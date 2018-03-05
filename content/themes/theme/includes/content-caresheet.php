<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// prev & next post -------------------
$single_post_nav = array(
	'hide-header'  => true,
	'hide-sticky'  => true,
	'in-same-term' => false,
);

$opts_single_post_nav = mfn_opts_get( 'prev-next-nav' );
if ( is_array( $opts_single_post_nav ) ) {

	if ( isset( $opts_single_post_nav['hide-header'] ) ) {
		$single_post_nav['hide-header'] = true;
	}
	if ( isset( $opts_single_post_nav['hide-sticky'] ) ) {
		$single_post_nav['hide-sticky'] = true;
	}
	if ( isset( $opts_single_post_nav['in-same-term'] ) ) {
		$single_post_nav['in-same-term'] = true;
	}

}

$post_prev    = get_adjacent_post( $single_post_nav['in-same-term'], '', true );
$post_next    = get_adjacent_post( $single_post_nav['in-same-term'], '', false );
$blog_page_id = get_option( 'page_for_posts' );


// post classes -----------------------
$classes = array();
if ( ! mfn_post_thumbnail( get_the_ID() ) ) {
	$classes[] = 'no-img';
}
if ( true ) {
	$classes[] = 'no-img';
}
if ( post_password_required() ) {
	$classes[] = 'no-img';
}
if ( ! mfn_opts_get( 'blog-title' ) ) {
	$classes[] = 'no-title';
}

if ( mfn_opts_get( 'share' ) == 'hide-mobile' ) {
	$classes[] = 'no-share-mobile';
} elseif ( ! mfn_opts_get( 'share' ) ) {
	$classes[] = 'no-share';
}


$translate['published']  = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-published', 'Published by' ) : __( 'Published by', 'betheme' );
$translate['at']         = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-at', 'at' ) : __( 'at', 'betheme' );
$translate['tags']       = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-tags', 'Tags' ) : __( 'Tags', 'betheme' );
$translate['categories'] = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-categories', 'Categories' ) : __( 'Categories', 'betheme' );
$translate['all']        = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-all', 'Show all' ) : __( 'Show all', 'betheme' );
$translate['related']    = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-related', 'Related posts' ) : __( 'Related posts', 'betheme' );
$translate['readmore']   = mfn_opts_get( 'translate' ) ? mfn_opts_get( 'translate-readmore', 'Read more' ) : __( 'Read more', 'betheme' );
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>


    <div class="post-wrapper-content">
		<?php
		$column_sizes  = [
			'medium-6'  => 'medium-6',
			'medium-8'  => 'medium-4',
			'medium-9'  => 'medium-3',
			'medium-12' => false
		];
		$colors        = [
			[ '#f6f6f6', '#ffffff' ],
			[ '#ffffff', '#f6f6f6' ]
		];
		$title_classes = [
			'themecolor-dark',
			'themecolor-red'
		];
		$rows          = get_field( 'casheet_row' );
		if ( have_rows( 'casheet_row' ) ) {
			$counter = 1;
			while ( have_rows( 'casheet_row' ) ) {
				the_row();
				$title       = get_sub_field( 'title' );
				$image       = get_sub_field( 'image' );
				$width       = get_sub_field( 'width' );
				$video       = get_sub_field( 'video' );
				$description = get_sub_field( 'description' );
				$odd         = $counter % 2;
				$color       = $counter == count( $rows ) ? '#FCFCFC' : $colors[ $odd ][0];
				?>
                <div style="background-color: <?php echo $colors[ $odd ][1]; ?>">
                    <div class="spacer-70"></div>
                    <h2 class="fs-34 text-center <?php echo $title_classes[ $odd ]; ?>"><?php echo $title; ?></h2>
                    <div class="spacer-30"></div>
                    <div class="row">
						<?php
						if ( $odd ) {
							?>
                            <div class="column small-12 <?php echo $column_sizes[ $width ]; ?> medium-text-right">
								<?php
								if ( $video ) {
									printf ('<div class="responsive-embed widescreen">%s</div>', $video);
								} else {
									printf( '<a href="%s" class="thumbnail" data-fancybox><img src="%s"></a>',
										wp_get_attachment_image_url( $image['ID'], 'full' ),
										wp_get_attachment_image_url( $image['ID'], [ 700 ] )
									);
								}
								?>
                            </div>
                            <div class="column small-12 <?php echo $width; ?> medium-text-left">
								<?php echo apply_filters( 'the_content', $description ); ?>
                            </div>
							<?php
						} else {
							?>
                            <div class="column small-12 <?php echo $width; ?> medium-text-right">
	                            <?php echo apply_filters( 'the_content', $description ); ?>
                            </div>
                            <div class="column small-12 <?php echo $column_sizes[ $width ]; ?> medium-text-left">
	                            <?php
	                            if ( $video ) {
		                            printf ('<div class="responsive-embed widescreen">%s</div>', $video);
	                            } else {
		                            printf( '<a href="%s" class="thumbnail" data-fancybox><img src="%s"></a>',
			                            wp_get_attachment_image_url( $image['ID'], 'full' ),
			                            wp_get_attachment_image_url( $image['ID'], [ 700 ] )
		                            );
	                            }
	                            ?>
                            </div>
							<?php
						}
						?>
                    </div>
                    <div class="spacer-70"></div>
					<?php separator_row( $color ); ?>
                </div>
				<?php
				$counter ++;
			}
		}
		?>
    </div>


    <div class="section section-post-related">
        <div class="section_wrapper clearfix">

			<?php
			if ( mfn_opts_get( 'blog-related' ) && true ) {

				$related_count = intval( mfn_opts_get( 'blog-related' ) );
				$related_cols  = 'col-' . absint( mfn_opts_get( 'blog-related-columns', 3 ) );
				$related_style = mfn_opts_get( 'related-style' );

				$args = array(
					'post_type'           => 'caresheet',
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
					'post__not_in'        => array( get_the_ID() ),
					'posts_per_page'      => $related_count,
					'post_status'         => 'publish',
					'meta_query'          => [
						[
							[
								'key'   => 'bird_specie',
								'value' => get_field('bird_specie')->ID
							]
						]
					]
				);

				$query_related_posts = new WP_Query( $args );
				if ( $query_related_posts->have_posts() ) {

					echo '<div class="section-related-adjustment ' . $related_style . '">';

					echo '<div class="spacer-20"></div> <h3 class="fs-60 text-center">' . $translate['related'] . '</h3><div class="spacer-20"></div>';

					echo '<div class="section-related-ul ' . $related_cols . '">';

					while ( $query_related_posts->have_posts() ) {
						$query_related_posts->the_post();
						$specie        = get_field( 'bird_specie' );
						$related_class = '';
						if ( ! mfn_post_thumbnail( get_the_ID() ) ) {
							$related_class .= 'no-img';
						}

						$post_format = mfn_post_thumbnail_type( get_the_ID() );
						if ( mfn_opts_get( 'blog-related-images' ) ) {
							$post_format = mfn_opts_get( 'blog-related-images' );
						}

						echo '<div class="column post-related ' . implode( ' ', get_post_class( $related_class ) ) . '">';

						if ( get_post_format() == 'quote' ) {

							echo '<blockquote>';
							echo '<a href="' . get_permalink() . '">';
							the_title();
							echo '</a>';
							echo '</blockquote>';

						} else {

							echo '<div class="single-photo-wrapper ' . $post_format . '">';
							echo '<div class="image_frame scale-with-grid">';

							echo '<div class="image_wrapper">';
							echo mfn_post_thumbnail( get_the_ID(), 'related', false, mfn_opts_get( 'blog-related-images' ) );
							echo '</div>';

							if ( has_post_thumbnail() && $caption = get_post( get_post_thumbnail_id() )->post_excerpt ) {
								echo '<p class="wp-caption-text ' . mfn_opts_get( 'featured-image-caption' ) . '">' . $caption . '</p>';
							}

							echo '</div>';
							echo '</div>';

						}

						echo '<div class="date_label">' . get_the_date() . '</div>';

						echo '<div class="desc">';
						if ( get_post_format() != 'quote' ) {
							echo '<h4><a href="' . get_permalink() . '">' . $specie->post_title . ' - ' . get_the_title() . '</a></h4>';
						}
						echo '<hr class="hr_color" />';
						echo '<a href="' . get_permalink() . '" class="button button_left button_js"><span class="button_icon"><i class="icon-layout"></i></span><span class="button_label">' . $translate['readmore'] . '</span></a>';
						echo '</div>';

						echo '</div>';
					}

					echo '</div>';

					echo '</div>';
				}
				wp_reset_postdata();
			}
			?>

        </div>
    </div>

</div>