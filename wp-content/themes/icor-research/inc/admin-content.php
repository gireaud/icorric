<?php
/**
 * Página de administración "Contenido de la web".
 *
 * Permite editar todos los textos de la landing en inglés y español sin
 * tocar código. Los valores se guardan en las opciones icor_content_en /
 * icor_content_es y se aplican en icor_strings() (inc/strings.php).
 */

/**
 * Definición de los campos editables, agrupados por sección.
 * type: 'text' (una línea), 'textarea' (varias líneas) o 'repeater'.
 */
function icor_content_fields() {
	return array(
		'Hero (portada)' => array(
			'hero_kicker' => array( 'Bajada superior', 'text' ),
			'hero_title'  => array( 'Título', 'textarea' ),
			'hero_sub'    => array( 'Subtítulo', 'textarea' ),
			'hero_cta1'   => array( 'Botón 1', 'text' ),
			'hero_cta2'   => array( 'Botón 2', 'text' ),
			'hero_cta3'   => array( 'Botón 3 (LinkedIn)', 'text' ),
		),
		'About (sobre el centro)' => array(
			'about_kicker' => array( 'Bajada superior', 'text' ),
			'about_title'  => array( 'Título', 'text' ),
			'about_body'   => array( 'Texto', 'textarea' ),
		),
		'Connected but distinct' => array(
			'distinct_kicker'     => array( 'Bajada superior', 'text' ),
			'distinct_title'      => array( 'Título', 'text' ),
			'distinct_body'       => array( 'Texto', 'textarea' ),
			'distinct_col1_title' => array( 'Columna 1 — título', 'text' ),
			'distinct_col1_body'  => array( 'Columna 1 — texto', 'textarea' ),
			'distinct_col2_title' => array( 'Columna 2 — título', 'text' ),
			'distinct_col2_body'  => array( 'Columna 2 — texto', 'textarea' ),
		),
		'Research & Innovation Areas' => array(
			'research_kicker' => array( 'Bajada superior', 'text' ),
			'research_title'  => array( 'Título', 'text' ),
			'research_lead'   => array( 'Introducción', 'textarea' ),
			'research_areas'  => array( 'Áreas — una por línea con formato: Título | Descripción', 'repeater' ),
		),
		'Team (equipo)' => array(
			'team_kicker' => array( 'Bajada superior', 'text' ),
			'team_title'  => array( 'Título', 'text' ),
			'team_lead'   => array( 'Introducción', 'textarea' ),
			'team'        => array( 'Integrantes — uno por línea con formato: Nombre | Cargo | Bio', 'repeater' ),
		),
		'Collaboration' => array(
			'collab_kicker' => array( 'Bajada superior', 'text' ),
			'collab_title'  => array( 'Título', 'text' ),
			'collab_body'   => array( 'Texto', 'textarea' ),
			'collab_items'  => array( 'Ítems — uno por línea', 'repeater' ),
			'collab_cta'    => array( 'Botón', 'text' ),
		),
		'Contact (contacto)' => array(
			'contact_kicker'      => array( 'Bajada superior', 'text' ),
			'contact_title'       => array( 'Título', 'text' ),
			'contact_body'        => array( 'Texto', 'textarea' ),
			'contact_email_label' => array( 'Etiqueta del correo', 'text' ),
		),
		'Footer y SEO' => array(
			'footer_note'      => array( 'Nota del pie de página', 'textarea' ),
			'footer_contact'   => array( 'Etiqueta "Contacto"', 'text' ),
			'meta_description' => array( 'Meta descripción (SEO)', 'textarea' ),
		),
	);
}

/** Reconstruye el texto por defecto de un campo repetidor para prellenar el formulario. */
function icor_default_raw( $key, $lang ) {
	// Evitamos la fusión con opciones llamando a los defaults directamente:
	// icor_strings() ya fusiona, pero como aquí solo mostramos placeholders,
	// usamos los valores actuales (con overrides) como referencia editable.
	$strings = icor_strings( $lang );
	if ( ! isset( $strings[ $key ] ) ) {
		return '';
	}
	$val = $strings[ $key ];
	if ( ! is_array( $val ) ) {
		return $val;
	}
	$lines = array();
	foreach ( $val as $item ) {
		$lines[] = is_array( $item ) ? implode( ' | ', $item ) : $item;
	}
	return implode( "\n", $lines );
}

/** Registra el menú del panel. */
add_action( 'admin_menu', function () {
	add_menu_page(
		'Contenido de la web',
		'Contenido de la web',
		'manage_options',
		'icor-content',
		'icor_render_content_page',
		'dashicons-edit-large',
		3
	);
} );

/** Renderiza la página de edición. */
function icor_render_content_page() {
	$saved_en = get_option( 'icor_content_en', array() );
	$saved_es = get_option( 'icor_content_es', array() );
	$value    = function ( $saved, $key, $lang ) {
		if ( isset( $saved[ $key ] ) && '' !== trim( (string) $saved[ $key ] ) ) {
			return $saved[ $key ];
		}
		return icor_default_raw( $key, $lang );
	};
	?>
	<div class="wrap">
		<h1>Contenido de la web — ICOR R&amp;I</h1>
		<p>Edita los textos del sitio en inglés y español. Deja un campo vacío para usar el texto por defecto.
		En los campos "una por línea", cada línea es un elemento; donde dice <code>|</code>, separa los datos con esa barra.</p>
		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Contenido guardado.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="icor_save_content">
			<?php wp_nonce_field( 'icor_save_content', 'icor_content_nonce' ); ?>
			<?php foreach ( icor_content_fields() as $group => $fields ) : ?>
				<h2 style="margin-top:2em;border-bottom:1px solid #dcdcde;padding-bottom:6px"><?php echo esc_html( $group ); ?></h2>
				<table class="form-table" role="presentation">
					<thead>
						<tr>
							<th style="width:180px"></th>
							<th style="text-align:left">Inglés (EN)</th>
							<th style="text-align:left">Español (ES)</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ( $fields as $key => $meta ) :
						list( $label, $type ) = $meta;
						$en = $value( $saved_en, $key, 'en' );
						$es = $value( $saved_es, $key, 'es' );
						$rows = ( 'repeater' === $type ) ? 6 : ( 'textarea' === $type ? 3 : 1 );
						?>
						<tr>
							<th scope="row"><label><?php echo esc_html( $label ); ?></label></th>
							<?php foreach ( array( 'icor_en' => $en, 'icor_es' => $es ) as $name => $current ) : ?>
								<td>
									<?php if ( 'text' === $type ) : ?>
										<input type="text" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $key ); ?>]"
											value="<?php echo esc_attr( $current ); ?>" class="large-text">
									<?php else : ?>
										<textarea name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $key ); ?>]"
											rows="<?php echo esc_attr( $rows ); ?>" class="large-text"><?php echo esc_textarea( $current ); ?></textarea>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>
			<?php submit_button( 'Guardar contenido' ); ?>
		</form>
	</div>
	<?php
}

/** Guarda el contenido enviado desde el formulario. */
add_action( 'admin_post_icor_save_content', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sin permisos.' );
	}
	check_admin_referer( 'icor_save_content', 'icor_content_nonce' );

	$posted = array(
		'icor_content_en' => isset( $_POST['icor_en'] ) ? (array) $_POST['icor_en'] : array(),
		'icor_content_es' => isset( $_POST['icor_es'] ) ? (array) $_POST['icor_es'] : array(),
	);

	foreach ( $posted as $option => $raw ) {
		$clean = array();
		foreach ( $raw as $key => $val ) {
			$clean[ sanitize_key( $key ) ] = sanitize_textarea_field( wp_unslash( $val ) );
		}
		update_option( $option, $clean );
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'icor-content', 'updated' => '1' ), admin_url( 'admin.php' ) ) );
	exit;
} );
