<?php
date_default_timezone_set ( "Europe/Berlin" );

defined('DOCUMENT_ROOT')
|| define('DOCUMENT_ROOT', realpath(dirname(__FILE__)));

defined('DOCTRINE_PROXIES_PATH')
|| define('DOCTRINE_PROXIES_PATH', realpath(DOCUMENT_ROOT . '/../proxies'));

defined('DOCTRINE_PATH')
|| define('DOCTRINE_PATH', realpath(DOCUMENT_ROOT . '/../../doctrine/lib'));

defined('ZF_PATH')
|| define('ZF_PATH', realpath(DOCUMENT_ROOT . '/../../ZendFramework/library'));

defined('TAL_PATH')
|| define('TAL_PATH', realpath(DOCUMENT_ROOT . '/../../phptal/classes'));

defined('KATEGLO_PATH')
|| define('KATEGLO_PATH', realpath(DOCUMENT_ROOT . '/../../'));

defined('INDEX_PATH')
|| define('INDEX_PATH', realpath(DOCUMENT_ROOT . '/../index'));

defined('CONFIGS_PATH')
|| define('CONFIGS_PATH', '/configs/application.ini');

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(DOCUMENT_ROOT . '/../application'));

// Define application environment
//defined('APPLICATION_ENV')
//|| define('APPLICATION_ENV', 'linuxDevelopment');
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', 'windowsDevelopment');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
realpath(DOCTRINE_PATH),
realpath(ZF_PATH),
realpath(TAL_PATH),
realpath(KATEGLO_PATH),
get_include_path(),
)));

/** Zend Application */
require_once 'Zend/Application.php';

/** Template Engine */
require_once 'PHPTAL.php';

/** Load Class for Doctrine and Kateglo */
require_once 'Doctrine/Common/GlobalClassLoader.php';

use kateglo\application\utilities;
use kateglo\application\configs;

//instantiate autoloader for Doctrine and Kateglo
$classLoader = new Doctrine\Common\GlobalClassLoader ( );
$classLoader->registerNamespace('Doctrine', realpath(DOCTRINE_PATH));
$classLoader->registerNamespace('kateglo', realpath(KATEGLO_PATH));
$classLoader->register();

// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . CONFIGS_PATH
);


try {
	// Initialize Configuration
	configs\Configs::getInstance ( new \Zend_Config_Ini ( APPLICATION_PATH . CONFIGS_PATH, APPLICATION_ENV ) );

	//run kateglo
	$application->bootstrap()->run();

} catch ( Exception $e ) {
	//catch anything in log files
	utilities\LogService::getInstance()->log($e->getTraceAsString(), \Zend_Log::ERR);
}
?>