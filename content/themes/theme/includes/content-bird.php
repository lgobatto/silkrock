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
	'hide-header'  => false,
	'hide-sticky'  => false,
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
if ( get_post_meta( get_the_ID(), 'mfn-post-hide-image', true ) ) {
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
$images                  = get_field( 'bird_photo_gallery' );
?>
<?php if ( $images ): ?>
    <div class="themebg">
        <div class="spacer-32"></div>
        <div id="rev_slider_10_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="birdscarousel" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
            <!-- START REVOLUTION SLIDER 5.4.5.2 fullwidth mode -->
            <div id="rev_slider_10_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.6">
                <ul>    <!-- SLIDE  -->
					<?php
					$counter = 0;
					if ( $images ) {
						foreach ( $images as $image ) {
							$title = $image['title'];
							$thumb = wp_get_attachment_image_src( $image['ID'], [ 100, 50 ] )[0];
							$file  = $image['url'];
							?>
                            <li data-index="rs-<?php echo $counter + 26; ?>" data-transition="grayscalecross" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-thumb="<?php echo $thumb; ?>" data-rotate="0" data-saveperformance="off" data-title="<?php echo $title ?>" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                                <!-- MAIN IMAGE -->
                                <img src="/content/themes/theme/dist/images/logo-white.png" alt="" title="<?php echo $title ?>" width="1920" height="1080" data-lazyload="<?php echo $file; ?>" data-bgposition="center center" data-bgfit="contain" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina>
                                <!-- LAYERS -->
                            </li>
							<?php
							$counter ++;
						}
					}
					?>
                </ul>
                <div class="tp-bannertimer" style="height: 5px; background: rgba(0,0,0,0.15);"></div>
            </div>
        </div>
        <div class="spacer-32"></div>
    </div>
<?php endif; ?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php
	// single post navigation | sticky
	if ( ! $single_post_nav['hide-sticky'] ) {
		echo mfn_post_navigation_sticky( $post_prev, 'prev', 'icon-left-open-big' );
		echo mfn_post_navigation_sticky( $post_next, 'next', 'icon-right-open-big' );
	}
	?>
    <div class="themebg-white">
        <div class="spacer-70"></div>
        <div class="row align-middle">
			<?php
			/**
			 * @var $group WP_Term
			 */
			$group           = get_field( 'bird_group' );
			$scientific_name = get_field( 'bird_scientific_name' );
			$pet_potential   = get_field( 'bird_pet_potential' );
			$size            = get_field( 'bird_size' );
			$weight          = get_field( 'bird_weight' );
			$lifespan        = get_field( 'bird_life_span' );
			$diet            = get_field( 'bird_diet' );
			$habitat         = get_field( 'bird_origin' );
			$description     = get_field( 'bird_description' );
			$video           = get_field( 'bird_video' );
			?>
            <div class="small-12 medium-5 columns">
				<?php
				if ( ! $video ) {
					the_post_thumbnail( 'full', [ 'class' => 'thumbnail' ] );
				} else {
					echo $video;
				}
				?>
            </div>
            <div class="small-12 medium-7 columns">
                <h2 class="fs-60"><?php the_title(); ?></h2>
                <div class="spacer-20"></div>
				<?php if ( $scientific_name ): ?>
                    <h3>Nome Científico:
                        <small><?php echo $scientific_name; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $group ): $group = get_term( $group ); ?>
                    <h3>Grupo:
                        <small><?php echo $group->name; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $pet_potential && false ): ?>
                    <h3>Potencial Pet:
                        <span class="pet-potential themecolor-yellow" data-amount="<?php echo $pet_potential / 20; ?>"></span>
                    </h3>
				<?php endif; ?>
				<?php if ( $size ): ?>
                    <h3>Tamanho Adulto:
                        <small><?php echo $size; ?>cm</small>
                    </h3>
				<?php endif; ?>
				<?php if ( $weight ): ?>
                    <h3>Peso Adulto:
                        <small><?php echo $weight; ?>g</small>
                    </h3>
				<?php endif; ?>
				<?php if ( $lifespan ): ?>
                    <h3>Expectativa de vida:
                        <small><?php echo $lifespan; ?> anos</small>
                    </h3>
				<?php endif; ?>
				<?php if ( $diet ): ?>
                    <h3>Dieta:
                        <small><?php echo $diet; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $habitat ): ?>
                    <h3>Origem:
                        <small><?php echo $habitat; ?></small>
                    </h3>
				<?php endif; ?>
                <h2 class="fs-50">Introdução à espécie</h2>
				<?php echo apply_filters( 'the_content', $description ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php if ( \SilkRock\Caresheet::has_sheets( get_the_ID() ) ): ?>
			<?php separator_row( '#ffffff' ); ?>
		<?php else: ?>
			<?php separator_row( '#61A6D6' ); ?>
		<?php endif; ?>
    </div>
    <div style="background-color: #f6f6f6; display: none;">
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center">Introdução à espécie</h2>
        <div class="spacer-50"></div>
        <div class="row">
            <div class="small-12 columns">
				<?php echo apply_filters( 'the_content', $description ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php if ( \SilkRock\Caresheet::has_sheets( get_the_ID() ) ): ?>
			<?php separator_row( '#ffffff' ); ?>
		<?php else: ?>
			<?php separator_row( '#61A6D6' ); ?>
		<?php endif; ?>
    </div>
	<?php if ( \SilkRock\Caresheet::has_sheets( get_the_ID() ) ): ?>
        <div class="themebg-white">
            <div class="spacer-70"></div>
            <h2 class="fs-60 text-center themecolor-light">Artigos sobre a espécie</h2>
            <div class="spacer-50"></div>
			<?php
			echo do_shortcode( '[caresheets specie="' . get_the_ID() . '"]' );
			?>
            <div class="spacer-70"></div>
			<?php separator_row( '#61A6D6' ); ?>
        </div>
	<?php endif; ?>
    <div class="themebg-light">
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-white">Mutações da espécie</h2>
        <div class="spacer-50"></div>
		<?php
		$related = new WP_Query( [
			'post_type'      => 'bird-mutations',
			'posts_per_page' => - 1,
			'meta_query'     => [
				[
					'key'   => 'bird_specie',
					'value' => get_the_ID()
				]
			]
		] );
		$total   = 0;
		foreach ( $related->posts as $p ) {
			if ( get_field( 'mutation_gallery', $p->ID ) ) {
				$total ++;
			}
		}
		if ( $related->have_posts() && $total ) {
			print( '<div class="row small-up-1 medium-up-3 align-center"  data-equalizer data-equalize-by-row="true" data-equalize-on="medium">' );
			while ( $related->have_posts() ) {
				$related->the_post();
				$image   = get_post_thumbnail_id();
				$title   = get_the_title();
				$gallery = get_field( 'mutation_gallery' );
				if ( ! $gallery ) {
					continue;
				}
				$sc = sprintf( '<a href="%s" data-fancybox="%s">[mpc_flipbox transition_duration="500" back_background_color="#ffffff"][mpc_flipbox_side title="Front"][mpc_image image="%s"][/mpc_flipbox_side][mpc_flipbox_side title="Back"][vc_column_text]%s[/vc_column_text][/mpc_flipbox_side][/mpc_flipbox]</a>', $gallery[0]['url'], sanitize_title( $title ), $image, sprintf( '<h3 class="text-center">%s</h3><p>%s</p><small>clique para abrir a galeria de fotos.</small>', get_field( 'mutation_name' ), get_field( 'mutation_description' ) ) );
				echo '<div class="column" data-equalizer-watch>';
				echo do_shortcode( $sc );
				for ( $i = 1; $i < count( $gallery ); $i ++ ) {
					printf( '<a href="%s" data-fancybox="%s" class="hide"></a>', $gallery[ $i ]['url'], sanitize_title( $title ) );
				}
				echo '<div class="spacer-20"></div></div>';
			}
			print( '</div>' );
		} else {
			?>
            <div class="row">
                <div class="small-12 columns">
                    <h2 class="text-center themecolor-white">Em Breve.</h2>
                </div>
            </div>
			<?php
		}
		wp_reset_postdata();
		?>
        <div class="spacer-70"></div>
		<?php separator_row( '#fcfcfc' ); ?>
    </div>
    <div>
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-red">Filhotes Disponíveis</h2>
        <div class="spacer-50"></div>
		<?php
		echo do_shortcode( '[available_birds specie="' . get_the_ID() . '" limit="4"]' );
		?>
        <div class="spacer-70"></div>
    </div>
</div>