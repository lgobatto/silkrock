<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 03/10/17
 * Time: 11:34
 */

namespace SilkRock;


use Imagecow\Image;

class BirdGender {
	/**
	 *
	 */
	const MALE = 'male';
	const FEMALE = 'female';
	/**
	 * @var int
	 */
	public $ID;

	/**
	 * @var \WP_Post
	 */
	private $post;
	/**
	 * @var BirdSpecie
	 */
	public $specie;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	private $admin_name;
	/**
	 * @var \WP_Term
	 */
	public $group;
	/**
	 * @var string
	 */
	public $description;
	/**
	 * @var int
	 */
	public $order;
	/**
	 * @var
	 */
	public $gallery;

	/**
	 * @var bool|string
	 */
	private $factor;
	/**
	 * @var bool|array
	 */
	private $genotype;

	/**
	 * BirdGender constructor.
	 *
	 * @param int $ID
	 * @param string $gender
	 * @param bool|string $factor
	 * @param bool $genotype
	 */
	public function __construct( $ID, $gender = self::MALE, $factor = false, $genotype = false ) {
		$this->ID = $ID;
		$this->setPost();
		$this->setSpecie();
		$this->setName();
		$this->setAdminName();
		$this->setFactor( $factor );
		$this->setGenotype( $genotype );
		$this->setGroup();
		$this->setDescription();
		$this->setGallery();
		$this->setOrder();
	}

	/**
	 * @return string
	 */
	public function getAdminName(): string {
		return $this->admin_name;
	}

	/**
	 * @return BirdGender
	 * @internal param string $admin_name
	 */
	private function setAdminName(): BirdGender {
		$this->admin_name = sprintf( '%s %s', $this->specie->name, $this->name );

		return $this;
	}

	/**-
	 * @return BirdGender
	 * @internal param string $name
	 *
	 */
	private function setName(): BirdGender {
		$this->name = get_field( 'mutation_name', $this->ID );

		return $this;
	}

	public function getDisplayName() {
		return sprintf(
			'%s%s%s',
			$this->name,
			( $this->factor ) ? ' ' . $this->factor : '',
			( $this->genotype ) ? '/' . join( '/', $this->genotype ) : ''
		);
	}

	/**
	 * @param bool|string $factor
	 *
	 * @return BirdGender
	 */
	private function setFactor( $factor ): BirdGender {
		switch ( $factor ) {
			case 'duplo':
				$factor = 'DF';
				break;
			case 'simples':
				$factor = 'SF';
				break;
			default:
				$factor = false;
				break;
		}
		$this->factor = $factor;

		return $this;
	}

	/**
	 * @param bool|array $genotype
	 *
	 * @return BirdGender
	 */
	private function setGenotype( $genotype ): BirdGender {
		if ( ! $genotype ) {
			$this->genotype = $genotype;
		} else {
			$this->genotype = array_map( 'self::map_genotypes', $genotype );
		}

		return $this;
	}

	/**
	 * @return BirdGender
	 * @internal param BirdSpecie $specie
	 *
	 */
	private function setSpecie(): BirdGender {
		/**
		 * @var $specie \WP_Post
		 */
		$specie       = get_field( 'bird_specie', $this->ID );
		$this->specie = new BirdSpecie( $specie->ID );

		return $this;
	}

	public static function map_genotypes( $genotype ) {
		return '' . $genotype['genotype'] . '';
	}

	public function getThumb() {
		if ( $this->gallery ) {
			$thumb = reset( $this->gallery );
			$image = Image::fromFile( get_attached_file( key( $thumb ) ), Image::LIB_IMAGICK );
			$image->resizeCrop( 500, 400, Image::CROP_ENTROPY );

			return $image->base64();
		} else {
			$thumb = get_post_thumbnail_id( $this->specie->ID );
			if ( $thumb ) {
				$image = Image::fromFile( get_attached_file( $thumb ), Image::LIB_IMAGICK );
				$image->resizeCrop( 500, 400, Image::CROP_ENTROPY );

				return $image->base64();
			} else {
				return false;
			}
		}
	}

	/**
	 * @return BirdGender
	 * @internal param int $order
	 *
	 */
	private function setOrder(): BirdGender {
		$this->order = $this->post->menu_order;

		return $this;
	}

	/**
	 * @return BirdGender
	 * @internal param int $post
	 *
	 */
	private function setPost(): BirdGender {
		$this->post = get_post( $this->ID );

		return $this;
	}

	/**
	 * @return BirdGender
	 * @internal param \WP_Term $group
	 *
	 */
	private function setGroup(): BirdGender {
		$group = get_field( 'mutation_group', $this->ID );
		if ( $group ) {
			$this->group = get_term( $group );
		}

		return $this;
	}

	/**
	 * @return BirdGender
	 * @internal param string $description
	 *
	 */
	private function setDescription(): BirdGender {
		$this->description = get_field( 'mutation_description', $this->ID );

		return $this;
	}

	/**
	 * @return BirdGender
	 * @internal param mixed $gallery
	 *
	 */
	private function setGallery(): BirdGender {
		$gallery = get_field( 'mutation_gallery', $this->ID );
		if ( $gallery ) {
			$this->gallery = array_map( 'self::mapGallery', $gallery );
		} else {
			$this->gallery = false;
		}

		return $this;
	}

	public static function mapGallery( $gallery ) {
		$thumbnail              = wp_get_attachment_image_url( $gallery['ID'], [ 500, 281 ] );
		$item[ $gallery['ID'] ] = [
			'url'         => $gallery['url'],
			'description' => $gallery['description'],
			'caption'     => $gallery['caption'],
			'width'       => $gallery['width'],
			'height'      => $gallery['height'],
			'thumbnail'   => $thumbnail
		];

		return $item;
	}


}