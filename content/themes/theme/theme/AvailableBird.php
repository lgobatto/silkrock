<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 04/10/17
 * Time: 14:42
 */

namespace SilkRock;


use Imagecow\Image;

class AvailableBird {
	/**
	 * @var $ID int
	 */
	public $ID;
	/**
	 * @var \WP_Post
	 */
	private $post;
	/**
	 * @var string
	 */
	public $title;
	/**
	 * @var []
	 */
	public $birds;
	/**
	 * @var BirdSpecie
	 */
	public $specie;

	public $price;
	public $mutation;

	/**
	 * AvailableBird constructor.
	 *
	 * @param int $id
	 */
	public function __construct( int $id ) {
		$this->ID    = $id;
		$this->post  = get_post( $id );
		$this->title = $this->post->post_title;
		$this->setPrice();
		$this->setSpecie();
		$this->setBirds();
	}

	/**
	 * @return AvailableBird
	 * @internal param BirdSpecie $specie
	 *
	 */
	private function setSpecie(): AvailableBird {
		$specie       = get_field( '_available_bird_specie', $this->ID );
		$specie       = new BirdSpecie( $specie->ID );
		$this->specie = $specie;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAvailable() {
		$available = get_field( '_available_bird_sold', $this->ID );

		return $available ? true : false;
	}

	public function getThumb( $gallery ) {
		if ( $gallery ) {
			$thumb = reset( $gallery );
			$image = Image::fromFile( get_attached_file( key( $thumb ) ), Image::LIB_IMAGICK );
			$image->resizeCrop( 500, 500, Image::CROP_ENTROPY );

			return $image->base64();
		} else {
			if ( has_post_thumbnail( $this->specie->ID ) ) {
				$thumb = get_post_thumbnail_id( $this->specie->ID );
				$image = Image::fromFile( get_attached_file( $thumb ), Image::LIB_IMAGICK );
				$image->resizeCrop( 500, 500, Image::CROP_ENTROPY );

				return $image->base64();
			} else {
				return false;
			}
		}
	}

	/**
	 * @return AvailableBird
	 * @internal param [] $birds
	 *
	 */
	private function setBirds(): AvailableBird {
		$birds      = get_field( '_available_bird_bird', $this->ID );
		$gender     = get_field( '_available_bird_type', $this->ID );
		$collection = [];
		$count      = 0;
		if ( $birds ) {
			foreach ( $birds as $bird ) {
				$gallery = get_field( 'mutation_gallery', $bird['_available_bird_mutation']->ID );
				if ( $gallery ) {
					$gallery = array_map( 'Silkrock\BirdGender::mapGallery', $gallery );
				}
				$g            = ( $gender == "Casal" ) ? ( ( $count == 1 ) ? "F" : "M" ) : $gender[0];
				$collection[] = [
					'name'      => $this->setBirdName( $bird['_available_bird_mutation'], $bird['_available_bird_genotypes'], $g ),
					'gender'    => $g,
					'birthdate' => $bird['_available_bird_birthdate'],
					'id'        => $bird['_available_bird_identification'],
					'thumbnail' => $this->getThumb( $gallery )
				];
				$count ++;
			}
		}
		$this->birds = $collection;

		return $this;
	}

	private function setBirdName( $mutation, $genotypes, $gender ) {
		$mutation = get_field( 'mutation_name', $mutation->ID );
		$mutation = $mutation ? $mutation : "Comum";
		$title    = ( $gender == 'M' ) ? '<span class="themecolor-light">' : '<span class="themecolor-pink">';
		$title    .= $this->specie->name;
		$title    .= '<br><small>Mutação: ' . $mutation;
		if ( $genotypes && is_array( $genotypes ) ) {
			$genotypes = array_map( 'SilkRock\BirdGender::map_genotypes', $genotypes );
			$title     .= '/' . join( '/', $genotypes );
		}
		if ( $genotypes ) {
			$title .= "/" . $genotypes;
		}
		$title .= '</small></span>';

		return $title;
	}

	/**
	 * @return AvailableBird
	 * @internal param mixed|null|void $price
	 *
	 */
	public function setPrice() {
		$price = get_field( '_available_bird_price', $this->ID );
		if ( $price ) {
			$price = sprintf(
				'R$ %s',
				number_format( floatval( $price ), 2, ',', '.' )
			);
		}
		$this->price = $price;

		return $this;
	}
}