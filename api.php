<?
/**
 * API entry point
 */

// base dir
$base_dir = dirname(__FILE__);
ini_set('include_path', $base_dir . '/pear/');

// includes
require_once($base_dir . '/config/settings.php');
require_once($base_dir . '/config/config.php');
require_once($base_dir . '/config/messages.php');
require_once('common.php');
require_once($base_dir . '/classes/class_db.php');
require_once($base_dir . '/classes/class_page.php');

// initialization
$db = new db;
$db->connect($dsn);
$db->msg = $msg;

// define mod
$mods = array(
	'dictionary'
);
$_GET['mod'] = strtolower($_GET['mod']);
if ($_GET['mod'] == 'dict') $_GET['mod'] = 'dictionary'; // backward
if (!in_array($_GET['mod'], $mods)) $_GET['mod'] = 'dictionary';
$mod = $_GET['mod'];

// shortcut
$_GET['mod'] = 'dictionary';
$_GET['action'] = 'view';

// process
require_once($base_dir . '/modules/class_' . $mod . '.php');
$page = new $mod(&$db, &$auth, $msg);
$page->process();
if ($apiData = $page->getAPI())
{
	$ret .= '<?xml version="1.0"?>' . LF;
	$ret .= '<api status="' . ($apiData ? 1 : 0) . '">' . LF;
	if ($apiData) $ret .= arrayToXML(&$apiData);
	$ret .= '</api>' . LF;
	header('Content-type: text/xml');
}
else
{
	$ret = 'Ini adalah API sederhana untuk Kateglo hanya untuk mengakses kamus. Gunakan dengan format seperti http://bahtera.org/kateglo/api.php?phrase=lema_yang_dicari. Silakan pelajari sendiri keluaran XML yang dihasilkan.';
}
echo($ret);

/**
 * Array to XML
 */
function arrayToXML(&$array)
{
	foreach ($array as $key => $value)
	{
		$keyName = is_numeric($key) ? 'elm' . $key : $key;
		if (!is_array($value))
		{
			$ret .= sprintf('<%1$s>%2$s</%1$s>', $keyName, $value) . LF;
		}
		else
		{
			$ret .= sprintf('<%1$s>', $keyName) . LF;
			$ret .= arrayToXML(&$value);
			$ret .= sprintf('</%1$s>', $keyName) . LF;
		}
	}
	return($ret);
}
?>