<?
/**
 * Entry point of application
 */

// constants
define(LF, "\n"); // line break
define(APP_NAME, 'Kateglo'); // application name
define(APP_VERSION, 'v0.0.2'); // application name

// variables
$base_dir = dirname(__FILE__);
$is_post = ($_SERVER['REQUEST_METHOD'] == 'POST');
$title = APP_NAME;
ini_set('include_path', $base_dir . '/pear/');
foreach ($_GET as $key => $val)
{
	$_GET[$key] = trim($val);
}

require_once('config.php');
require_once('messages.php');

require_once('common.php');
require_once('class_db.php');
require_once('class_form.php');
require_once('class_logger.php');
require_once('Auth.php');

require_once('class_phrase.php');
require_once('class_glossary.php');

// initialization
$db = new db;
$db->connect($dsn);

// authentication & and logging
$auth = new Auth(
	'MDB2', array(
		'dsn' => $db->dsn,
		'table' => "sys_user",
		'usernamecol' => "user_id",
		'passwordcol' => "pass_key"
	), 'login');
$auth->start();
$logger = new logger(&$db, &$auth);
$logger->log();

// process
switch ($_GET['mod'])
{
	case 'auth':
		if ($_GET['action'] == 'logout')
		{
			$auth->logout();
			redir('./?');
		}
		break;
	case 'dict':
		$phrase = new phrase;
		$phrase->db = $db;
		$phrase->msg = $msg;
		$phrase->auth = $auth;
		if ($is_post && $auth->checkAuth() && $_GET['action'] == 'form') {
			$phrase->save_form();
		}
		break;
	case 'glo':
		$glossary = new glossary(&$db, &$auth, $msg);
		break;
}

// display
$has_content = false;
$body .= show_header();
switch ($_GET['mod'])
{
	case 'dict':
		if ($_GET['action'] == 'form')
		{
			$has_content = true;
			$body .= $phrase->show_form();
		}
		else
		{
			if ($_GET['phrase'])
			{
				$has_content = true;
				$body .= $phrase->show_phrase();
			}
		}
		if ($_GET['phrase']) $title = $_GET['phrase'] . ' - ' . $title;
		break;
	case 'glo':
		$has_content = true;
		$body .= $glossary->show_result();
		break;
	case 'auth':
		if ($_GET['action'] == 'login')
		{
			$has_content = true;
			$body .= login();
		}
		break;
}
// if no content
if (!$has_content)
	$body .= str_replace("\n", '<br />', file_get_contents('./docs/README.txt'));

// render
$ret .= '<html>' . LF;
$ret .= '<head>' . LF;
$ret .= '<title>' . $title . '</title>' . LF;
$ret .= '<link rel="stylesheet" href="./common.css" type="text/css" />' . LF;
$ret .= '<link rel="icon" href="./images/favicon.ico" type="image/x-icon">' . LF;
$ret .= '<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">' . LF;

$ret .= '</head>' . LF;
$ret .= '<body>' . LF;
$ret .= $body;
$ret .= sprintf('<p align="right"><a href="%2$s">%1$s %3$s</a></p>' . LF,
	APP_NAME, './docs/README.txt', APP_VERSION);
$ret .= '</body>' . LF;
$ret .= '</html>' . LF;
echo($ret);
?>