<?php

/*
 * File to start mini application. Run me in CLI php Run.php
 */

$conf = new stdClass;
$conf->basePath = __DIR__ .'/';

$conf->api = new stdClass();
$conf->api->nbp = new stdClass();
$conf->api->nbp->url = 'http://api.nbp.pl/api/';

if (version_compare(PHP_VERSION, '7.2.0') < 0) // script need PHP 7.2
{
	 echo "Your PHP version (". PHP_VERSION .") it's to old. Script need 7.2.3 version.\n";
	 die;
}
elseif (version_compare(PHP_VERSION, '7.2.3') !== 0)
{
    echo "Your PHP version (". PHP_VERSION .") it's not 7.2.3 version, but maybe script will be work.\n";
}

spl_autoload_register(function ($className) use ($conf)
{
	// remove project name and replace slash
	$pathClass = str_replace('\\', '/', substr($className, 10));
	$path = $conf->basePath .'App/'. $pathClass . '.php';
	require_once $path;
});

try {
	$startPeriodDate = (new \DateTime())->modify('-5 year');
	$endPeriodDate = new \DateTime();

	$sourceNbp = new \Excavator\Source\NbpGold($conf->api->nbp);
	$excavatorFold = new \Excavator\Excavator\Gold($sourceNbp);

	$result = $excavatorFold->getBestMomentToBuyAndSellInPeriod($startPeriodDate, $endPeriodDate);
	
	if ($result === [])
	{
		echo "Empty results.\n";
	}
	else
	{
		echo "The best moment to buy and sell gold to get the highest profit:\n";
		echo "Buy: {$result['buy']['date']} {$result['buy']['price']}\n";
		echo "Sell: {$result['sell']['date']} {$result['sell']['price']}\n";
	}
}
catch (\Exception $e)
{
	echo 'Error: '. $e->getMessage() ."\n";
}
