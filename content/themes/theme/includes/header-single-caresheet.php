<?php
// class
$intro_class = "dark full-screen parallax";
$parallax    = true;
$intro_style = "background-size:cover;";

// style
if ( $bg_color = get_post_meta( get_the_ID(), 'mfn-post-bg', true ) ) {
	$intro_style .= 'background-color:' . esc_attr( $bg_color ) . ';';
}
if ( $bg_image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ) ) {
	$intro_style .= 'background-image:url(' . esc_url( $bg_image ) . ');';
} else {
	$parallax = false;
}

// padding
if ( $intro_padding = mfn_opts_get( 'single-intro-padding' ) ) {
	$intro_padding = 'style="padding:' . esc_attr( $intro_padding ) . ';"';
}

// parallax
if ( $parallax ) {
	$parallax = mfn_parallax_data();

	if ( mfn_parallax_plugin() == 'translate3d' ) {
		if ( wp_is_mobile() ) {
			$intro_style .= 'background-attachment:scroll;background-size:cover;-webkit-background-size:cover;';
		} else {
			$intro_style = false;
		}
	} else {
		$intro_style .= 'background-repeat:no-repeat;background-attachment:fixed;background-size:cover;-webkit-background-size:cover;';
	}
}

// style - prepare
if ( $intro_style ) {
	$intro_style = 'style="' . $intro_style . '"';
}

// IMPORTANT for post meta
while ( have_posts() ) {
	the_post();
}
wp_reset_query();
?>

<div id="Intro" class="<?php echo $intro_class; ?>" <?php echo $intro_style; ?> <?php echo $parallax; ?>>

	<?php
	// parallax | translate3d -------
	if ( ! wp_is_mobile() && $parallax && mfn_parallax_plugin() == 'translate3d' ) {
		echo '<img class="mfn-parallax" src="' . esc_url( $bg_image ) . '" alt="' . __( 'parallax background', 'betheme' ) . '" style="opacity:0" />';
	}
	?>

    <div class="intro-inner" <?php echo $intro_padding; ?>>

		<?php
		$h = mfn_opts_get( 'title-heading', 1 );
		echo '<h' . $h . ' class="intro-title">' . get_field('bird_specie')->post_title. '<br>' . get_the_title() . '</h' . $h . '>';
		?>


    </div>

    <div class="intro-next"><i class="icon-down-open-big"></i></div>

</div>