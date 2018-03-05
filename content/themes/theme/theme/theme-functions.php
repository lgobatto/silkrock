<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 29/09/17
 * Time: 14:50
 */
/* ---------------------------------------------------------------------------
 * Post Thumbnail | GET post thumbnail
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_post_thumbnail' ) )
{
	function mfn_post_thumbnail( $postID, $type = false, $style = false, $images_only = false ){
		$output = '';



		// Image Size ---------------------------------------------------------


		if( $type == 'portfolio' ){

			// Portfolio ----------------------
			if( $style == 'list' ){

				// Portfolio | List ----------------------
				$sizeH = 'portfolio-list';

			} elseif( $style == 'masonry-flat' ) {

				// Portfolio | Masonry Flat ----------------------
				$size = get_post_meta( $postID, 'mfn-post-size', true );
				if( $size == 'wide' ){
					$sizeH = 'portfolio-mf-w';
				} elseif( $size == 'tall' ) {
					$sizeH = 'portfolio-mf-t';
				} else {
					$sizeH = 'portfolio-mf';
				}

			} elseif( $style == 'masonry-minimal' ) {

				// Portfolio | Masonry Minimal ----------------------
				$sizeH = 'full';

			} else {

				// Portfolio | Default ----------------------
				$sizeH = 'blog-portfolio';

			}

		} elseif( $type == 'blog' && $style == 'photo' ){

			// Blog | Photo ----------------------
			$sizeH = 'blog-single';
			$sizeV = 'blog-single';

		} elseif( $type == 'related' ){

			// Related Posts ----------------------
			$sizeH = 'blog-portfolio';

		} elseif( is_single( $postID ) ){

			// Blog & Portfolio | Single ----------------------
			$sizeH = 'blog-single';
			$sizeV = 'full';

		} else {

			// Default ----------------------
			$sizeH = 'blog-portfolio';
			$sizeV = 'full';

		}



		// Link Wrap ----------------------------------------------------------


		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'large' );

		if( is_single( $postID ) ){

			// Single -----------------------------------------

			$link_before = '<a href="'. $large_image_url[0] .'" rel="prettyphoto">';
			$link_before .= '<div class="mask"></div>';

			$link_after = '</a>';
			$link_after .= '<div class="image_links">';
			$link_after .= '<a href="'. $large_image_url[0] .'" class="zoom" rel="prettyphoto"><i class="icon-search"></i></a>';
			$link_after .= '</div>';

			// Single | Post

			if( get_post_type() == 'post' ){

				// Blog | Single - Disable Image Zoom

				if( ! mfn_opts_get( 'blog-single-zoom' ) ){
					$link_before = '';
					$link_after = '';
				}

				// Blog | Single | Structured data

				if( mfn_opts_get( 'mfn-seo-schema-type' ) ){

					$link_before .= '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';

					$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'full' );

					$link_after_schema = '<meta itemprop="url" content="'. $image_url[0] .'"/>';
					$link_after_schema .= '<meta itemprop="width" content="'. mfn_get_attachment_data( $image_url[0], 'width' ) .'"/>';
					$link_after_schema .= '<meta itemprop="height" content="'. mfn_get_attachment_data( $image_url[0], 'height' ) .'"/>';

					$link_after_schema .= '</div>';

					$link_after = $link_after_schema . $link_after;
				}

			}

		} elseif( $type == 'portfolio' ){

			// Portfolio --------------------------------------

			$external = mfn_opts_get( 'portfolio-external' );

			// External Link to Project Page
			if( $image_links = ( get_post_meta( get_the_ID(), 'mfn-post-link', true ) ) ){
				$image_links_class = 'triple';
			} else {
				$image_links_class = 'double';
			}

			// Image Link
			if( $external == 'popup' ){

				// link popup
				$link_before 	= '<a href="'. $large_image_url[0] .'" rel="prettyphoto">';
				$link_title 	= '<a href="'. $large_image_url[0] .'" rel="prettyphoto">';

			} elseif( $external == 'disable' ){

				// disable details
				$link_before 	= '<a href="'. $large_image_url[0] .'" rel="prettyphoto[portfolio]">';
				$link_title 	= '<a href="'. $large_image_url[0] .'" rel="prettyphoto">';

			} elseif( $external && $image_links ){

				// link to project website
				$image_links_class = 'double';
				$link_before 	= '<a href="'. $image_links .'" target="'. $external .'">';
				$link_title 	= '<a href="'. $image_links .'" target="'. $external .'">';

			} else {

				// link to project details
				$link_before 	= '<a href="'. get_permalink() .'">';
				$link_title 	= '<a href="'. get_permalink() .'">';

			}

			$link_before .= '<div class="mask"></div>';

			$link_after = '</a>';

			// Hover
			if( mfn_opts_get( 'portfolio-hover-title' ) ){

				// Hover | Title
				$link_after .= '<div class="image_links hover-title">';
				$link_after .= $link_title . get_the_title() .'</a>';
				$link_after .= '</div>';

			} elseif( $external != 'disable' ) {

				// Hover | Icons
				$link_after .= '<div class="image_links '. $image_links_class .'">';
				if( ! in_array( $external, array('_self','_blank') ) ) $link_after .= '<a href="'. $large_image_url[0] .'" class="zoom" rel="prettyphoto"><i class="icon-search"></i></a>';
				if( $image_links ) $link_after .= '<a target="_blank" href="'. $image_links .'" class="external"><i class="icon-forward"></i></a>';
				$link_after .= '<a href="'. get_permalink() .'" class="link"><i class="icon-link"></i></a>';
				$link_after .= '</div>';

			}

		} else {

			// Blog -------------------------------------------

			$link_before = '<a href="'. get_permalink() .'">';
			$link_before .= '<div class="mask"></div>';

			$link_after = '</a>';
			$link_after .= '<div class="image_links double">';
			$link_after .= '<a href="'. $large_image_url[0] .'" class="zoom" rel="prettyphoto"><i class="icon-search"></i></a>';
			$link_after .= '<a href="'. get_permalink() .'" class="link"><i class="icon-link"></i></a>';
			$link_after .= '</div>';

		}



		// Post Format --------------------------------------------------------

		$post_format = mfn_post_format( $postID );

		// Images Only

		if( $images_only ){
			if( ! in_array( $post_format, array( 'quote', 'link', 'image' ) ) ){
				$post_format = 'images-only';
			}
		}

		switch( $post_format ){

			case 'quote':
			case 'link':

				// quote - Quote - without image

				return false;
				break;

			case 'image':

				// image - Vertical Image

				if( has_post_thumbnail() ){
					$output .= $link_before;
					$output .= get_the_post_thumbnail( $postID, $sizeV, array( 'class'=>'scale-with-grid' ) );
					$output .= $link_after;
				}
				break;

			case 'video':

				// video - Video

				if( $video = get_post_meta( $postID, 'mfn-post-video', true ) ){
					if( is_numeric($video) ){
						// Vimeo
						$output .= '<iframe class="scale-with-grid" src="http'. mfn_ssl() .'://player.vimeo.com/video/'. $video .'" allowFullScreen></iframe>'."\n";
					} else {
						// YouTube
						$output .= '<iframe class="scale-with-grid" src="http'. mfn_ssl() .'://www.youtube.com/embed/'. $video .'?wmode=opaque&rel=0" allowfullscreen></iframe>'."\n";
					}
				} elseif( get_post_meta( $postID, 'mfn-post-video-mp4', true ) ){
					$output .= mfn_jplayer( $postID );
				}
				break;

			case 'images-only':

				// Images Only

				$output .= $link_before;
				$output .= get_the_post_thumbnail( $postID, $sizeH, array( 'class' => 'scale-with-grid' ) );
				$output .= $link_after;
				break;

			default:

				// standard - Text, Horizontal Image, Slider

				$rev_slider = get_post_meta( $postID, 'mfn-post-slider', true );
				$lay_slider = get_post_meta( $postID, 'mfn-post-slider-layer', true );

				if( $type != 'portfolio' && ( $rev_slider || $lay_slider ) ){

					if( $rev_slider ){
						// Revolution Slider
						$output .= do_shortcode('[rev_slider '. $rev_slider .']');

					} elseif( $lay_slider ){
						// Layer Slider
						$output .= do_shortcode('[layerslider id="'. $lay_slider .'"]');
					}

				} elseif( has_post_thumbnail() ){

					// Image
					$output .= $link_before;
					$output .= get_the_post_thumbnail( $postID, $sizeH, array( 'class'=>'scale-with-grid' ) );
					$output .= $link_after;

				}
		}

		return $output;
	}
}