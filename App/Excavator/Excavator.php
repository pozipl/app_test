<?php

namespace Excavator\Excavator;

abstract class Excavator 
{
	/**
	 * 
	 * @var \Excavator\Source\Source
	 */
	protected $_source = null;


	/**
	 * 
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return array ['buy' => ['date' => , 'price' => ], 'sell' => ['date' => , 'price' => ],]
	 */
	abstract public function getBestMomentToBuyAndSellInPeriod (\DateTime $startDate, \DateTime $endDate): array;


	/**
	 * 
	 * @param \Excavator\Source\Source $source
	 */
	public function __construct(\Excavator\Source\Source $source)
	{
		$this->_source = $source;
	}
}