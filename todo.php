<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 23/10/17
 * Time: 13:43
 */
require_once dirname( __FILE__ ) . '/app/wp-load.php';
//
//$species = new WP_Query( [
//	'post_type'      => 'bird',
//	'posts_per_page' => - 1,
//	'orderby'        => 'post_title',
//	'order'          => 'ASC'
//] );
//$up_dir  = WP_CONTENT_DIR . '/uploads/Species/%s';
//foreach ( $species->posts as $post ) {
//	$sp_dir = sprintf( $up_dir, $post->post_title );
//	if ( ! file_exists( $sp_dir ) ) {
//		mkdir( $sp_dir );
//	}
//	printf( '%s <br>', $post->post_title );
//	$mutations = new WP_Query( [
//		'post_type'      => 'bird-mutations',
//		'posts_per_page' => - 1,
//		'meta_query'     => [
//			[
//				'key'     => 'bird_specie',
//				'value'   => [ $post->ID ],
//				'compare' => 'IN'
//			]
//		]
//	] );
//	foreach ( $mutations->posts as $mut ) {
//		$mt_name = get_field( 'mutation_name', $mut->ID );
//		//printf( '- %s <br>',  $mt_name);
//		$mt_dir = $sp_dir . '/' . $mt_name;
//		if(! file_exists($mt_dir)){
//			mkdir($mt_dir);
//		}
//	}
//}
$count     = 0;
$mutations = [];
if ( ( $handle = fopen( "available_birds.csv", "r" ) ) !== false ) {
	while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
		if ( $count == 0 ) {
			$count ++;
			continue;
		}
		$group        = $data[5];
		$specie       = $data[1];
		$mutation_1   = $data[7];
		$mutation_1_f = sprintf( '%s %s', $specie, $mutation_1 );
		$mutation_2   = $mutation_2_f = false;
		$specie = str_replace("  ", " ", $specie);
		if ( $data[10] ) {
			$mutation_2   = $data[10];
			$mutation_2_f = sprintf( '%s %s', $specie, $mutation_2 );
		}
		if ( ! array_key_exists( $mutation_1_f, $mutations ) ) {
			$mutations[ $mutation_1_f ] = [
				$specie,
				$mutation_1,
				$mutation_1_f
			];
		}
		if ( $mutation_2_f ) {

			if ( ! array_key_exists( $mutation_2_f, $mutations ) ) {
				$mutations[ $mutation_2_f ] = [
					$specie,
					$mutation_2,
					$mutation_2_f
				];
			}
		}
		$count ++;
	}
}
$mut = fopen( 'mutations.csv', 'w' );
fputcsv( $mut, [ 'specie', 'name', 'formatted_name' ] );
foreach ( $mutations as $m ) {
	fputcsv( $mut , $m );
}
fclose( $mut );