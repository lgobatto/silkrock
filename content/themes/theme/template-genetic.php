<?php
/**
 * Template Name: Genetic Page
 * The template for displaying genetic pages.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();
?>
    <!-- #Content -->
    <div id="Content">
    <div class="content_wrapper clearfix">
    <!-- .sections_group -->
    <div class="sections_group">
        <div class="entry-content" itemprop="mainContentOfPage">
			<?php
			while ( have_posts() ) {
				the_post();                            // Post Loop
				mfn_builder_print( get_the_ID() );    // Content Builder & WordPress Editor Content
			}
			?>
			<?php

			$genetic_results = new WP_Query( [
				'post_type'      => 'genetic_results',
				'posts_per_page' => - 1,
				'orderby'        => 'post_title',
				'order'          => 'ASC',
				'fields'         => 'ids'
			] );
			$classes         = [ 'secondary', 'warning', 'alert', 'primary', 'dark', 'storm' ];
			$species         = $males = $females = [];
			foreach ( $genetic_results->posts as $id ) {
				$geneticResult   = new \SilkRock\GeneticResult( $id );
				$specieSanitized = sanitize_title( $geneticResult->specie->name );
				$maleSanitized   = $specieSanitized . '-male-' . sanitize_title( $geneticResult->male->name );
				$femaleSanitized = $specieSanitized . '-female-' . sanitize_title( $geneticResult->female->name );

				if ( ! array_key_exists( $specieSanitized, $species ) ) {
					$species[ $specieSanitized ] = [
						'classes' => [ $specieSanitized ],
						'filter'  => [ $specieSanitized ],
						'name'    => $geneticResult->specie->name
					];
				}

				if ( ! array_key_exists( $maleSanitized, $males ) ) {
					$males[ $maleSanitized ] = [
						'name'    => $geneticResult->male->name,
						'classes' => [ $specieSanitized, $maleSanitized ],
						'filter'  => [ $specieSanitized, $maleSanitized ]
					];
				}

				if ( ! in_array( $femaleSanitized, $males[ $maleSanitized ]['classes'] ) ) {
					$males[ $maleSanitized ]['classes'][] = $femaleSanitized;
				}

				if ( ! array_key_exists( $femaleSanitized, $females ) ) {
					$females[ $femaleSanitized ] = [
						'name'    => $geneticResult->female->name,
						'classes' => [ $femaleSanitized, $specieSanitized ],
						'filter'  => [ $femaleSanitized, $specieSanitized ]
					];
				}
				if ( ! in_array( $maleSanitized, $females[ $femaleSanitized ]['classes'] ) ) {
					$females[ $femaleSanitized ]['classes'][] = $maleSanitized;
				}
				ksort( $species );
				ksort( $males );
				ksort( $females );
			}
			?>
            <div class="spacer-70"></div>
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Resultados de Acasalamentos</h1>
                    <h5 class="text-center fs-17"><span>Diagrama de criação para mutações de psitacídeos com expectativas em porcentagens.</span></h5>
                </div>
            </div>
            <div class="spacer-30"></div>
            <div class="row collapse medium-unstack">
                <div class="columns">
                    <button class="button warning expanded" role="button">Filtros</button>
                </div>
                <div class="columns">
                    <select class="filters" id="species-filter" data-placeholder="Espécie" data-minimumResultsForSearch="-1" data-theme="foundation">
                        <option data-filter="*"></option>
						<?php
						$count = 0;
						foreach ( $species as $key => $value ) {
							if ( $count >= count( $classes ) ) {
								$count = 0;
							}
							printf( '<option class="%s %s" value=".%s"  style="width: 100%%;">%s</option>', $classes[ $count ], join( ' ', $value['classes'] ), join( '.', $value['filter'] ), $value['name'] );
							$count ++;
						}
						?>
                    </select>
                    <div class="spacer-10"></div>
                </div>
                <div class="columns">
                    <select class="filters" id="males-filter" data-placeholder="Machos" data-minimumResultsForSearch="-1" data-theme="foundation">
                        <option data-filter="*"></option>
						<?php
						$count = 0;
						foreach ( $males as $key => $value ) {
							if ( $count >= count( $classes ) ) {
								$count = 0;
							}
							printf( '<option class="%s %s" value=".%s"  style="width: 100%%;">%s</option>', $classes[ $count ], join( ' ', $value['classes'] ), join( '.', $value['filter'] ), $value['name'] );
							$count ++;
						}
						?>
                    </select>
                    <div class="spacer-10"></div>
                </div>
                <div class="columns">
                    <select class="filters" id="females-filter" data-placeholder="Fêmeas" data-minimumResultsForSearch="-1" data-theme="foundation">
                        <option data-filter="*"></option>
						<?php
						$count = 0;
						foreach ( $females as $key => $value ) {
							if ( $count >= count( $classes ) ) {
								$count = 0;
							}
							printf( '<option class="%s %s" value=".%s"  style="width: 100%%;">%s</option>', $classes[ $count ], join( ' ', $value['classes'] ), join( '.', $value['filter'] ), $value['name'] );
							$count ++;
						}
						?>
                    </select>
                    <div class="spacer-10"></div>
                </div>
                <div class="columns">
                    <button class="button storm expanded" role="button" id="clear-filters"><i class="fa fa-fw fa-times"></i>Limpar filtros</button>
                </div>
            </div>
            <div class="row">
                <div class="column small-12 medium-3">
                    <h3 class="themecolor-red">Legenda</h3>
                    <p class="fs-14">
                        <span class="themecolor-light"><i class="fa fa-fw fa-mars"></i> Macho</span><br>
                        <span class="themecolor-pink"><i class="fa fa-fw fa-venus"></i> Fêmea</span><br>
                        <span class="themecolor-dark"><i class="fa fa-fw fa-venus-mars"></i> Mesmo resultado para Macho X Fêmea ou Fêmea X Macho.</span><br>
                        <span class="themecolor-medium">/ - Portador do gen.</span><br>
                    </p>
                </div>
                <div class="column small-12 medium-9">
					<?php ob_start(); ?>
                    <div class="row small-up-1 medium-up-3 large-up-4 genetic-results">
						<?php
						foreach ( $genetic_results->posts as $id ) {
							$geneticResult = new \SilkRock\GeneticResult( $id );
							$maleSymbol    = $femaleSymbol = 'fa-venus-mars';
							$maleColor     = $femaleColor = 'themecolor-neutral';
							$classes       = [
								sanitize_title( $geneticResult->specie->name ),
								sanitize_title( $geneticResult->specie->name ) . '-male-' . sanitize_title( $geneticResult->male->name ),
								sanitize_title( $geneticResult->specie->name ) . '-female-' . sanitize_title( $geneticResult->female->name )
							];
							if ( ! $geneticResult->canInvert() ) {
								$maleColor    = 'themecolor-light';
								$maleSymbol   = 'fa-mars';
								$femaleColor  = 'themecolor-pink';
								$femaleSymbol = 'fa-venus';
							} else {
								$classes[] = sanitize_title( $geneticResult->specie->name ) . '-male-' . sanitize_title( $geneticResult->female->name );
								$classes[] = sanitize_title( $geneticResult->specie->name ) . '-female-' . sanitize_title( $geneticResult->male->name );
							}
							?>
                            <div class="column genetic-result <?php echo join( ' ', $classes ); ?>">
                                <a href="<?php echo $geneticResult->file->getFilename(); ?>" class="card" data-fancybox>
                                    <div class="card-section">
                                        <h5 class="fs-16 themecolor-dark text-center"><?php echo $geneticResult->specie->name; ?></h5>
                                        <hr>
                                        <p class="fs-14 text-center">
                                        <span class="<?php echo $maleColor ?>">
                                            <i class="fa fa-fw <?php echo $maleSymbol ?>"></i>
	                                        <?php echo $geneticResult->male->getDisplayName(); ?>
                                        </span>
                                            <br>
                                            <i class="fa fa-fw fa-times themecolor-yellow"></i>
                                            <br>
                                            <span class="<?php echo $femaleColor ?>">
                                            <i class="fa fa-fw <?php echo $femaleSymbol ?>"></i>
												<?php echo $geneticResult->female->getDisplayName(); ?>
                                        </span>
                                        </p>
                                    </div>
                                </a>
                            </div>
							<?php
						}
						?>
                    </div>
					<?php
					$content = ob_get_clean();
					echo do_shortcode( '[emaillocker id="2003"]' . $content . '[/emaillocker]' );
					?>
                </div>
            </div>
            <div class="spacer-70"></div>
        </div>
    </div>
<?php get_footer();

// Omit Closing PHP Tags