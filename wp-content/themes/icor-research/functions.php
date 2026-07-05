<?php
/**
 * ICOR Research & Innovation Center — funciones del tema.
 */

require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/strings.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/admin-content.php';

/**
 * Idioma activo del sitio.
 *
 * Si Polylang está activo, usa su idioma actual (así toda la landing y el
 * contenido siguen el mismo idioma). Si no, cae al sistema propio: 'es' en la
 * página con plantilla ES, 'en' en el resto.
 */
function icor_current_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$slug = pll_current_language( 'slug' );
		if ( $slug ) {
			return ( 0 === strpos( $slug, 'es' ) ) ? 'es' : 'en';
		}
	}
	return is_page_template( 'page-templates/template-es.php' ) ? 'es' : 'en';
}

/** Correo de contacto configurable (Ajustes → General usa admin_email como fallback). */
function icor_contact_email() {
	$email = get_option( 'icor_contact_email' );
	return $email ? $email : get_option( 'admin_email' );
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'icor-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'icor-research',
		get_stylesheet_uri(),
		array( 'icor-fonts' ),
		filemtime( get_template_directory() . '/style.css' )
	);
} );

/* Meta description institucional en portada / página ES. */
add_action( 'wp_head', function () {
	if ( is_front_page() || is_page_template( 'page-templates/template-es.php' ) ) {
		$strings = icor_strings( icor_current_lang() );
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $strings['meta_description'] ) );
	}
}, 1 );

/* Ajustes propios en el Personalizador (Apariencia → Personalizar → Contacto y redes). */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'icor_settings', array(
		'title'    => 'Contacto y redes (ICOR R&I)',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'icor_contact_email', array(
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'icor_contact_email', array(
		'label'       => 'Correo de contacto',
		'description' => 'Destino del formulario y correo visible en la web (ej. research@icor.cl).',
		'section'     => 'icor_settings',
		'type'        => 'email',
	) );

	$wp_customize->add_setting( 'icor_linkedin_url', array(
		'type'              => 'option',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'icor_linkedin_url', array(
		'label'       => 'URL de LinkedIn institucional',
		'section'     => 'icor_settings',
		'type'        => 'url',
	) );
} );
