<?php
gc_enable ();

require_once 'bootstrap.php';

use kateglo\application\providers;
use kateglo\application\utilities;
use kateglo\application\models;

function insertSolr(&$docStart, $segment = 100) {
	try {
		$solr = new Apache_Solr_Service ( 'localhost', '8080', '/solr/' );
		
		if (! $solr->ping ()) {
			die ( 'Can not access solr' );
		}
		
		/**
		 * 
		 * Get Log Service from Dependency Injector
		 * @var kateglo\application\utilities\interfaces\LogService
		 */
		$dataAccess = utilities\Injector::getInstance ( providers\EntityManager::$CLASS_NAME );
		
		$query = $dataAccess->get ()->createQuery ( "SELECT count(e.id) as summe FROM " . models\Entry::CLASS_NAME . " e " );
		$sum = $query->getSingleScalarResult ();
		
		for($docStart; $docStart <= $sum; $docStart = $docStart + $segment) {
			
			$query = $dataAccess->get ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e " );
			$query->setFirstResult ( $docStart );
			$query->setMaxResults ( $segment );
			
			$iterator = $query->iterate ();
			
			/*@var $entry kateglo\application\models\Entry */
			while ( ($row = $iterator->next ()) !== false ) {
				$entry = $row [0];
				
				$doc = new Apache_Solr_Document ();
				$doc->addField ( 'entry', $entry->getEntry (), 2 );
				$doc->addField ( 'id', $entry->getId () );
				
				/*@var $meaning kateglo\application\models\Meaning */
				foreach ( $entry->getMeanings () as $meaning ) {
					
					/*@var $antonym kateglo\application\models\Antonym */
					foreach ( $meaning->getAntonyms () as $antonym ) {
						$doc->addField ( 'antonym', $antonym->getAntonym ()->getEntry ()->getEntry () );
					}
					
					/*@var $definition kateglo\application\models\Definition */
					foreach ( $meaning->getDefinitions () as $definition ) {
						$doc->addField ( 'definition', $definition->getDefinition () );
						
						if ($definition->getClazz () instanceof kateglo\application\models\Clazz) {
							$doc->addField ( 'class', $definition->getClazz ()->getClazz () );
							
							if ($definition->getClazz ()->getCategory () instanceof kateglo\application\models\ClazzCategory) {
								$doc->addField ( 'classCategory', $definition->getClazz ()->getCategory ()->getCategory () );
							}
						}
						
						/*@var $discipline kateglo\application\models\Discipline */
						foreach ( $definition->getDisciplines () as $discipline ) {
							$doc->addField ( 'discipline', $discipline->getDiscipline () );
						}
						
						/*@var $sample kateglo\application\models\Sample */
						foreach ( $definition->getSamples () as $sample ) {
							$doc->addField ( 'sample', $sample->getSample () );
						}
					
					}
					
					/*@var $misspelled kateglo\application\models\Misspelled */
					foreach ( $meaning->getMisspelleds () as $misspelled ) {
						$doc->addField ( 'misspelled', $misspelled->getMisspelled ()->getEntry ()->getEntry () );
					}
					
					/*@var $relation kateglo\application\models\Relation */
					foreach ( $meaning->getRelations () as $relation ) {
						$doc->addField ( 'relation', $relation->getRelation ()->getEntry ()->getEntry () );
					}
					
					/*@var $synonym kateglo\application\models\Synonym */
					foreach ( $meaning->getSynonyms () as $synonym ) {
						$doc->addField ( 'synonym', $synonym->getSynonym ()->getEntry ()->getEntry () );
					}
					
					if ($meaning->getSpelled () instanceof kateglo\application\models\Misspelled) {
						$doc->addField ( 'spelled', $meaning->getSpelled ()->getMeaning ()->getEntry ()->getEntry () );
					}
					
					/*@var $syllabel kateglo\application\models\Syllabel */
					foreach ( $meaning->getSyllabels () as $syllabel ) {
						$doc->addField ( 'syllabel', $syllabel->getSyllabel () );
					}
					
					/*@var $type kateglo\application\models\Type */
					foreach ( $meaning->getTypes () as $type ) {
						$doc->addField ( 'type', $type->getType () );
						
						if ($type->getCategory () instanceof kateglo\application\models\TypeCategory) {
							$doc->addField ( 'typeCategory', $type->getCategory ()->getCategory () );
						}
					}
				
				}
				
				/*@var $source kateglo\application\models\Source */
				foreach ( $entry->getSources () as $source ) {
					$doc->addField ( 'source', strip_tags ( html_entity_decode ( $source->getSource (), ENT_QUOTES, 'UTF-8' ) ) );
					
					$doc->addField ( 'sourceCategory', $source->getCategory ()->getCategory () );
				}
				
				/*@var $equivalent kateglo\application\models\Equivalent */
				foreach ( $entry->getEquivalents () as $equivalent ) {
					$doc->addField ( 'foreign', $equivalent->getForeign()->getForeign() );	
					$equivalentData['foreign'] = $equivalent->getForeign()->getForeign();
					
					$doc->addField ( 'language', $equivalent->getForeign()->getLanguage()->getLanguage() );
					$equivalentData['language'] = $equivalent->getForeign()->getLanguage()->getLanguage();
					
					$disciplineData = array();
					/*@var $discipline kateglo\application\models\Discipline */
					foreach ($equivalent->getDisciplines() as $disciplineEq) {
						$doc->addField ( 'equivalentDiscipline', $disciplineEq->getDiscipline() );
						$disciplineData[] = $disciplineEq->getDiscipline();
					}				
					$equivalentData['discipline'] = $disciplineData;
					
					$doc->addField('equivalent', json_encode($equivalentData));
				}
				
				$solr->addDocument ( $doc );
				
				$dataAccess->get ()->detach ( $row [0] );
			}
			$dataAccess->get ()->clear ();
			
			$solr->commit ();			
			$solr->optimize ();
			
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
$mySegment = 100;

insertSolr ( $myDocStart, $mySegment );

gc_disable ();
?>