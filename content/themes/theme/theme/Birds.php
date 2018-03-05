<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 26/01/17
 * Time: 21:46
 */

namespace SilkRock;


use LGobatto\Helper;
use LGobatto\Post;
use LGobatto\RegisterPostType;
use LGobatto\Taxonomy;

class Birds extends RegisterPostType {

	/**
	 * Birds constructor.
	 */
	public function __construct() {
		$birds = new Post( __( 'bird', 'theme' ), __( 'Ave', 'theme' ), __( 'Aves', 'theme' ) );
		$birds->setRewrite( __( 'aves', 'theme' ) );
		$birds->setArgs( [
			Post::TITLE,
			Post::THUMBNAIL,
			Post::ATTRIBUTES
		], Helper::theme_image_url( 'birds-icon.png', false ), __( 'aves', 'theme' ) );
		$group = new Taxonomy( 'group', __( 'Grupo', 'theme' ), __( 'Grupos', 'theme' ), __( 'grupos', 'theme' ) );
		parent::__construct( $birds, [ $group ] );
		add_shortcode( 'birds_grid', [ $this, 'birds_grid' ] );
	}

	public function birds_grid( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'show_filter' => false,
			'limit' => -1

		), $atts ) );
		$args = [
			'post_type'      => 'bird',
			'posts_per_page' => $limit
		];
		if($limit != -1){
			$args['orderby'] = 'rand';
		}
		$birds             = new \WP_Query( $args );
		$available_filters = [];
		foreach ( $birds->posts as $bird ) {
			$group = get_field( 'bird_group', $bird->ID );
			$group = get_term( $group );
			if ( ! in_array( $group->slug, $available_filters ) ) {
				array_push( $available_filters, $group->slug );
			}
		}
		$isotope = '';
		$result  = '';
		if ( $show_filter ) {
			$isotope = 'isotope';
			$groups  = get_terms( [ 'taxonomy' => 'group' ] );
			$result  .= '<div class="expanded button-group filter-grid">';
			$result  .= '<a class="button" data-filter="*">Mostrar todos</a>';
			$count   = 0;
			$classes = [ 'secondary', 'warning', 'alert', 'primary', 'dark', 'storm' ];
			foreach ( $groups as $group ) {
				if ( ! in_array( $group->slug, $available_filters ) ) {
					continue;
				}
				if ( $count >= count( $classes ) ) {
					$count = 0;
				}
				$result .= sprintf( '<a class="button %s" data-filter="%s">%s</a>', $classes[ $count ], $group->slug, $group->name );
				$count ++;
			}
			$result .= '</div>';
		}
		$result .= '<div class="row small-up-12 medium-up-4 align-center bird-species ' . $isotope . '">';
		foreach ( $birds->posts as $bird ) {
			/**
			 * @var $bird \WP_Post
			 */
			$thumbnail       = get_post_thumbnail_id( $bird->ID );
			$name            = $bird->post_title;
			$scientific_name = get_field( 'bird_scientific_name', $bird->ID );
			$group           = get_field( 'bird_group', $bird->ID );
			$group           = get_term( $group );
			$result          .= sprintf( '<div class="column bird %s">', $group->slug );
			$result          .= '[mpc_ihover preset="mpc_preset_2" item_width="250" gap="15" effect="effect3" style="bottom_to_top" image_background_type="image" image_background_color="#2f4858" content_background_color="rgba(0,0,0,0.3)" title_font_preset="preset_1" title_font_color="#d8cbc7" title_font_size="24" title_font_line_height="1.5" title_font_transform="capitalize" title_font_align="center" content_font_preset="preset_2" content_font_color="#ffffff" content_font_size="18" content_font_line_height="1.3" content_font_align="center" spinner_top_color="#61a6d6" spinner_bottom_color="#33658a" divider_color="#d8cbc7" divider_height="2" divider_width="15" divider_margin_divider="true" divider_margin_css="margin-top:7px;margin-bottom:5px;"]';
			$result          .= sprintf( '[mpc_ihover_item thumbnail="%s" title="%s" url="url:%s|||"]%s[/mpc_ihover_item]', $thumbnail, $name, urlencode( get_permalink( $bird->ID ) ), $scientific_name );
			$result          .= '[/mpc_ihover]';
			$result          .= '</div>';
		}
		$result .= '</div>';

		return do_shortcode( $result );
	}
}

new Birds();