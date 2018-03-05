<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 03/10/17
 * Time: 11:58
 */

namespace SilkRock;


class GeneticResultChild {
	/**
	 * @var $gender string
	 */
	/**
	 * @var $name string
	 */
	/**
	 * @var $percent float
	 */
	/**
	 * @var $factor string
	 */
	/**
	 * @var $genotype string
	 */
	private $gender,
		$name,
		$percent,
		$factor,
		$genotype;

	/**
	 * GeneticResultChild constructor.
	 *
	 * @param string $gender
	 * @param string $name
	 * @param float|int|string $percent
	 * @param string $factor
	 * @param string $genotype
	 */
	public function __construct( $gender, $name, $percent = 0, $factor = '', $genotype = '' ) {
		$this->gender   = $gender;
		$this->name     = $name;
		$this->percent  = $percent;
		$this->factor   = $factor;
		$this->genotype = $genotype;
	}

	public function getName() {
		$gender = new BirdGender( $this->name->ID, BirdGender::MALE, $this->factor );

		return $gender->getDisplayName();
	}

	public function getGender() {
		return $this->gender[0];
	}

	public function getPercent() {
		return $this->percent;
	}

	public function getGenotype() {
		$genotypes = '';
		if ( is_array( $this->genotype ) && $this->genotype ) {
			$genotypes = array_map( 'SilkRock\BirdGender::map_genotypes', $this->genotype );
			$genotypes = join( '/', $genotypes );
		}

		return $genotypes;
	}
}