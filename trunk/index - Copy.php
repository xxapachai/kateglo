<?
/**
 * @author ivan@lanin.org
 *
 */
$base_dir = dirname(__FILE__);
ini_set('include_path', $base_dir . '/pear/');
define(LF, "\n");

require_once('config.php');
require_once('class_db.php');
require_once('class_form.php');

// variables
$phrase = $_GET['phrase'];
$operator = $_GET['operator'];
$action = $_GET['action'];
$is_post = ($_SERVER['REQUEST_METHOD'] == 'POST');

// connect to database
$db = new db;
$db->connect($dsn);

// display accordingly
if ($is_post) {
	save_phrase();
}
$html .= '<link rel="stylesheet" href="./common.css" type="text/css" />
';
$html .= show_search();
if ($_GET['action'] == 'form')
{
	$html .= show_form();
}
else
{
	$html .= show_phrase();
}

echo($html);

/**
 * @return unknown_type
 */
function show_phrase()
{
	global $db, $msg;
	global $_GET;
	$phrase = get_phrase();
	//var_dump($phrase);
	//die();
	if ($phrase)
	{
		/*
		$return .= sprintf(
			'<h1>%1$s [<a href="%4$s">%2$s</a>] [<a href="%5$s">%3$s</a>]</h1>',
			$phrase['phrase'],
			$msg['edit'],
			$msg['delete'],
			'?phrase=' . $phrase['phrase'] . '&action=form',
			'?phrase=' . $phrase['phrase'] . '&action=delete'
			);
		*/
		//  class="h3">
		$return .= sprintf(
			'<h1>%1$s</h1>',
			$phrase['phrase'],
			$msg['edit'],
			$msg['delete'],
			'?phrase=' . $phrase['phrase'] . '&action=form',
			'?phrase=' . $phrase['phrase'] . '&action=delete'
			);
		$return .= $phrase['etymology'];
		// definition
		if ($phrase['class'])
		{
			$col_width = '25%';
			foreach($phrase['class'] as $class_name => $class)
			{
				$return .= sprintf('<h2>%1$s</h2>', $class['lex_class_name']);
				$defs = $phrase['class'][$class_name]['def'];
				if ($defs) {
					$return .= '<ol>' . LF;
					foreach ($defs as $def)
					{
						$return .= '<li>' . LF;
						$return .= $def['def_text'] . LF;
						// related words & translation
						$return .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin: 5px 0px;">' . LF;
						$return .= '<tr valign="top"><td width="' . $col_width . '">' . LF;
						$return .= '<p class="h3">Sinonim</p>' . LF;
						$return .= '</td><td width="' . $col_width . '">' . LF;
						$return .= '<p class="h3">Antonim</p>' . LF;
						$return .= '</td><td width="' . $col_width . '">' . LF;
						$return .= '<p class="h3">Kata terkait</p>' . LF;
						$return .= '</td><td width="' . $col_width . '">' . LF;
						$return .= '<p class="h3">Terjemahan</p>' . LF;
						$return .= '</td></tr></table>' . LF;
						$return .= '</li>' . LF;
					}
					$return .= '</ol>' . LF;
				}
			}
		}
		// derivation
		$dtype_count = count($phrase['derivation']);
		$col_width = round(100 / $dtype_count) . '%';
		$return .= sprintf('<h2>%1$s</h2>', $msg['derivation']);
		$return .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin: 5px 0px;">' . LF;
		$return .= '<tr valign="top">' . LF;
		foreach ($phrase['derivation'] as $dtype_key => $dtype)
		{
			$count = count($dtype) - 1;
			$return .= '<td width="' . $col_width . '">' . LF;
			$return .= '<h3>' . $dtype['name'] . '</h3>' . LF;
			if ($count > 0)
			{
				$return .= '<ol>';
				for ($i=0; $i<$count; $i++)
				{
					$return .= sprintf('<li>%1$s</li>', $dtype[$i]['derived_phrase']);
				}
				$return .= '</ol>';
			}
			$return .= '</td>' . LF;
		}
		$return .= '</tr></table>' . LF;
	}
	return($return);
}

/**
 * @return unknown_type
 */
function show_form()
{
	global $msg;
	global $_GET;
	$phrase = get_phrase();
	$url = './?phrase=' . ($phrase ? $_GET['phrase'] : '') . '&action=form';
	$form = new form('phrase_form', null, $url);
	$form->addElement('text', 'phrase', $msg['phrase']);
	$form->addElement('text', 'etymology', $msg['etymology']);
	$form->addElement('text', 'actual_phrase', $msg['actual_phrase']);
	$form->addElement('submit', 'save', $msg['save']);
	if ($phrase)
	{
		$return .= sprintf('<h2>%1$s</h2>', $phrase['phrase']);
		$form->setDefaults($phrase);
	}
	$return .= $form->toHtml();
	return($return);
}

/**
 * @return Search form HTML
 */
function show_search()
{
	global $msg;
	$form = new form('search_form', 'get');
	$form->addElement('text', 'phrase', $msg['enter_phrase']);
	$form->addElement('submit', 'search', $msg['search']);
	$return .= $form->beginForm();
	$return .= $form->getElementHtml('phrase');
	$return .= $form->getElementHtml('search');
	$return .= sprintf('<a href="%2$s">%1$s</a>' . LF,
		$msg['add_phrase'], './?action=form');
	$return .= $form->endForm();
	return($return);
}

/**
 * Get phrase
 * 
 * @return Phrase structure
 */
function get_phrase()
{
	global $db;
	global $_GET;
	// phrase
	$query = sprintf('SELECT * FROM phrase WHERE phrase = %1$s',
		$db->_db->quote($_GET['phrase']));
	$phrase = $db->get_row($query);
	if ($phrase)
	{
		// phrase class
		$query = sprintf('SELECT a.*, b.lex_class_name
			FROM phrase_class a, lexical_class b
			WHERE a.lex_class = b.lex_class AND phrase = %1$s
			ORDER BY b.sort_order',
			$db->_db->quote($_GET['phrase']));
		$classes = $db->get_rows($query);
		foreach($classes as $class)
		{
			$class_name = $class['lex_class'];
			$phrase['class'][$class_name] = $class;
			// definition
			$query = sprintf('SELECT * FROM phrase_def WHERE phrase = %1$s AND lex_class = %2$s',
				$db->_db->quote($_GET['phrase']), $db->_db->quote($class_name));
			$defs = $db->get_rows($query);
			$phrase['class'][$class_name]['def'] = $defs;
			foreach($defs as $def)
			{
			}
		}
		// derivation
		$query = sprintf('SELECT a.*, b.drv_type_name
			FROM derivation a, derivation_type b
			WHERE a.drv_type = b.drv_type AND a.root_phrase = %1$s
			ORDER BY b.sort_order',
			$db->_db->quote($_GET['phrase']));
		$derivation = $db->get_rows($query);
		$query = 'SELECT drv_type, drv_type_name FROM derivation_type ORDER BY sort_order';
		$types = $db->get_rows($query);
		foreach ($types as $type)
		{
			$type_key = $type['drv_type'];
			foreach ($derivation as $drv)
			{
				if ($drv['drv_type'] == $type['drv_type'])
				{
					$phrase['derivation'][$type_key][] = $drv;
				}
			}
			$phrase['derivation'][$type_key]['name'] = $type['drv_type_name'];
		}
	}
	return($phrase);
}

/**
 * Save phrase update
 * 
 * @return unknown_type
 */
function save_phrase()
{
	global $db;
	global $_GET, $_POST;
	if ($_GET['phrase'])
	{
		$query = sprintf(
			'UPDATE phrase SET
				phrase = %2$s,
				etymology = %3$s,
				actual_phrase = %4$s
			WHERE phrase = %1$s',
			$db->_db->quote($_GET['phrase']),
			$db->_db->quote($_POST['phrase']),
			$db->_db->quote($_POST['etymology']),
			$db->_db->quote($_POST['actual_phrase'])
			);
	}
	else
	{
		$query = sprintf('INSERT INTO phrase (phrase, etymology, actual_phrase)
			VALUES (%1$s, %2$s, %3$s)',
			$db->_db->quote($_POST['phrase']),
			$db->_db->quote($_POST['etymology']),
			$db->_db->quote($_POST['actual_phrase'])
		);
	}
	//die($query);
	$db->exec($query);
	redir('./?phrase=' . $_POST['phrase']);
}

function redir($url)
{
	header('Location:' . $url);
}
?>