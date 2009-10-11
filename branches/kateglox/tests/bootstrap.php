<?php
use kateglo\application\utilities;
use kateglo\application\configs;

date_default_timezone_set ( "Europe/Berlin" );

defined('DOCUMENT_ROOT')
|| define('DOCUMENT_ROOT', realpath(dirname(__FILE__)))
;

defined('DOCTRINE_PATH')
|| define('DOCTRINE_PATH', realpath(DOCUMENT_ROOT . '/../../doctrine/lib'))
;

defined('ZF_PATH')
|| define('ZF_PATH', realpath(DOCUMENT_ROOT . '/../../ZendFramework/library'))
;

defined('TAL_PATH')
|| define('TAL_PATH', realpath(DOCUMENT_ROOT . '/../../phptal/classes'))
;

defined('KATEGLO_PATH')
|| define('KATEGLO_PATH', realpath(DOCUMENT_ROOT . '/../../'))
;

defined('CONFIGS_PATH')
|| define('CONFIGS_PATH', '/configs/application.ini')
;

defined('BOOTSTRAP_PATH')
|| define('BOOTSTRAP_PATH', realpath(DOCUMENT_ROOT . '/../tests'))
;

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(DOCUMENT_ROOT . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
realpath(DOCTRINE_PATH),
realpath(ZF_PATH),
realpath(TAL_PATH),
realpath(KATEGLO_PATH),
realpath(BOOTSTRAP_PATH),
get_include_path(),
)));

/** PHP Unit */
require_once 'PHPUnit/Framework.php';

/** Zend Application */
require_once 'Zend/Application.php';

/** Template Engine */
require_once 'PHPTAL.php';

/** Load Class for Doctrine and Kateglo */
require_once 'Doctrine/Common/ClassLoader.php';


// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . CONFIGS_PATH
);

//instantiate autoloader for Doctrine and Kateglo
new Doctrine\Common\ClassLoader ( );

// Initialize Configuration
configs\Configs::getInstance ( new Zend_Config_Ini ( APPLICATION_PATH . CONFIGS_PATH, APPLICATION_ENV ) );
?>