<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 16/08/17
 * Time: 19:43
 */

add_filter( 'foogallery_attachment_html_link_attributes', 'theme_add_html_attributes' );
function theme_add_html_attributes( $attr ) {
	global $current_foogallery;

	$attr['data-fancybox'] = "foogallery-{$current_foogallery->ID}";

	return $attr;
}

function open_row( $output, $type = 0, $last = false ) {
	$separator = [
		'#f6f6f6',
		'#fcfcfc',
		'#2f4858'
	];
	$row       = [
		'[vc_row full_width="stretch_row" enable_bottom_separator="true" bottom_separator_style="arrow-right" bottom_separator_color="%s" css=".vc_custom_1503864603051{padding-top: 70px !important;padding-bottom: 70px !important;}"]',
		'[vc_row full_width="stretch_row" enable_bottom_separator="true" bottom_separator_style="arrow-right" bottom_separator_color="%s" css=".vc_custom_1503864879014{padding-top: 70px !important;padding-bottom: 70px !important;background-color: #f6f6f6 !important;}"]'
	];
	if ( $last ) {
		$output .= sprintf( $row[ $type ], $separator[2] );
	} else {
		$output .= sprintf( $row[ $type ], $separator[ $type ] );
	}

	return $output;
}

function separator_row( $color ) {
	echo do_shortcode( sprintf( '[vc_row full_width="stretch_row" enable_bottom_separator="true" bottom_separator_style="arrow-right" bottom_separator_color="%s"][/vc_row]', $color ) );
}

function close_row( $output ) {
	$output .= '[/vc_row]';

	return $output;
}

function open_column( $output, $size ) {
	$output .= '[vc_column width="' . $size . '"][vc_empty_space height=70]';

	return $output;
}

function close_column( $output ) {
	$output .= '[vc_empty_space height=70][/vc_column]';

	return $output;
}