<?php

namespace Excavator\Source;

abstract class Source 
{
	protected $_config = null;
	
	/**
	 * Check config set in the constructor
	 * 
	 * @param object $config
	 * @throws Exception
	 */
	abstract protected function _checkConfig (object $config): void;
	
	/**
	 * 
	 * 
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return array
	 */
	abstract protected function getListByPeriod (\DateTime $startDate, \DateTime $endDate): array;
	
	/**
	 * 
	 * @param object $config
	 */
	public function __construct(object $config)
	{
		$this->_checkConfig($config);
		$this->_config = $config;
	}
}