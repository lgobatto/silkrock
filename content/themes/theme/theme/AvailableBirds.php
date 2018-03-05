<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 31/01/17
 * Time: 19:51
 */

namespace SilkRock;


use LGobatto\Helper;
use LGobatto\Post;
use LGobatto\RegisterPostType;
use LGobatto\Taxonomy;
use WP_Query;

class AvailableBirds extends RegisterPostType {
	/**
	 * AvailableBirds constructor.
	 */
	public function __construct() {
		$availableBirds = new Post( 'available-bird', __( 'Filhote', 'theme' ), __( 'Filhotes', 'theme' ) );
		$availableBirds->setRewrite( __( 'filhotes', 'theme' ) );
		$availableBirds->setArgs( [
			Post::THUMBNAIL
		], Helper::theme_image_url( 'available-birds-icon.png', false ), __( 'filhotes', 'theme' ) );
		$genotype = new Taxonomy( 'genotype', __( 'Genótipo', 'theme' ), __( 'Genótipos', 'theme' ), null, false, false, false, false, true, false, false );
		parent::__construct( $availableBirds, [ $genotype ] );
		add_shortcode( 'available_birds', [ $this, 'available_birds' ] );
		add_action( 'pre_get_posts', [ $this, 'filter_query' ] );
		add_action( 'acf/save_post', [ $this, 'modify_post_title' ], 20 );
	}

	public function available_birds( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'specie'      => '',
			'limit'       => - 1,
			'featured'    => false,
			'show_filter' => false
		), $atts ) );
		ob_start();
		$args = [
			'post_type'      => 'available-bird',
			'posts_per_page' => $limit,
			'meta_query'     => [
				[
					'key'   => '_available_bird_sold',
					'value' => true
				]
			]
		];
		if ( $specie ) {
			$args['meta_query'][] = [
				'key'   => '_available_bird_specie',
				'value' => $specie
			];
		}
		if ( $featured ) {
			$args['meta_query'][] = [
				'key'   => '_available_bird_featured',
				'value' => $featured
			];
		}
		if ( is_singular( 'available-bird' ) ) {
			$args['post__not_in'] = [ get_the_ID() ];
		}
		$isotope = '';
		$related = new WP_Query( $args );
		if ( $show_filter ) {
			$isotope           = 'isotope';
			$classes           = [ 'secondary', 'warning', 'alert', 'primary', 'dark', 'storm' ];
			$available_species = $available_groups = [];
			foreach ( $related->posts as $bird ) {
				$specie = get_field( '_available_bird_specie', $bird->ID );
				if ( $specie->ID == null ) {
					var_dump( get_edit_post_link( $bird->ID ) );
					continue;
				}
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
					$count = 0;
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
					$count = 0;
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
			ob_start();
			$columns = $show_filter ? 3 : 4;
			printf( '<div class="row align-center bird-species %s">', $show_filter ? $isotope : "" );
			$count = 0;
			while ( $related->have_posts() ) {
				$related->the_post();
				$image  = get_post_thumbnail_id();
				$specie = get_field( '_available_bird_specie' );
				$group  = get_field( 'bird_group', $specie->ID );
				$group  = get_term( $group );
				if ( ! $image ) {
					$image = get_post_thumbnail_id( $specie->ID );
				}
				$price          = get_field( '_available_bird_price' );
				$birthdate      = get_field( '_available_bird_birthdate' );
				$gender         = get_field( '_available_bird_gender' );
				$identification = get_field( '_available_bird_identification' );
				$featured       = get_field( '_available_bird_featured' );
				$title          = get_the_title();
				$content        = sprintf(
					'[pricing_item image="%s" title="%s<br><small>%s</small>" currency="R$" currency_pos="left" price="%s,00" period="" subtitle="%s" link_title="Saiba mais" link="%s" featured="%s" style="box" animate="fadeIn"][/pricing_item]',
					wp_get_attachment_image_url( $image, [ 500, 500 ] ),
					$specie->post_title,
					$title,
					$price,
					get_field( 'mutation_name', $mutation->ID ) ? 'Mutação: ' . get_field( 'mutation_name', $mutation->ID ) : '&nbsp;',
					get_permalink(),
					$featured
				);
				printf( '<div class="%s column bird %s %s">', $show_filter ? "" : "small-12", $specie->post_name, $group->slug );
				?>
                <div class="row align-top">
                    <div class="medium-2 columns">
                        <img class="thumbnail" src="<?php echo wp_get_attachment_image_url( $image, [ 150, 150 ] ) ?>">
                    </div>
                    <div class="medium-7 columns">
                        <p>
                            <strong><?php echo $specie->post_title; ?></strong><br>
							<?php echo $title; ?>
                        </p>
                    </div>
                    <div class="medium-3 columns">
                        <p style="line-height: 1.1" class="text-right">
                            <small>6 parcelas de:</small>
                            <br>
                            <span>R$ <?php echo number_format( $price / 6, 2, ',', '.' ); ?></span><br>
                            <small>ou R$ <?php echo number_format( $price, 2, ',', '.' ); ?> à vista.</small>
                        </p>
                        <a href="<?php echo get_permalink(); ?>" class="button success small expanded"><i class="fa fa-fw fa-plus"></i>mais detalhes</a>
                    </div>
                </div>
				<?php
				if ( ( $count + 1 ) < $related->found_posts ) {
					echo "<hr>";
				}
				print( '</div>' );
				$count ++;
			}
			if ( $limit > 0 ):
				?>
                <div class="text-center">
                    <a href="/filhotes" class="button warning large">Ver todos!</a>
                </div>
			<?php
			endif;
			print( '</div>' );
			if ( $show_filter ) {
				?>
                </div>
                </div>
				<?php
			}
			$content = ob_get_clean();
			echo do_shortcode( '[emaillocker id="2003"]' . $content . '[/emaillocker]' );
		} else {
			if ( is_singular( 'available-bird' ) ) {
				?>
                <div class="row align-center">
                    <div class="small-12 medium-6 columns">
                        <p class="text-center big themecolor-dark">Infelizmente não temos filhotes <?php if ( $specie ) {
								echo 'de ' . get_the_title( $specie ) . ' ';
							} ?>disponíveis para venda.<br>Volte novamente dentro de alguns dias ou consulte-nos para disponibilidade e reservas.</p>
                    </div>
                </div>
                <div class="row align-center">
                    <div class="small-12 medium-6 columns"><?php gravity_form( 2, false, false, false, null, true ); ?></div>
                </div>
				<?php
			}
		}
		wp_reset_postdata();

		return ob_get_clean();
	}

	public function filter_query( $query ) {
		/**
		 * @var $query WP_Query
		 */
		// do not modify queries in the admin
		if ( is_admin() ) {

			return $query;

		}


		// only modify queries for 'event' post type
		if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'available-bird' ) {

			// allow the url to alter the query
			if ( isset( $_GET['_available_bird_sold'] ) ) {

				$query->set( 'meta_key', '_available_bird_sold' );
				$query->set( 'meta_value', 1 );

			}

		}


		// return
		return $query;
	}

	public function modify_post_title( $post_id ) {
		$post = get_post( $post_id );
		if ( $post->post_type == 'available-bird' ) {
			$bird = get_field( '_available_bird_specie' );
			$type = get_field( '_available_bird_type' );

//			wp_update_post( [
//				'ID'         => $post_id,
//				'post_title' => sprintf( '%s %s', $bird->post_title, $name )
//			] );
		}
	}
}

new AvailableBirds();