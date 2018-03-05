<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 29/09/17
 * Time: 20:50
 */
get_header();
?>
    <div class="themebg-light">
        <div class="spacer-70"></div>
        <div class="row">
            <div class="small-12 columns">
				<?php echo do_shortcode( '[birds_grid show_filter="true"]' ); ?>
            </div>
        </div>
        <div class="spacer-70"></div>
		<?php separator_row( '#fcfcfc' ); ?>
    </div>
    <div>
        <div class="spacer-70"></div>
        <h2 class="fs-60 text-center themecolor-red">Filhotes Disponíveis</h2>
        <div class="spacer-50"></div>
		<?php
		echo do_shortcode( '[available_birds limit="4" featured="1"]' );
		?>
        <div class="spacer-70"></div>
    </div>
<?php
get_footer();