<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 03/10/17
 * Time: 11:23
 */

namespace SilkRock;


class GeneticResult {
	/**
	 * @var int
	 */
	public $ID;
	/**
	 * @var string
	 */
	public $file;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var BirdSpecie
	 */
	public $specie;

	/**
	 * @var BirdGender
	 */
	public $male;
	/**
	 * @var BirdGender
	 */
	public $female;

	/**
	 * @var GeneticResultChild[]
	 */
	public $children;


	/**
	 * GeneticResult constructor.
	 *
	 * @param int $ID
	 */
	public function __construct( $ID ) {
		$this->ID = $ID;
		$this->setMale();
		$this->setFemale();
		$this->setSpecie();
		$this->setTitle();
		$this->setChildren();
		$this->file = new GeneticResultFile( $this );
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @return GeneticResult
	 * @internal param BirdSpecie $specie
	 *
	 */
	public function setSpecie(): GeneticResult {
		$this->specie = $this->male->specie;

		return $this;
	}

	/**
	 * @return GeneticResult
	 * @internal param BirdGender $male
	 *
	 */
	public function setMale(): GeneticResult {
		/**
		 * @var $male \WP_Post
		 */
		$male           = get_field( 'genetic_results_male', $this->ID );
		$male_factor    = get_field( 'genetic_results_male_factor', $this->ID );
		$male_genotypes = get_field( 'genetic_results_male_genotypes', $this->ID );
		if ( ! $male_factor ) {
			$male_factor = false;
		}
		$this->male = new BirdGender( $male->ID, BirdGender::MALE, $male_factor, $male_genotypes );

		return $this;
	}

	/**
	 * @return GeneticResult
	 * @internal param BirdGender $female
	 *
	 */
	public function setFemale(): GeneticResult {
		/**
		 * @var $male \WP_Post
		 */
		$female           = get_field( 'genetic_results_female', $this->ID );
		$female_factor    = get_field( 'genetic_results_female_factor', $this->ID );
		$female_genotypes = get_field( 'genetic_results_female_genotypes', $this->ID );
		if ( ! $female_factor ) {
			$female_factor = false;
		}
		$this->female = new BirdGender( $female->ID, BirdGender::FEMALE, $female_factor, $female_genotypes );

		return $this;
	}

	public function canInvert() {
		return get_field( 'genetic_results_inverted', $this->ID );
	}

	/**
	 * @return GeneticResult
	 * @internal param string $title
	 *
	 */
	public function setTitle(): GeneticResult {
		$this->title = $this->title = sprintf(
			'%s: %s X %s',
			$this->specie->name,
			$this->male->getDisplayName(),
			$this->female->getDisplayName()
		);

		return $this;
	}

	/**
	 * @return GeneticResult
	 * @internal param GeneticResultChild[] $children
	 *
	 */
	public function setChildren(): GeneticResult {
		$children = [];
		$results  = get_field( 'genetic_results_results', $this->ID );
		foreach ( $results as $result ) {
			$children[] = new GeneticResultChild( $result['gender'], $result['mutation'], $result['percent'], $result['factor'], $result['genotype'] );
		}
		$this->children = $children;

		return $this;
	}
}