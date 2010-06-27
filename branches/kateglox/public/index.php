<?php
use kateglo\application\utilities\interfaces;
date_default_timezone_set ( "Europe/Berlin" );

defined('DOCUMENT_ROOT')
|| define('DOCUMENT_ROOT', realpath(dirname(__FILE__)));

defined('DOCTRINE_PROXIES_PATH')
|| define('DOCTRINE_PROXIES_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'build' . DIRECTORY_SEPARATOR . 'proxies'));

defined('DOCTRINE_PATH')
|| define('DOCTRINE_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'doctrine' . DIRECTORY_SEPARATOR . 'lib'));

defined('ZF_PATH')
|| define('ZF_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'ZendFramework'. DIRECTORY_SEPARATOR .'library'));

defined('TAL_PATH')
|| define('TAL_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'phptal' . DIRECTORY_SEPARATOR . 'classes'));

defined('STUBBLES_PATH')
|| define('STUBBLES_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'stubbles' ));

defined('KATEGLO_PATH')
|| define('KATEGLO_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'));

defined('INDEX_PATH')
|| define('INDEX_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'index'));

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'application'));

defined('CONFIGS_PATH')
|| define('CONFIGS_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini');

defined('LIBRARY_PATH')
|| define('LIBRARY_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'library');

// Define application environment
//defined('APPLICATION_ENV')
//|| define('APPLICATION_ENV', 'linuxDevelopment');
//defined('APPLICATION_ENV')
//|| define('APPLICATION_ENV', 'macDevelopment');
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', 'windowsDevelopment');
//defined('APPLICATION_ENV')
//|| define('APPLICATION_ENV', 'production');

define('PHPTAL_PHP_CODE_DESTINATION',
realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'proxies'));

define('STUBBLES_CACHE',
realpath(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'proxies'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(LIBRARY_PATH),
realpath(DOCTRINE_PATH),
realpath(ZF_PATH),
realpath(TAL_PATH),
realpath(STUBBLES_PATH),
realpath(KATEGLO_PATH),
get_include_path(),
)));

/** Zend Application */
require_once 'Zend'.DIRECTORY_SEPARATOR.'Application.php';

/** Template Engine */
require_once 'PHPTAL.php';

/** Stubbles Inversion of Control */
require_once 'bootstrap.php';

/** Load Class for Doctrine and Kateglo */
require_once 'Doctrine' . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'ClassLoader.php';

use kateglo\application\utilities;
use kateglo\application\configs;

//instantiate autoloader for Stubbles
stubBootstrap::init(array("project" => DOCUMENT_ROOT, "cache" => STUBBLES_CACHE));
stubClassLoader::load('net::stubbles::ioc::stubBinder');

//instantiate autoloader for Doctrine and Momoku
$doctrineLoader = new Doctrine\Common\ClassLoader ( 'Doctrine', realpath(DOCTRINE_PATH));
$doctrineLoader->register();

$kategloLoader = new Doctrine\Common\ClassLoader ( 'kateglo', realpath(KATEGLO_PATH));
$kategloLoader->register();

// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
CONFIGS_PATH
);

$logService = utilities\Injector::getInstance(interfaces\LogService::INTERFACE_NAME);
try {
	//run kateglo
	$application->bootstrap()->run();

} catch ( Exception $e ) {
	//catch anything in log files
	$logService->get()->log($e->getTraceAsString(), \Zend_Log::ERR);
}
?>