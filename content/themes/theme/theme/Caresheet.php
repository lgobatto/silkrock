<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 31/01/17
 * Time: 20:25
 */

namespace SilkRock;


use LGobatto\RegisterPostType;
use LGobatto\Taxonomy;
use LGobatto\Post;
use WP_Query;

class Caresheet extends RegisterPostType {

	/**
	 * Caresheet constructor.
	 */
	public function __construct() {
		$caresheet = new Post( 'caresheet', 'Caresheet', 'Caresheets' );
		$caresheet->setRewrite( __( 'caresheets', 'lgobatto' ) );
		$caresheet->setArgs( [
			Post::TITLE,
			Post::THUMBNAIL
		], 'dashicons-media-spreadsheet', __( 'caresheets', 'lgobatto' ) );

		parent::__construct( $caresheet );
		add_shortcode( 'caresheets', [ $this, 'caresheets' ] );
	}
    public static function  has_sheets($specie){
	    $args = [
			'post_type'      => 'caresheet',
			'posts_per_page' => -1
		];
		if ( $specie ) {
			$args['meta_query'][] = [
				'key'   => 'bird_specie',
				'value' => $specie
			];
		}
		$related = new WP_Query( $args );
		return $related->have_posts();
    }
	public function caresheets( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'specie'      => '',
			'limit'       => - 1,
			'show_filter' => false,
			'remove' => false

		), $atts ) );
		ob_start();
		$args = [
			'post_type'      => 'caresheet',
			'posts_per_page' => $limit
		];
		if ( $specie ) {
			$args['meta_query'][] = [
				'key'   => 'bird_specie',
				'value' => $specie
			];
		}
		if($remove){
		    $args['post__not_in']  = [$remove];
		}
		$related = new WP_Query( $args );
		if ( $show_filter ) {
			$isotope           = 'isotope';
			$classes = [ 'secondary', 'warning', 'alert', 'primary', 'dark', 'storm' ];
			$available_species = $available_groups = [];
			foreach ( $related->posts as $bird ) {
				$specie = get_field( 'bird_specie', $bird->ID );
				if ( ! array_key_exists( $specie->post_name, $available_species ) ) {
					$group                                   = get_field( 'bird_group', $specie->ID );
					$group                                   = get_term( $group );
					$available_species[ $specie->post_name ] = [
						'name'  => $specie->post_title,
						'group' => $group->slug
					];
					if ( ! array_key_exists( $group->slug, $available_groups ) ) {
						$available_groups[ $group->slug ] = $group->name;
					}
				}
			}
			?>
            <div class="row">
            <div class="small-12 medium-2 columns">
                <h3 class="themecolor-red text-center">Filtros</h3>
                <h4>Grupos:</h4>
                <div class="stacked button-group filter-grid group-filter">
                    <a class="button" data-filter="*">Mostrar todos</a>
		            <?php
		            $count   = 0;
		            foreach ( $available_groups as $key => $value ) {
			            if ( $count >= count( $classes ) ) {
				            $count = 0;
			            }
			            printf( '<a class="button %s" data-filter="%s">%s</a>', $classes[ $count ], $key, $value );
			            $count ++;
		            }
		            ?>
                </div>
                <h4>Espécies:</h4>
                <div class="stacked button-group filter-grid species-filter">
                    <a class="button" data-filter="*" style="width: 100%;">Mostrar todos</a>
					<?php
					$count   = 0;
					foreach ( $available_species as $key => $value ) {
						if ( $count >= count( $classes ) ) {
							$count = 0;
						}
						printf( '<a class="button with-filter %s %s" data-filter="%s"  style="width: 100%%;">%s</a>', $classes[ $count ], $value['group'], $key, $value['name'] );
						$count ++;
					}
					?>
                </div>
            </div>
            <div class="small-12 medium-10 columns">
			<?php
		}
		if ( $related->have_posts() ) {
			printf( '<div class="row small-up-1 medium-up-3 align-center bird-species %s">', $isotope );
			while ( $related->have_posts() ) {
				$related->the_post();
				$image     = get_post_thumbnail_id();
				$specie    = get_field( 'bird_specie' );
				$group    = get_field( 'bird_group', $specie->ID );
				$group    = get_term( $group );
				$image_src = wp_get_attachment_image_url( $image, [ 500, 281 ] );
				$title     = get_the_title() . '<br><small>' . $specie->post_title . '</small>';
				$url       = get_permalink();
				$sc        = sprintf( '[sliding_box image="%s" title="%s" link="%s" animate="fadeInUp"]', $image_src, $title, $url );
				printf( '<div class="column bird %s %s">', $specie->post_name, $group->slug );
				echo do_shortcode( $sc );
				print( '</div>' );
			}
			print( '</div>' );
			if ( $show_filter ) {
				print ( '</div></div>' );
			}
		} else {
			?>
            <div class="row">
                <div class="small-12 columns">
                    <h3 class="text-center">Em Breve.</h3>
                </div>
            </div>
			<?php
		}
		wp_reset_postdata();

		return ob_get_clean();
	}

}

new Caresheet();