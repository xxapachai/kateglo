<?php
gc_enable ();

require_once 'bootstrap.php';

use kateglo\application\providers;
use kateglo\application\utilities;
use kateglo\application\models;

function insertSolr(&$docStart, $segment = 2000) {
	try {
		
		/**
		 * 
		 * Get Log Service from Dependency Injector
		 * @var kateglo\application\utilities\interfaces\LogService
		 */
		$dataAccess = utilities\Injector::getInstance ( providers\EntityManager::$CLASS_NAME );
		
		$query = $dataAccess->get ()->createQuery ( "SELECT count(e.id) as summe FROM " . models\Source::CLASS_NAME . " e " );
		$sum = $query->getSingleScalarResult ();
		
		for($docStart; $docStart <= $sum; $docStart = $docStart + $segment) {
			
			$query = $dataAccess->get ()->createQuery ( "SELECT e FROM " . models\Source::CLASS_NAME . " e " );
			$query->setFirstResult ( $docStart );
			$query->setMaxResults ( $segment );
			
			$iterator = $query->iterate ();
			
			/*@var $entry kateglo\application\models\Source */
			while ( ($row = $iterator->next ()) !== false ) {
				$source = $row [0];
				$result = strip_tags ( html_entity_decode ( $source->getSource (), ENT_QUOTES, 'UTF-8' ) );
				$dataAccess->get ()->createQuery ( 'UPDATE ' . models\Source::CLASS_NAME . ' a SET a.clean = ?1 WHERE a.id = ?2' )->setParameter ( 1, $result )->setParameter ( 2, $source->getId () )->execute ();
			
			}
			echo "Entities: " . ($docStart + $segment) . " Collect: " . gc_collect_cycles () . " Memory usage : " . (memory_get_usage () / 1024) . " KB" . PHP_EOL . PHP_EOL;
		}
	} catch ( Exception $e ) {
		echo "Exception Caught!!! " . $e->getMessage () . " Sleep 10 seconds! ";
		sleep ( 10 );
		echo "Restart from the last " . $segment . " segment." . PHP_EOL . PHP_EOL;
		$docStart = ($docStart <= $segment) ? 0 : $docStart - $segment;
		insertSolr ( $docStart, $segment );
	}
}

$myDocStart = 0;
$mySegment = 2000;

insertSolr ( $myDocStart, $mySegment );

gc_disable ();
?>