<?php

namespace Excavator\Source;

class NbpGold extends Nbp
{
	/**
	 * 
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return array
	 */
	public function getListByPeriod (\DateTime $startDate, \DateTime $endDate): array
	{
		$paramApiFun = function ($startDate, $endDate) { return ['cenyzlota', $startDate, $endDate]; };
		$result = $this->_callApiByPeriod($startDate, $endDate, $paramApiFun);
		
		$newResult = [];
		foreach ($result as $row)
		{
			$newResult[] = ['date' => $row->data, 'price' => $row->cena];
		}
		
		return $newResult;
	}
}

