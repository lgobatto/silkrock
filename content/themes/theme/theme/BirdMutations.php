<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 10/02/17
 * Time: 16:09
 */

namespace SilkRock;


use LGobatto\Post;
use LGobatto\RegisterPostType;
use LGobatto\Taxonomy;

class BirdMutations extends RegisterPostType {

	/**
	 * BirdMutations constructor.
	 */
	public function __construct() {
		$birdMutations = new Post( 'bird-mutations', __( 'Mutação', 'theme' ), __( 'Mutações', 'theme' ) );
		$birdMutations->setRewrite( __( 'mutacao', 'theme' ) );
		$birdMutations->setArgs(
			[
				Post::ATTRIBUTES
			],
			'dashicons-forms',
			__( 'mutacoes', 'theme' )
		);
		$mutationGroup = new Taxonomy( 'mutation-group', __( 'Grupo', 'theme' ), __( 'Grupos', 'theme' ), null, false, false, true, false, true, false, false );
		parent::__construct( $birdMutations, [ $mutationGroup ] );
		add_action( 'acf/save_post', [ $this, 'modify_post_title' ], 20 );
	}

	public function modify_post_title( $post_id ) {
		$post = get_post( $post_id );
		if ( $post->post_type == 'bird-mutations' ) {
			$bird = get_field( 'bird_specie' );
			$name = get_field( 'mutation_name' );
			wp_update_post( [
				'ID'         => $post_id,
				'post_title' => sprintf( '%s %s', $bird->post_title, $name )
			] );
		}
	}
}

new BirdMutations();