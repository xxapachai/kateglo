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

/** +++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate Zend Framework +++ */

/**
 *
 * Define Zend Framework Library Path
 * Default: www/ZendFramework/library
 *
 * @var string
 */
$zfPath = realpath ( $wwwRoot . DIRECTORY_SEPARATOR . 'ZendFramework' . DIRECTORY_SEPARATOR . 'library' );

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

use kateglo\application\providers;
use kateglo\application\models;
/**
 * 
 * Get Log Service from Dependency Injector
 * @var kateglo\application\utilities\interfaces\LogService
 */
$dataAccess = utilities\Injector::getInstance ( providers\EntityManager::$CLASS_NAME );

require_once 'Zend/Search/Lucene.php';
require_once 'Zend/Search/Lucene/Field.php';
require_once 'Zend/Search/Lucene/Document.php';

$indexPath = KATEGLO_ROOT . '/build/index/entry_index';
$entryIndex = Zend_Search_Lucene::create ( $indexPath );
$query = $dataAccess->get ()->createQuery ( "SELECT count(e.id) as summe FROM " . models\Entry::CLASS_NAME . " e " );
$sum = $query->getSingleScalarResult ();

$segment = 100;

for($i = 0; $i <= $sum; $i = $i + $segment) {
	$query = $dataAccess->get ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e " );
	$query->setFirstResult ( $i );
	$query->setMaxResults ( $segment );
	
	$iterator = $query->iterate ();
	
	/*@var $entry kateglo\application\models\Entry */
	while ( ($row = $iterator->next ()) !== false ) {
		$doc = new Zend_Search_Lucene_Document ();
		$entry = $row [0];
		$entryField = Zend_Search_Lucene_Field::keyword ( 'entry', $entry->getEntry (), 'utf-8' );
		$entryField->boost = 2;
		$doc->addField ( $entryField );
		$doc->addField ( Zend_Search_Lucene_Field::keyword ( 'id', $entry->getId () ), 'utf-8' );
		
		/*@var $meaning kateglo\application\models\Meaning */
		foreach ( $entry->getMeanings () as $meaning ) {
			
			/*@var $antonym kateglo\application\models\Antonym */
			foreach ( $meaning->getAntonyms () as $antonym ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'antonym', $antonym->getAntonym ()->getEntry ()->getEntry () ) );
			}
			
			/*@var $definition kateglo\application\models\Definition */
			foreach ( $meaning->getDefinitions () as $definition ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'definition', $definition->getDefinition () ) );
				
				if ($definition->getClazz () instanceof kateglo\application\models\Clazz) {
					$doc->addField ( Zend_Search_Lucene_Field::text ( 'class', $clazzs [] = $definition->getClazz ()->getClazz () ) );
					
					if ($definition->getClazz ()->getCategory () instanceof kateglo\application\models\ClazzCategory) {
						$doc->addField ( Zend_Search_Lucene_Field::text ( 'classCategory', $definition->getClazz ()->getCategory ()->getCategory () ) );
					}
				}
				
				/*@var $discipline kateglo\application\models\Discipline */
				foreach ( $definition->getDisciplines () as $discipline ) {
					$doc->addField ( Zend_Search_Lucene_Field::text ( 'discipline', $discipline->getDiscipline () ) );
				}
				
				/*@var $sample kateglo\application\models\Sample */
				foreach ( $definition->getSamples () as $sample ) {
					$doc->addField ( Zend_Search_Lucene_Field::text ( 'sample', $sample->getSample () ) );
				}
			
			}
			
			/*@var $misspelled kateglo\application\models\Misspelled */
			foreach ( $meaning->getMisspelleds () as $misspelled ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'misspelled', $misspelled->getMisspelled ()->getEntry ()->getEntry () ) );
			}
			
			/*@var $relation kateglo\application\models\Relation */
			foreach ( $meaning->getRelations () as $relation ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'relation', $relation->getRelation ()->getEntry ()->getEntry () ) );
			}
			
			/*@var $synonym kateglo\application\models\Synonym */
			foreach ( $meaning->getSynonyms () as $synonym ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'synonym', $synonym->getSynonym ()->getEntry ()->getEntry () ) );
			}
			
			if ($meaning->getSpelled () instanceof kateglo\application\models\Misspelled) {
				$doc->addField ( Zend_Search_Lucene_Field::keyword ( 'spelled', $meaning->getSpelled ()->getMeaning ()->getEntry ()->getEntry () ) );
			}
			
			/*@var $syllabel kateglo\application\models\Syllabel */
			foreach ( $meaning->getSyllabels () as $syllabel ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'syllabel', $syllabel->getSyllabel () ) );
			}
			
			/*@var $type kateglo\application\models\Type */
			foreach ( $meaning->getTypes () as $type ) {
				$doc->addField ( Zend_Search_Lucene_Field::text ( 'type', $types [] = $type->getType () ) );
				
				if ($type->getCategory () instanceof kateglo\application\models\TypeCategory) {
					$doc->addField ( Zend_Search_Lucene_Field::text ( 'typeCategory', $typeCategories [] = $type->getCategory ()->getCategory () ) );
				}
			}
		
		}
		
		/*@var $source kateglo\application\models\Source */
		foreach ( $entry->getSources () as $source ) {
			$doc->addField ( Zend_Search_Lucene_Field::text ( 'source', strip_tags ( html_entity_decode ( $source->getSource (), ENT_QUOTES, 'UTF-8' ) ) ) );
			
			$doc->addField ( Zend_Search_Lucene_Field::text ( 'sourceCategory', $sourceCategories [] = $source->getCategory ()->getCategory () ) );
		}
		
		$entryIndex->addDocument ( $doc );
		$dataAccess->getEntityManager ()->detach ( $row [0] );
	}
	
	$dataAccess->getEntityManager ()->clear ();
	
	echo "Entities: " . ($i + $segment) . " Collect: " . gc_collect_cycles () . " Memory usage : " . (memory_get_usage () / 1024) . " KB" . PHP_EOL . PHP_EOL;
}
$entryIndex = Zend_Search_Lucene::open ( $indexPath );
$entryIndex->optimize ();
gc_disable ();
?>