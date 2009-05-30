<?
/**
 * Entry point of application
 */

// constants
define(LF, "\n"); // line break
define(APP_NAME, 'Kateglo (Beta) - kamus, tesaurus, dan glosarium bahasa Indonesia'); // application name
define(APP_SHORT, 'Kateglo (Beta)'); // application name
define(APP_VERSION, 'v0.0.12'); // application version. See README.txt

// variables
$base_dir = dirname(__FILE__);
$is_post = ($_SERVER['REQUEST_METHOD'] == 'POST');
ini_set('include_path', $base_dir . '/pear/');
foreach ($_GET as $key => $val)
	$_GET[$key] = trim($val);

// includes
require_once('config.php');
require_once('messages.php');
require_once('common.php');
require_once('Auth.php');
require_once('class_db.php');
require_once('class_form.php');
require_once('class_logger.php');
require_once('class_page.php');

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
	'user'=>'user', 'comment'=>'comment', 'dict'=>'dictionary',
	'glo'=>'glossary', 'home'=>'home', 'doc'=>'doc'
);
$_GET['mod'] = strtolower($_GET['mod']);
if (!array_key_exists($_GET['mod'], $mods)) $_GET['mod'] = 'home';
$mod = $_GET['mod'];

// process
$module = $mods[$mod];
require_once('class_' . $module . '.php');
$page = new $module(&$db, &$auth, $msg);
$page->process();

// display
$body .= show_header();
$body .= $page->show();
$title = ($mod == 'home') ? APP_NAME : APP_SHORT;
if (!$page->title && $mod != 'home')
{
	if ($msg[$module]) $page->title = $msg[$module];
	if ($_GET['phrase']) $page->title = $_GET['phrase'] . ' - ' . $page->title;
}
$title = $page->title ? $page->title . ' - ' . $title : $title;

// render
$ret .= '<html>' . LF;
$ret .= '<head>' . LF;
$ret .= '<title>' . $title . '</title>' . LF;
if ($keywords = $page->get_keywords())
	$ret .= '<meta name="keywords" content="' . $keywords . '" />' . LF;
if ($description = $page->get_description())
	$ret .= '<meta name="description" content="' . $description . '" />' . LF;
$ret .= '<link rel="stylesheet" href="./common.css" type="text/css" />' . LF;
$ret .= '<link rel="icon" href="./images/favicon.ico" type="image/x-icon" />' . LF;
$ret .= '<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />' . LF;
$ret .= '</head>' . LF;
$ret .= '</head>' . LF;
$ret .= $body;
$ret .= sprintf('<p>' .
	'<span style="float:right;">' .
	'<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">' .
	'<img alt="Creative Commons License" style="border-width:0" ' .
	'src="./images/cc-by-nc-sa.png" />' .
	'</a></span>' .
	'<a href="%2$s">%1$s %3$s</a>' .
	'. ' .
	'<a href="%4$s">%5$s</a>' .
	'</p>' . LF,
	APP_SHORT,
	'./?mod=doc&doc=README.txt',
	APP_VERSION,
	'./?mod=comment',
	$msg['comment_link']
);
// stats
if ($allow_stat)
{
	$ret .= '<!-- GoStats JavaScript Based Code -->';
	$ret .= '<script type="text/javascript" src="http://gostats.com/js/counter.js"></script>';
	$ret .= '<script type="text/javascript">_gos=\'gostats.com\';_goa=728945;_got=5;_goi=1;_goz=0;_gol=\'web traffic software\';_GoStatsRun();</script>';
	$ret .= '<noscript><a target="_blank" title="web traffic software" href="http://gostats.com"><img alt="web traffic software" src="http://gostats.com/bin/count/a_728945/t_5/i_1/counter.png"  style="border-width:0" /></a></noscript>';
	$ret .= '<!-- End GoStats JavaScript Based Code -->' . LF;
}
$ret .= '</body>' . LF;
$ret .= '</html>' . LF;
echo($ret);
?>