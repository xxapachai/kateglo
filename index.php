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

require_once('config.php');
require_once('messages.php');

require_once('common.php');
require_once('class_db.php');
require_once('class_form.php');
require_once('class_logger.php');
require_once('Auth.php');

require_once('class_phrase.php');

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
if ($_GET['mod'] == 'auth' && $_GET['action'] == 'logout')
{
	$auth->logout();
	redir('./?');
}

// phrase class
$phrase = new phrase;
$phrase->db = $db;
$phrase->msg = $msg;
$phrase->auth = $auth;

// process
if ($is_post && $auth->checkAuth() && $_GET['action'] == 'form') {
	$phrase->save_form();
}

// display
$body .= show_header();
if ($_GET['mod'] == 'auth' && $_GET['action'] == 'login')
{
	$body .= login();
}
else
{
	if ($_GET['action'] == 'form')
		$body .= $phrase->show_form();
	else
	{
		if ($_GET['phrase'])
			$body .= $phrase->show_phrase();
		else
			$body .= str_replace("\n", '<br />', file_get_contents('./docs/README.txt'));
	}
	if ($_GET['phrase']) $title = $_GET['phrase'] . ' - ' . $title;
}

// render
$ret .= '<html>' . LF;
$ret .= '<head>' . LF;
$ret .= '<title>' . $title . '</title>' . LF;
$ret .= '<link rel="stylesheet" href="./common.css" type="text/css" />' . LF;
$ret .= '</head>' . LF;
$ret .= '<body>' . LF;
$ret .= $body;
$ret .= sprintf('<p align="right"><a href="%2$s">%1$s %3$s</a></p>' . LF,
	APP_NAME, './docs/README.txt', APP_VERSION);
$ret .= '</body>' . LF;
$ret .= '</html>' . LF;
echo($ret);
?>