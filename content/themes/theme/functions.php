<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 25/01/17
 * Time: 15:04
 */
/**
 * Make translation works for all includes before wordpress action and filter hooks.
 */
/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

// White Label --------------------------------------------
define( 'WHITE_LABEL', true );

function kill_theme_wpse_188906( $themes ) {
	unset( $themes['betheme'] );

	return $themes;
}

add_filter( 'wp_prepare_themes_for_js', 'kill_theme_wpse_188906' );
load_child_theme_textdomain( 'theme', get_template_directory() . '/lang' );
load_theme_textdomain( 'theme', get_template_directory() . '/lang' );
add_action( 'init', function () {
	load_child_theme_textdomain( 'theme', get_template_directory() . '/lang' );
	load_theme_textdomain( 'theme', get_template_directory() . '/lang' );
} );
require_once ROOT_PATH . '/vendor/autoload.php';
$includes = glob( sprintf( '%s/lib/*.php', dirname( __FILE__ ) ) );
foreach ( $includes as $include ) {
	if ( ! empty( $include ) && file_exists( $include ) ) {
		/** @noinspection PhpIncludeInspection */
		require_once $include;
	}
}
unset( $includes, $include );
$includes = glob( sprintf( '%s/theme/*.php', dirname( __FILE__ ) ) );
foreach ( $includes as $include ) {
	if ( ! empty( $include ) && file_exists( $include ) ) {
		/** @noinspection PhpIncludeInspection */
		require_once $include;
	}
}
unset( $includes, $include );
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

add_filter( 'auto_update_plugin', '__return_true' );

add_filter( 'acf/settings/google_api_key', function () {
	return 'AIzaSyAeq274bKf8x80RgHsMYgtyxtvKjZxWYTg';
} );

add_filter( "gform_address_types", "brazilian_address", 10, 2 );

function brazilian_address( $address_types, $form_id ) {
	$address_types["brazil"] = array(
		"label"       => "Brasil",
		"country"     => "Brasil",
		"zip_label"   => "CEP",
		"state_label" => "Estado",
		"states"      => array( "", "Acre", "Alagoas", "Amapa", "Amazonas", "Bahia", "Ceara", "Distrito Federal", "Espirito Santo", "Goias", "Maranhao", "Mato Grosso", "Mato Grosso do Sul", "Minas Gerais", "Para", "Paraiba", "Parana", "Pernambuco", "Piaui", "Roraima", "Rondonia", "Rio de Janeiro", "Rio Grande do Norte", "Rio Grande do Sul", "Santa Catarina", "Sao Paulo", "Sergipe", "Tocantins" )
	);

	return $address_types;
}