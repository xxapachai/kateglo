<?
/**
 * Entry point of application
 */

// constants
define(LF, "\n"); // line break
define(APP_NAME, 'Kateglo'); // application name
define(APP_VERSION, 'v0.0.1'); // application name

// variables
$base_dir = dirname(__FILE__);
$is_post = ($_SERVER['REQUEST_METHOD'] == 'POST');
$title = APP_NAME;
ini_set('include_path', $base_dir . '/pear/');

require_once('config.php');
require_once('messages.php');
require_once('class_db.php');
require_once('class_form.php');
require_once('class_phrase.php');

// initialization
$db = new db;
$db->connect($dsn);
$phrase = new phrase;
$phrase->db = $db;
$phrase->msg = $msg;

// process
if ($is_post) {
	$phrase->save_form();
}

// display
$body .= $phrase->show_search();
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

/**
 * @param $url
 * @return unknown_type
 */
function redir($url)
{
	header('Location:' . $url);
}
?>