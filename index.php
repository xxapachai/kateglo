<?
/**
 * Entry point of application
 */
// base dir
$base_dir = dirname(__FILE__);
ini_set('include_path', $base_dir . '/pear/');

// includes
require_once($base_dir . '/config/settings.php');
require_once($base_dir . '/config/config.php');
require_once($base_dir . '/config/messages.php');
require_once('common.php');
require_once('Auth.php');
require_once($base_dir . '/classes/class_db.php');
require_once($base_dir . '/classes/class_form.php');
require_once($base_dir . '/classes/class_logger.php');
require_once($base_dir . '/classes/class_page.php');

// initialization
$db = new db;
$db->connect($dsn);
$db->msg = $msg;

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

// define mod
$mods = array(
	'user', 'dictionary', 'glossary', 'home', 'doc', 'proverb'
);
$_GET['mod'] = strtolower($_GET['mod']);
if ($_GET['mod'] == 'dict') $_GET['mod'] = 'dictionary'; // backward
if ($_GET['mod'] == 'glo') $_GET['mod'] = 'glossary'; // backward
if (!in_array($_GET['mod'], $mods)) $_GET['mod'] = 'home';
$mod = $_GET['mod'];

// process
require_once($base_dir . '/modules/class_' . $mod . '.php');
$page = new $mod(&$db, &$auth, $msg);
$page->process();

// display
$body .= $page->show();
$title = ($mod == 'home') ? APP_NAME : APP_SHORT;
if (!$page->title && $mod != 'home')
{
	if ($msg[$mod]) $page->title = $msg[$mod];
	if ($_GET['phrase']) $page->title = $_GET['phrase'] . ' - ' . $page->title;
}
$title = $page->title ? $page->title . ' - ' . $title : $title;

// render
$ret .= '<?xml version="1.0" encoding="utf-8"?>' . LF;
$ret .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . LF;
$ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">' . LF;
$ret .= '<head>' . LF;
$ret .= '<title>' . $title . '</title>' . LF;
if ($keywords = $page->get_keywords())
	$ret .= '<meta name="keywords" content="' . $keywords . '" />' . LF;
if ($description = $page->get_description())
	$ret .= '<meta name="description" content="' . $description . '" />' . LF;
$ret .= '<link rel="stylesheet" href="./common.css" type="text/css" />' . LF;
$ret .= '<link rel="icon" href="./images/favicon.ico" type="image/x-icon" />' . LF;
$ret .= '<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />' . LF;
$ret .= '<link rel="search" type="application/opensearchdescription+xml" href="./opensearch_desc.php" title="Kateglo" /> ' . LF;
$ret .= '</head>' . LF;
$ret .= '<body>' . LF;
if ($mod == 'home') $ret .= '<div id="home">' . LF;
$ret .= show_header();
$ret .= '<div id="content">' . LF;
$ret .= $body;
$ret .= '</div>' . LF;
$ret .= show_footer();
if ($mod == 'home') $ret .= '</div>' . LF;

// stats
if ($allow_stat) $ret .= get_external_stat();
$ret .= '</body>' . LF;
$ret .= '</html>' . LF;
echo($ret);
?>