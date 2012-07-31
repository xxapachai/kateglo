<?php

/** Set Time Zone to where the server is */
date_default_timezone_set("Europe/Berlin");

/** Define application environment for the application.ini */
defined('APPLICATION_ENV') || define ('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') :
    'production'));

/**
 *
 * Define the path of the Vendor
 * Default: vendor/
 *
 */
$vendor = realpath(dirname(__FILE__) . '/../vendor');

require_once realpath($vendor . '/autoload.php');

/**
 * Define path to application directory
 * Default: www/kateglo/application
 */
defined('APPLICATION_PATH') || define ('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/**
 * Define where to find the ini file
 * Default: www/kateglo/application/configs/application.ini
 */
defined('CONFIGS_PATH') || define ('CONFIGS_PATH', APPLICATION_PATH . '/configs/application.ini');

/**
 *
 * Register autoloader for Kateglo
 *
 * @var Doctrine\Common\ClassLoader
 */
$kategloLoader = new Doctrine\Common\ClassLoader ('kateglo', realpath(dirname(__FILE__) . '/../..'));
$kategloLoader->register();

/** +++++++++++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate PHPTal Template Engine +++ */

set_include_path(realpath($vendor . '/phptal/phptal/classes'));
/** Import PHPTal Template Engine Loader */
require_once realpath($vendor . '/phptal/phptal/classes/PHPTAL.php');

/** +++ END : Initiate PHPTal Template Engine +++ */
/** +++++++++++++++++++++++++++++++++++++++++++++ */
set_include_path(realpath($vendor . '/room13/PhpSolrClient'));
require_once ('Apache/Solr/Service.php');
/** +++++++++++++++++++++++++++++++++++++++ */
/** +++ BEGIN : Initiate Zend Framework +++ */

/** Ensure libraries is on include_path */
set_include_path(implode(PATH_SEPARATOR, array(realpath($vendor . '/zendframework/zendframework1/library'),
    realpath(dirname(__FILE__) . '/../vendor/doctrine/common/lib/'),
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path())));

/** Import Zend Framework Loader */
require_once 'Zend/Application.php';

/**
 *
 * Initiate Framework
 *
 * @var Zend_Application
 */
$application = new Zend_Application (APPLICATION_ENV, CONFIGS_PATH);

use kateglo\application\utilities\Injector;

/**
 *
 * Get Log Service from Dependency Injector
 * @var Zend_Log
 */
$logService = Injector::getInstance('Zend_Log');

try {
    //run kateglo
    $application->bootstrap()->run();

} catch (Exception $e) {
    //catch anything in log files
    $logService->log($e->getTraceAsString(), Zend_Log::ERR);
}

/** +++ END : Initiate Zend Framework +++ */
/** +++++++++++++++++++++++++++++++++++++ */
?>