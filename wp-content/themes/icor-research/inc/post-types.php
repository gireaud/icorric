<?php
/**
 * Tipos de contenido de la fase 2: Proyectos, Publicaciones y Noticias.
 * Editables desde el panel de WordPress (Gutenberg) con su propio menú.
 */

function icor_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'add_new'            => 'Añadir',
		'add_new_item'       => "Añadir {$singular}",
		'edit_item'          => "Editar {$singular}",
		'new_item'           => "Nuevo {$singular}",
		'view_item'          => "Ver {$singular}",
		'view_items'         => "Ver {$plural}",
		'search_items'       => "Buscar {$plural}",
		'not_found'          => "No hay {$plural} todavía",
		'not_found_in_trash' => "No hay {$plural} en la papelera",
		'all_items'          => $plural,
	);
}

add_action( 'init', function () {
	$common = array(
		'public'       => true,
		'show_in_rest' => true, // editor de bloques (Gutenberg)
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_position' => 5,
	);

	register_post_type( 'proyecto', array_merge( $common, array(
		'labels'      => icor_cpt_labels( 'Proyecto', 'Proyectos' ),
		'menu_icon'   => 'dashicons-lightbulb',
		'has_archive' => 'proyectos',
		'rewrite'     => array( 'slug' => 'proyectos' ),
	) ) );

	register_post_type( 'publicacion', array_merge( $common, array(
		'labels'      => icor_cpt_labels( 'Publicación', 'Publicaciones' ),
		'menu_icon'   => 'dashicons-media-document',
		'has_archive' => 'publicaciones',
		'rewrite'     => array( 'slug' => 'publicaciones' ),
	) ) );

	register_post_type( 'noticia', array_merge( $common, array(
		'labels'      => icor_cpt_labels( 'Noticia', 'Noticias' ),
		'menu_icon'   => 'dashicons-megaphone',
		'has_archive' => 'noticias',
		'rewrite'     => array( 'slug' => 'noticias' ),
	) ) );
}, 5 );

/**
 * Refresca las reglas de enlaces permanentes una sola vez cuando cambian
 * las rutas (para que /proyectos/, /publicaciones/ y /noticias/ funcionen
 * sin entrar a Ajustes → Enlaces permanentes). Sube el número al cambiar rutas.
 */
add_action( 'init', function () {
	if ( '2' !== get_option( 'icor_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'icor_rewrite_version', '2' );
	}
}, 99 );
