<?php

namespace Excavator\Excavator;

class Gold extends Excavator
{
	/**
	 * 
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return array ['buy' => ['date' => , 'price' => ], 'sell' => ['date' => , 'price' => ],]
	 */
	public function getBestMomentToBuyAndSellInPeriod (\DateTime $startDate, \DateTime $endDate): array
	{
		$list = $this->_source->getListByPeriod($startDate, $endDate);

		$bestBuy = null;
		$bestSell = null;
		$higerPriceDifference = -1;
		
		foreach ($list as $buyKey => $buy)
		{
			unset($list[$buyKey]);
			
			foreach ($list as $sell)
			{
				$diffPrice = $sell['price'] - $buy['price'];
				if ($higerPriceDifference < $diffPrice)
				{
					$higerPriceDifference = $diffPrice;
					$bestBuy = $buy;
					$bestSell = $sell;
				}
			}
		}
		
		if ($higerPriceDifference === -1)
		{
			$return = [];
		}
		else
		{
			$return = [
				'buy' => ['date' => $bestBuy['date'], 'price' => $bestBuy['price']],
				'sell' => ['date' => $bestSell['date'], 'price' => $bestSell['price']],
			];
		}
		
		return $return;
	}
}

