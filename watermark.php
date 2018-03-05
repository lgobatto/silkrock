<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 30/03/17
 * Time: 19:22
 */
require_once dirname( __FILE__ ) . '/app/wp-load.php';
$watermark_file = dirname( __FILE__ ) . '/logo-white.png';
$uploads_dir    = wp_upload_dir();
$uploads_dir    = $uploads_dir['basedir'] . '/watermark';
if ( ! file_exists( $uploads_dir ) ) {
	wp_mkdir_p( $uploads_dir );
}
$original_image = '.' . $_GET['image'];
$base_name      = basename( $original_image );
$watermarked    = $uploads_dir . '/' . $base_name;
if ( file_exists( $watermarked ) ) {
	header( 'Content-type: image/png' );
	readfile( $watermarked );
} else {
	$image           = new Imagick( $original_image );
	$watermark_image = new Imagick( $watermark_file );
	$width           = $image->getImageWidth();
	$height          = $image->getImageHeight();
	$scale_to        = ( $width * 0.28 ) > 150 ? $width * 0.28 : 150;
	$watermark_image->scaleImage( $scale_to, 0 );
	$x = $width - $watermark_image->getImageWidth() - ( $width * 0.04 );
	if ( $width == $height ) {
		$x = ( $width - $watermark_image->getImageWidth() ) / 2;
	}
	$y = $height - $watermark_image->getImageHeight() - ( $height * 0.05 );
	$image->compositeImage( $watermark_image, imagick::COMPOSITE_OVER, $x, $y );
	$image->writeImage( $watermarked );
	header( 'Content-type: image/png' );
	echo $image;
}