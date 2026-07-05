<?php
/**
 * Formulario de contacto nativo (sin plugins).
 *
 * Cada envío se guarda como entrada privada del CPT "icor_contact"
 * (respaldo si el correo no llega) y se notifica por wp_mail al correo
 * institucional configurado en el Personalizador.
 */

add_action( 'init', function () {
	register_post_type( 'icor_contact', array(
		'labels'       => array(
			'name'          => 'Mensajes de contacto',
			'singular_name' => 'Mensaje de contacto',
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-email',
		'supports'     => array( 'title', 'editor' ),
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap' => true,
	) );
} );

function icor_handle_contact_form() {
	$lang     = ( isset( $_POST['icor_lang'] ) && 'es' === $_POST['icor_lang'] ) ? 'es' : 'en';
	$redirect = 'es' === $lang ? home_url( '/es/' ) : home_url( '/' );

	$fail = function () use ( $redirect ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) . '#contact' );
		exit;
	};

	if ( ! isset( $_POST['icor_contact_nonce'] ) || ! wp_verify_nonce( $_POST['icor_contact_nonce'], 'icor_contact' ) ) {
		$fail();
	}

	// Honeypot: los bots rellenan el campo oculto.
	if ( ! empty( $_POST['icor_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'ok', $redirect ) . '#contact' );
		exit;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['icor_name'] ?? '' ) );
	$org     = sanitize_text_field( wp_unslash( $_POST['icor_org'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['icor_email'] ?? '' ) );
	$type    = sanitize_text_field( wp_unslash( $_POST['icor_type'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['icor_message'] ?? '' ) );

	if ( ! $name || ! $email || ! $message || ! is_email( $email ) ) {
		$fail();
	}

	$body = sprintf(
		"Nombre: %s\nOrganización: %s\nCorreo: %s\nTipo de colaboración: %s\nIdioma del formulario: %s\n\nMensaje:\n%s",
		$name,
		$org ? $org : '—',
		$email,
		$type ? $type : '—',
		strtoupper( $lang ),
		$message
	);

	// Respaldo en el panel (Mensajes de contacto) aunque falle el correo.
	wp_insert_post( array(
		'post_type'    => 'icor_contact',
		'post_status'  => 'private',
		'post_title'   => sprintf( '%s — %s', $name, $type ? $type : 'Contacto' ),
		'post_content' => $body,
	) );

	wp_mail(
		icor_contact_email(),
		sprintf( '[ICOR R&I] Nuevo contacto: %s (%s)', $name, $type ? $type : 'sin tipo' ),
		$body,
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_safe_redirect( add_query_arg( 'contact', 'ok', $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_icor_contact', 'icor_handle_contact_form' );
add_action( 'admin_post_nopriv_icor_contact', 'icor_handle_contact_form' );

/** Muestra el aviso de éxito/error sobre el formulario. */
function icor_contact_form_notices( $strings ) {
	if ( ! isset( $_GET['contact'] ) ) {
		return;
	}
	if ( 'ok' === $_GET['contact'] ) {
		printf( '<div class="form-notice form-notice--ok">%s</div>', esc_html( $strings['form_ok'] ) );
	} else {
		printf( '<div class="form-notice form-notice--error">%s</div>', esc_html( $strings['form_error'] ) );
	}
}
