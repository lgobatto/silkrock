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
    <div id="rev_slider_9_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="birds" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
        <!-- START REVOLUTION SLIDER 5.4.5.2 fullwidth mode -->
        <div id="rev_slider_9_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.5.2">
            <ul>    <!-- SLIDE  -->
				<?php
				$counter = 0;
				if ( $images ) {
					foreach ( $images as $image ) {
						?>
                        <li data-index="rs-<?php echo $counter + 26; ?>" data-transition="slideoverhorizontal" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-thumb="<?php echo $image['url']; ?>" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                            <!-- MAIN IMAGE -->
                            <img src="<?php echo $image['url']; ?>" alt="" title="<?php echo $image['title']; ?>" width="1920" height="1275" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                            <!-- LAYERS -->
                        </li>
						<?php
						$counter ++;
					}
				}
				?>
            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>
    </div><!-- END REVOLUTION SLIDER -->
<?php endif; ?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php
	// single post navigation | sticky
	if ( ! $single_post_nav['hide-sticky'] ) {
		echo mfn_post_navigation_sticky( $post_prev, 'prev', 'icon-left-open-big' );
		echo mfn_post_navigation_sticky( $post_next, 'next', 'icon-right-open-big' );
	}

	$availableBird = new \SilkRock\AvailableBird( get_the_ID() );

	?>
    <div class="themebg-white">
        <div class="spacer-70"></div>
        <h2 class="text-center fs-60"><?php echo $availableBird->specie->name; ?></h2>
        <div class="spacer-20"></div>
        <div class="row">
            <div class="small-12 medium-6 columns">
                <div class="text-center">
					<?php
					if ( $availableBird->specie->gallery ) {
						$gallery = reset( $availableBird->specie->gallery );
						foreach ( $gallery as $image ) {
							printf( '<img class="thumbnail" src="%s">', $image['url'] );
						}
					} else {
						$availableBird->specie->getThumbnail( true );
					}
					?>
                </div>
            </div>
            <div class="small-12 medium-6 columns">
                <h2 class="themecolor-light">
                    Sobre a Espécie
                </h2>
				<?php if ( $availableBird->specie->scientific_name ): ?>
                    <h3>Nome Científico:
                        <small><?php echo $availableBird->specie->scientific_name; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->pet_potential ): ?>
                    <h3>Potencial Pet:
                        <span class="pet-potential themecolor-yellow" data-amount="<?php echo $availableBird->specie->pet_potential / 20; ?>"></span>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->size ): ?>
                    <h3>Tamanho Adulto:
                        <small><?php echo $availableBird->specie->size; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->weight ): ?>
                    <h3>Peso Adulto:
                        <small><?php echo $availableBird->specie->weight; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->lifespan ): ?>
                    <h3>Expectativa de vida:
                        <small><?php echo $availableBird->specie->lifespan; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->diet ): ?>
                    <h3>Dieta:
                        <small><?php echo $availableBird->specie->diet; ?></small>
                    </h3>
				<?php endif; ?>
				<?php if ( $availableBird->specie->habitat ): ?>
                    <h3>Origem:
                        <small><?php echo $availableBird->specie->habitat; ?></small>
                    </h3>
				<?php endif; ?>
                <div class="spacer-16"></div>
                <h3 class="themecolor-neutral">Preço nesta oferta:</h3>
                <h1 class="themecolor-light"><?php echo $availableBird->price; ?></h1>
            </div>
        </div>
        <div class="spacer-16"></div>
		<?php separator_row( '#61A6D6' ); ?>
    </div>
    <div class="themebg-light">
        <div class="spacer-30"></div>
        <div class="row align-center">
            <div class="small-12 columns">
                <h2 class="themecolor-white text-center fs-60">O que você está adquirindo</h2>
                <div class="spacer-30"></div>
            </div>
        </div>
        <div class="row align-center">
            <div class="columns small-12 medium-6">
				<?php
				if ( $availableBird->birds ) {
					$count = 0;
					foreach ( $availableBird->birds as $bird ) {
						if ( $count > 0 ) {
							printf( '<div class="row align-center"><div class="columns text-center"><i class="fa fa-fw fa-times fa-plus fa-3x themecolor-storm"></i><div class="spacer-16"></div></div></div>' );
						}
						?>
                        <div class="card">
                            <div class="card-section">
                                <div class="row align-top">
                                    <div class="columns small-12 medium-5">
										<?php if ( $bird['thumbnail'] ) {
											printf( '<img class="thumbnail" src="%s">', $bird['thumbnail'] );
										} ?>
                                    </div>
                                    <div class="columns small-12 medium-7 align-self-top">
                                        <p class="big"><?php echo $bird['name']; ?></p>
										<?php
										if ( $bird['gender'][0] == 'M' ) {
											printf( '<p class="themecolor-light"><i class="fa fa-fw fa-mars"></i> Macho</p>' );
										} else {
											printf( '<p class="themecolor-pink"><i class="fa fa-fw fa-mars"></i> Fêmea</p>' );
										}
										?>
										<?php if ( $bird['birthdate'] ) {
											printf( '<p class="">Nascimento: %s</p>', $bird['birthdate'] );
										} ?>
										<?php if ( $bird['id'] ) {
											printf( '<p class="">Anilha nº: %s</p>', $bird['id'] );
										} ?>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
						$count ++;
					}
				}
				?>
            </div>
            <div class="columns small-12 medium-6">
                <p class="big themecolor-white">
                    Entre em contato.
                </p>
                <p class="themecolor-white">
                    Para maiores informações sobre a aquisição desta ave/casal entre em contato pelos canais de atendimento ou através do formulário abaixo.
                </p>
                <div class="themecolor-white social-contact">
					<?php
					get_template_part( 'includes/include', 'social' );
					?>
                </div>
				<?php gravity_form( 3, false, false, false, null, true ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php if ( \SilkRock\Caresheet::has_sheets( $availableBird->specie->ID ) ):; ?>
			<?php separator_row( '#888888' ); ?>
		<?php else: ?>
			<?php separator_row( '#fcfcfc' ); ?>
		<?php endif; ?>
    </div>
	<?php if ( \SilkRock\Caresheet::has_sheets( $availableBird->specie->ID ) ):; ?>
        <div class="themebg-neutral">
            <div class="spacer-70"></div>
            <h2 class="fs-60 text-center themecolor-white">Artigos sobre a espécie</h2>
            <div class="spacer-50"></div>
			<?php
			echo do_shortcode( '[caresheets specie="' . $availableBird->specie->ID . '"]' );
			?>
            <div class="spacer-70"></div>
			<?php separator_row( '#fcfcfc' ); ?>
        </div>
	<?php endif; ?>
    <div>
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-red">Outros Filhotes</h2>
        <div class="spacer-50"></div>
		<?php
		echo do_shortcode( '[available_birds specie="' . $availableBird->specie->ID . '" limit="4"]' );
		?>
        <div class="spacer-70"></div>
    </div>
</div>