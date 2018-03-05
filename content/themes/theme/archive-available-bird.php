<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 29/09/17
 * Time: 20:50
 */
get_header();
?>
    <div style="background-color: #fcfcfc;">
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-light">Filhotes Disponíveis</h2>
        <div class="spacer-50"></div>
        <div class="row">
            <div class="small-12 columns">
				<?php echo do_shortcode( '[available_birds show_filter="1"]' ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php separator_row( '#61A6D6' ); ?>
    </div>
    <div class="themebg-light">
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-white">Nossas Espécies</h2>
        <div class="spacer-50"></div>
        <div class="row">
            <div class="small-12 columns">
				<?php echo do_shortcode( '[birds_grid]' ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php separator_row( '#fcfcfc' ); ?>
    </div>
    <div>
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-red">Artigos</h2>
        <div class="spacer-50"></div>
		<?php
		echo do_shortcode( '[caresheets]' );
		?>
        <div class="spacer-70"></div>
    </div>
<?php
get_footer();