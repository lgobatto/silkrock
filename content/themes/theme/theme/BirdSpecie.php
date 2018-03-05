<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 03/10/17
 * Time: 11:34
 */

namespace SilkRock;


use Imagecow\Image;

class BirdSpecie {
	/**
	 * @var int
	 */
	public $ID;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var array|null|\WP_Post
	 */
	public $post;
	/**
	 * @var string|bool
	 */
	public $scientific_name;
	/**
	 * @var int|null|void
	 */
	public $pet_potential;
	/**
	 * @var int
	 */
	public $size;
	/**
	 * @var int
	 */
	public $weight;
	/**
	 * @var int
	 */
	public $lifespan;
	/**
	 * @var string
	 */
	public $diet;
	/**
	 * @var string
	 */
	public $habitat;
	/**
	 * @var string
	 */
	public $description;
	/**
	 * @var string
	 */
	public $video;
	/**
	 * @var []
	 */
	public $gallery;

	/**
	 * BirdSpecie constructor.
	 *
	 * @param $ID
	 */
	public function __construct( $ID ) {
		$this->ID              = $ID;
		$this->post            = get_post( $ID );
		$this->name            = $this->post->post_title;
		$this->scientific_name = get_field( 'bird_scientific_name', $ID );
		$this->pet_potential   = get_field( 'bird_pet_potential', $ID );
		$this->size            = get_field( 'bird_size', $ID ) . 'cm';
		$this->weight          = get_field( 'bird_weight', $ID ) . 'g';
		$this->lifespan        = get_field( 'bird_life_span', $ID ) . ' anos';
		$this->diet            = get_field( 'bird_diet', $ID );
		$this->habitat         = apply_filters( 'the_content', get_field( 'bird_origin', $ID ) );
		$this->description     = apply_filters( 'the_content', get_field( 'bird_description', $ID ) );
		$this->video           = get_field( 'bird_video', $ID );
		$this->setGallery();
	}

	public function getThumbnail( $echo = false ) {
		$thumb = '';
		if ( $this->video ) {
			$thumb = $this->video;
		} elseif ( has_post_thumbnail( $this->post ) ) {
			$thumb = get_the_post_thumbnail_url( $this->post, [ 500, 500 ] );
			$thumb = sprintf( '<img src="%s">', $thumb );
		}
		if ( $echo ) {
			echo $thumb;
		}

		return $thumb;
	}

	/**
	 * @return BirdSpecie
	 * @internal param mixed $gallery
	 *
	 */
	private function setGallery() : BirdSpecie{
		$gallery = get_field( 'bird_photo_gallery', $this->ID );
		if ( $gallery ) {
			$this->gallery = array_map( 'self::mapGallery', $gallery );
		} else {
			$this->gallery = false;
		}

		return $this;
	}

	private function mapGallery( $gallery ) {
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