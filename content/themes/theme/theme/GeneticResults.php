<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 27/03/17
 * Time: 13:49
 */

namespace SilkRock;

use LGobatto\Post;
use LGobatto\RegisterPostType;

class GeneticResults extends RegisterPostType {

	/**
	 * GeneticResults constructor.
	 */
	public function __construct() {
		$geneticResults = new Post( 'genetic_results', __( 'Genetic Result', 'theme' ), __( 'Genetic Results', 'theme' ) );
		$geneticResults->setArgs( [ Post::ATTRIBUTES ], 'dashicons-chart-line', false, false, true, true, true, 5, false, false, false, true, false );
		parent::__construct( $geneticResults );
		add_action( 'acf/save_post', [ $this, 'modify_post_title' ], 20 );
	}

	public function modify_post_title( $post_id ) {
		$post = get_post( $post_id );
		if ( $post->post_type == 'genetic_results' ) {
			$geneticResult = new GeneticResult( $post_id );
			$geneticResult->file->updateFile();
			wp_update_post( [
				'ID'         => $post_id,
				'post_title' => $geneticResult->getTitle()
			] );
		}
	}
}

new GeneticResults();