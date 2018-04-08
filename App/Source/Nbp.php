<?php

namespace Excavator\Source;

abstract class Nbp extends Source
{
	/**
	 * Check config set in the constructor
	 * 
	 * @param object $config
	 * @throws Exception
	 */
	protected function _checkConfig (object $config): void
	{
		if (empty($config->url) === true)
		{
			throw new Exception("NBP: empty url config");
		}
	}
	
	/**
	 * 
	 * @param array $params
	 * @return array
	 * @throws Exception
	 */
	protected function _callApi (array $params): array
	{
		$url = $this->_config->url;
		$url .= implode('/', $params) .'/';
		$url .= '?format=json';

		// if we need more control or options like ssl we can change to CURL
		$result = @file_get_contents($url);

		// check response heder
		$httpCode = explode(' ', $http_response_header[0])[1];
		if ($httpCode !== '200')
		{
			throw new Exception("NBP: connection error, http code: $httpCode");
		}
		
		return json_decode($result);
	}
	
	/**
	 * 
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param \Excavator\Source\callable $parmFun
	 * @return array
	 * @throws Exception
	 */
	protected function _callApiByPeriod (\DateTime $startDate, \DateTime $endDate, callable $parmFun): array
	{
		if ($startDate > $endDate)
		{
			throw new Exception("NBP: start date is older then end date");
		}
		
		$result = [];
		
		$dateNow = new \DateTime();
		$startDiffDay = (int)$dateNow->diff($startDate)->format('%R%a');
		$endDiffDay = (int)$dateNow->diff($endDate)->format('%R%a');
		
		$maxStart = $startDiffDay;
		$maxEnd = $startDiffDay;

		do {
			$maxEnd += 367; // max days in NBP
			
			if ($maxEnd > $endDiffDay)
			{
				$maxEnd = $endDiffDay;
			}

			// we could use DateTime but this is simpler to debug
			$maxStartDate = (new \DateTime())->modify("$maxStart day")->format('Y-m-d');
			$maxEndDate = (new \DateTime())->modify("$maxEnd day")->format('Y-m-d');
			
			$resultApi = $this->_callApi($parmFun($maxStartDate, $maxEndDate));
			$result = array_merge($result, $resultApi); // order merge matters
			
			$maxStart = $maxEnd + 1;
			
		} while ($maxEnd !== $endDiffDay);
		
		return $result;
	}
}

