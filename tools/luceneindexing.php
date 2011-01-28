<?php
gc_enable ();

/** Set Time Zone to where the server is */
date_default_timezone_set ( "Europe/Berlin" );

/** Define application environment for the application.ini */
defined ( 'APPLICATION_ENV' ) || define ( 'APPLICATION_ENV', (getenv ( 'APPLICATION_ENV' ) ? getenv ( 'APPLICATION_ENV' ) : //'linuxDevelopment'
'windowsDevelopment') ); //'macDevelopment'
//'production'


/**
 *
 * Define the path of the Kateglo
 * Default: www/kateglo/
 *
 * @var string
 */
defined ( 'KATEGLO_ROOT' ) || define ( 'KATEGLO_ROOT', realpath ( dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . '..' ) );

/**
 *
 * Define the path of the Kateglo
 * Default: www/
 *
 */
$wwwRoot = realpath ( KATEGLO_ROOT . DIRECTORY_SEPARATOR . '..' );

/**
 * Define path to application directory
 * Default: www/kateglo/application
 */
defined ( 'APPLICATION_PATH' ) || define ( 'APPLICATION_PATH', realpath ( KATEGLO_ROOT . DIRECTORY_SEPARATOR . 'application' ) );

/**
 * Define where to find the ini file
 * Default: www/kateglo/application/configs/application.ini
 */
defined ( 'CONFIGS_PATH' ) || define ( 'CONFIGS_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini' );

/**
 *
 * Define Custom Libraries. Sometimes Extending the original ones.
 * Default: www/kateglo/library
 *
 * @var string
 */
$libraryPath = realpath ( KATEGLO_ROOT . DIRECTORY_SEPARATOR . 'library' );

/** ++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate Stubbles Inversion of Control +++ */

/**
 *
 * Define Stubbles Library Path.
 * Default : www/stubbles/
 *
 * @var string
 */
$stubblesPath = realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'stubbles' );

/**
 *
 * Define Stubbles Cache Directory.
 * Default : www/kateglo/cache/stubbles
 *
 * @var string
 */
$stubblesCache = realpath ( KATEGLO_ROOT . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'stubbles' );

/**
 *
 * Define Stubbles Class Loader File.
 * Default : www/stubbles/src/main/php/net/stubbles/stubClassLoader.php
 *
 * @var string
 */
$stubblesClassLoader = realpath ( $stubblesPath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'main' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'net' . DIRECTORY_SEPARATOR . 'stubbles' . DIRECTORY_SEPARATOR . 'stubClassLoader.php' );

/** Import Stubbles Bootstrap File */
require_once $stubblesPath . DIRECTORY_SEPARATOR . 'bootstrap.php';

/** Override Stubbles original bootstrap */
require_once $libraryPath . DIRECTORY_SEPARATOR . 'Stubbles' . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 *
 * Define Stubbles Pathes
 *
 * @var array
 */
$stubblesPathes = array ("project" => KATEGLO_ROOT, "cache" => $stubblesCache );

/**
 * Instantiate autoloader for Stubbles
 * Using the Class that override the original init() method.
 */
kateglo\library\Stubbles\stubBootstrap::init ( $stubblesPathes, $stubblesClassLoader );

/**
 * Load the Stubbles Inversion of Control
 * IoC ready to use.
 */
\stubClassLoader::load ( 'net::stubbles::ioc::stubBinder' );

/** +++ END : Initiate Stubbles Inversion of Control +++ */
/** ++++++++++++++++++++++++++++++++++++++++++++++++++++ */

/** ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate Doctrine Object Relational Mapper +++ */

/**
 *
 * Define Doctrine Object Relational Mapper Library Path
 * Default: www/doctrine
 *
 * @var string
 */
$doctrinePath = realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'doctrine' );

/** Import Doctrine Class Loader */
require_once $doctrinePath . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'ClassLoader.php';

/**
 *
 * Register autoloader for Doctrine
 *
 * @var Doctrine\Common\ClassLoader
 */
$doctrineLoader = new Doctrine\Common\ClassLoader ( 'Doctrine', realpath ( $doctrinePath ) );
$doctrineLoader->register ();

/**
 *
 * Register autoloader for Kateglo
 *
 * @var Doctrine\Common\ClassLoader
 */
$kategloLoader = new Doctrine\Common\ClassLoader ( 'kateglo', realpath ( $wwwRoot ) );
$kategloLoader->register ();

/** +++ End : Initiate Doctrine Object Relational Mapper +++ */
/** ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

/** +++++++++++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate PHPTal Template Engine +++ */

/**
 *
 * Define Tal Template Engine Library Path
 * Default: www/phptal/classes
 *
 * @var string
 */
$talPath = realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'phptal' . DIRECTORY_SEPARATOR . 'classes' );

/** Import PHPTal Template Engine Loader */
require_once $talPath . DIRECTORY_SEPARATOR . 'PHPTAL.php';

/** +++ END : Initiate PHPTal Template Engine +++ */
/** +++++++++++++++++++++++++++++++++++++++++++++ */
set_include_path ( realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'solr' ) );
require_once ('Apache/Solr/Service.php');
/** +++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate Zend Framework +++ */

/**
 *
 * Define Zend Framework Library Path
 * Default: www/ZendFramework/library
 *
 * @var string
 */
$zfPath = realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'library' );

/** Ensure libraries is on include_path */
set_include_path ( implode ( PATH_SEPARATOR, array (realpath ( $zfPath ), realpath ( $libraryPath ), get_include_path () ) ) );

/** Import Zend Framework Loader */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Application.php';

/**
 *
 * Initiate Framework
 *
 * @var Zend_Application
 */
$application = new Zend_Application ( APPLICATION_ENV, CONFIGS_PATH );

/** +++ END : Initiate Zend Framework +++ */
/** +++++++++++++++++++++++++++++++++++++++++++++ */

use kateglo\application\utilities;
use kateglo\application\utilities\interfaces;
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
		$logService = utilities\Injector::getInstance ( interfaces\LogService::INTERFACE_NAME );
		$dataAccess = utilities\Injector::getInstance ( interfaces\DataAccess::INTERFACE_NAME );
		
		$query = $dataAccess->getEntityManager ()->createQuery ( "SELECT count(e.id) as summe FROM " . models\Entry::CLASS_NAME . " e " );
		$sum = $query->getSingleScalarResult ();
		
		for($docStart; $docStart <= $sum; $docStart = $docStart + $segment) {
			
			$query = $dataAccess->getEntityManager ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e " );
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
					
					/*@var $discipline kateglo\application\models\Discipline */
					foreach ($equivalent->getDisciplines() as $discipline) {
						$doc->addField ( 'equivalentDiscipline', $discipline->getDiscipline() );
					}				
						
					
					$doc->addField ( 'language', $equivalent->getForeign()->getLanguage()->getLanguage() );
				}
				
				$solr->addDocument ( $doc );
				
				$dataAccess->getEntityManager ()->detach ( $row [0] );
			}
			$dataAccess->getEntityManager ()->clear ();
			
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