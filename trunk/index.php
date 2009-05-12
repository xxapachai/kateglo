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

// connect to database
$db = new db;
$db->connect($dsn);

// process
if ($is_post) {
	save_form();
}

// display
$body .= show_search();
if ($_GET['action'] == 'form')
	$body .= show_form();
else
{
	if ($_GET['phrase'])
		$body .= show_phrase();
	else
	{
		$body .= str_replace("\n", '<br />', file_get_contents('./docs/README.txt'));
	}
}
if ($_GET['phrase']) $title = $_GET['phrase'] . ' - ' . $title;

// render
$return .= '<html>' . LF;
$return .= '<head>' . LF;
$return .= '<title>' . $title . '</title>' . LF;
$return .= '<link rel="stylesheet" href="./common.css" type="text/css" />' . LF;
$return .= '</head>' . LF;
$return .= '<body>' . LF;
$return .= $body;
$return .= sprintf('<p align="right"><a href="%2$s">%1$s %3$s</a></p>' . LF,
	APP_NAME, './docs/README.txt', APP_VERSION);
$return .= '</body>' . LF;
$return .= '</html>' . LF;
echo($return);

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
		$return .= sprintf('<h1>%1$s</h1>' . LF, $phrase['phrase']);
		//$return .= sprintf('<p><a href="%1$s">%2$s</a> <a href="%3$s">%4$s</a></p>' . LF,
		//	sprintf('./?phrase=%1$s&action=form', $_GET['phrase']), $msg['edit'],
		//	sprintf('./?phrase=%1$s&action=delete', $_GET['phrase']), $msg['delete']);
		$return .= sprintf('<p><a href="%1$s">%2$s</a></p>' . LF,
			sprintf('./?phrase=%1$s&action=form', $_GET['phrase']), $msg['edit']);
		$template = '<tr><td>%1$s:</td><td>%2$s</td></tr>' . LF;
		$return .= '<table>' . LF;
		$return .= sprintf($template, $msg['lex_class'], $phrase['lex_class_name']);
		$return .= sprintf($template, $msg['etymology'], $phrase['etymology']);
		if ($phrase['root'])
		{
			$return .= sprintf($template, $msg['root_phrase'],
				merge_phrase_list($phrase['root'], 'root_phrase'));
		}
		$return .= '</table>' . LF;

		// definition
		$return .= sprintf('<h2>%1$s</h2>' . LF, $msg['definition']);
		$defs = $phrase['definition'];
		if ($defs)
		{
			$return .= '<ol>' . LF;
			foreach ($defs as $def)
			{
				$return .= sprintf('<li>%2$s%1$s%3$s</li>' . LF,
					$def['def_text'],
					$def['discipline'] ? '<em>(' . $def['discipline'] . ')</em> ' : '',
					$def['sample'] ? ': <em>' . $def['sample'] . '</em> ' : ''
				);
			}
			$return .= '</ol>' . LF;
		}

		// relation and derivation
		$return .= show_phrase_rd($phrase, 'relation', 'related_phrase');
		$return .= show_phrase_rd($phrase, 'derivation', 'derived_phrase');
	}
	else
	{
		// derivation and relation
		get_phrase_rd(&$phrase, 'derivation', 'drv_type', 'derived_phrase', true);
		get_phrase_rd(&$phrase, 'relation', 'rel_type', 'related_phrase', true);
		$return .= sprintf('<h1>%1$s</h1>' . LF, $_GET['phrase']);
		$return .= sprintf('<p>%1$s</p>', sprintf($msg['phrase_na'], $_GET['phrase']));
		$return .= show_phrase_rd($phrase, 'relation', 'root_phrase');
		$return .= show_phrase_rd($phrase, 'derivation', 'root_phrase');
	}
	return($return);
}

/**
 * @return unknown_type
 */
function show_phrase_rd($phrase, $type_name, $col_name)
{
	global $msg;
	// derivation
	$type_count = count($phrase[$type_name]);
	$col_width = round(100 / $type_count) . '%';
	$return .= sprintf('<h2>%1$s</h2>' . LF, $msg[$type_name]);
	$return .= '<ol>' . LF;
	foreach ($phrase[$type_name] as $type_key => $type)
	{
		$return .= sprintf('<li><strong>%1$s:</strong> ', $type['name']);
		$return .= merge_phrase_list($type, $col_name, count($type) - 1);
		$return .= '</li>' . LF;
	}
	$return .= '</ol>' . LF;
	return($return);
}

/**
 * Merge phrase list with comma
 */
function merge_phrase_list($phrases, $col_name, $count = null)
{
	if (is_null($count)) $count = count($phrases);
	if ($count > 0)
	{
		for ($i = 0; $i < $count; $i++)
		{
			$return .= sprintf('<a href="./?phrase=%1$s">%1$s</a>', $phrases[$i][$col_name]);
			$return .= ($i < $count - 1) ? ', ': '';
		}
	}
	else
	{
		$return = '-';
	}
	return($return);
}

/**
 * @return unknown_type
 */
function show_form()
{
	global $msg, $db;
	global $_GET;
	$phrase = get_phrase();
	if (!$phrase) $is_new = true;
	$url = './?phrase=' . ($phrase ? $_GET['phrase'] : '') . '&action=form';
	if ($is_new) $phrase['phrase'] = $_GET['phrase'];

	$form = new form('phrase_form', null, $url);
	$form->setup();

	// main elements
	$form->addElement('text', 'phrase', $msg['phrase'],
		array('size' => 40, 'maxlength' => '255'));
	$form->addElement('select', 'lex_class', $msg['lex_class'],
		$db->get_row_assoc('SELECT * FROM lexical_class', 'lex_class', 'lex_class_name'));
	$form->addElement('text', 'etymology', $msg['etymology'],
		array('size' => 40, 'maxlength' => '255'));
	$form->addElement('submit', 'save', $msg['save']);
	$form->addRule('phrase', sprintf($msg['required_alert'], $msg['phrase']), 'required', null, 'client');
	$form->addRule('lex_class', sprintf($msg['required_alert'], $msg['lex_class']), 'required', null, 'client');
	$form->setDefaults($phrase);
	$return .= $form->beginForm();
	$title = !$is_new ? $phrase['phrase'] :
		($_GET['phrase'] ? $_GET['phrase'] : $msg['new_flag']);
	$return .= sprintf('<h1>%1$s</h1>', $title);
	$return .= sprintf('<p><a href="%1$s">%2$s</a></p>',
		$is_new ? './' : './?phrase=' . $_GET['phrase'], $msg['cancel']);
	$template = '<table>
		<tr><td>%1$s:</td><td>%2$s</td></tr>
		<tr><td>%3$s:</td><td>%4$s</td>
		<tr><td>%5$s:</td><td>%6$s</td>
		</tr></table>';
	$return .= sprintf($template,
		$msg['phrase'], $form->getElementHtml('phrase'),
		$msg['lex_class'], $form->getElementHtml('lex_class'),
		$msg['etymology'], $form->getElementHtml('etymology'),
		LF);

	// definition
	$return .= show_sub_form(&$form, &$phrase,
		array(
			'def_uid' => array('type' => 'hidden'),
			'def_num' => array('type' => 'text', 'width' => '1%',
				'option1' => array('size' => 3, 'maxlength' => 5)),
			'discipline' => array('type' => 'select', 'width' => '1%',
				'option1' => $db->get_row_assoc('SELECT * FROM discipline', 'discipline', 'discipline_name')),
			'def_text' => array('type' => 'text', 'width' => '50%',
				'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%')),
			'sample' => array('type' => 'text', 'width' => '50%',
				'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%'))),
		'definition', 'definition', 'def_count');

	// relation
	$return .= show_sub_form(&$form, &$phrase,
		array(
			'rel_uid' => array('type' => 'hidden'),
			'rel_type' => array('type' => 'select', 'width' => '1%',
				'option1' => $db->get_row_assoc('SELECT * FROM relation_type', 'rel_type', 'rel_type_name')),
			'related_phrase' => array('type' => 'text', 'width' => '99%',
				'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%'))),
		'relation', 'all_relation', 'rel_count');

	// derivation
	$return .= show_sub_form(&$form, &$phrase,
		array(
			'drv_uid' => array('type' => 'hidden'),
			'drv_type' => array('type' => 'select', 'width' => '1%',
				'option1' => $db->get_row_assoc('SELECT * FROM derivation_type', 'drv_type', 'drv_type_name')),
			'derived_phrase' => array('type' => 'text', 'width' => '99%',
				'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%'))),
		'derivation', 'all_derivation', 'drv_count');


	// end
	$form->applyFilter('__ALL__', 'trim');
	$return .= $def_hidden;
	$return .= '<input name="is_new" type="hidden" value="' . $is_new . '" />' . LF;
	$return .= sprintf('<p>%1$s</p>', $form->getElementHtml('save'));
	$return .= $form->endForm();
	//var_dump($form->toArray());
	//die();
	return($return);
}

/**
 * @return unknown_type
 */
function show_sub_form(&$form, &$phrase, $field_def, $name, $phrase_field, $count_name)
{
	global $msg;
	// definition
	$hidden_field = '';
	$defs = &$phrase[$phrase_field];
	$new_def = $field_def;
	foreach ($new_def as $key => $val) $new_def[$key] = '';
	$defs[] = $new_def;
	$defs[] = $new_def;
	$def_count = count($defs);
	$return .= sprintf('<h2>%1$s</h2>' . LF, $msg[$name]);
	$return .= '<table>' . LF;
	if ($defs)
	{
		for ($i = 0; $i < $def_count; $i++)
		{
			$def = $defs[$i];
			$return .= '<tr>' . LF;
			foreach ($field_def as $field_key => $field)
			{
				$field_name = $field_key . '_' . $i;
				$form->addElement($field['type'], $field_name, $msg['number'], $field['option1']);
				$form->setDefaults(array($field_name => $defs[$i][$field_key]));
				if ($field['type'] != 'hidden')
				{
					$return .= sprintf('<td width="%2$s">%1$s</td>' . LF,
						$form->getElementHtml($field_name), $field['width']);
				}
				else
				{
					$hidden_field .= $form->getElementHtml($field_name) . LF;
				}
			}
			$return .= '</tr>' . LF;
		}
	}
	$hidden_field .= '<input name="' . $count_name . '" type="hidden" value="' . $def_count . '" />' . LF;
	$return .= '</table>' . LF;
	$return .= $hidden_field;
	return($return);
}

/**
 * @return Search form HTML
 */
function show_search()
{
	global $msg;
	$form = new form('search_form', 'get');
	$form->setup();
	$form->addElement('text', 'phrase', $msg['enter_phrase']);
	$form->addElement('submit', 'search', $msg['search']);
	$return .= sprintf('<span style="float:right;"><a href="%2$s">%1$s</a></span>', $msg['home'], './');
	$return .= $form->beginForm();
	$return .= $form->getElementHtml('phrase');
	$return .= $form->getElementHtml('search');
	// $return .= sprintf('<a href="%2$s">%1$s</a>' . LF,
	//	$msg['add_phrase'], './?action=form');
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
	$query = sprintf('SELECT a.*, b.lex_class_name FROM phrase a, lexical_class b
		WHERE a.lex_class = b.lex_class AND a.phrase = %1$s',
		$db->_db->quote($_GET['phrase']));
	$phrase = $db->get_row($query);
	if ($phrase)
	{
		// definition
		$query = sprintf('SELECT a.*, b.discipline_name
			FROM definition a LEFT JOIN discipline b
			ON a.discipline = b.discipline
			WHERE a.phrase = %1$s
			ORDER BY a.def_num, a.def_uid',
			$db->_db->quote($_GET['phrase']), $db->_db->quote($class_name));
		$rows = $db->get_rows($query);
		$phrase['definition'] = $rows;

		// derivation and relation
		get_phrase_rd(&$phrase, 'derivation', 'drv_type', 'derived_phrase');
		get_phrase_rd(&$phrase, 'relation', 'rel_type', 'related_phrase');

		// root phrase
		$query = sprintf('SELECT a.*
			FROM derivation a
			WHERE a.derived_phrase = %1$s
			ORDER BY a.root_phrase',
			$db->_db->quote($_GET['phrase']));
		$rows = $db->get_rows($query);
		$phrase['root'] = $rows;
	}
	return($phrase);
}

/**
 * @return unknown_type
 */
function get_phrase_rd(&$phrase, $table, $key_field, $sort_phrase, $reverse = false)
{
	global $db;
	global $_GET;
	$where_field = 'root_phrase';
	if ($reverse)
	{
		$temp = $sort_phrase;
		$sort_phrase = $where_field;
		$where_field = $temp;
	}
	// relation
	$query = sprintf('SELECT a.*, b.%3$s_name
		FROM %2$s a, %2$s_type b
		WHERE a.%3$s = b.%3$s AND a.%5$s = %1$s
		ORDER BY b.sort_order, a.%4$s',
		$db->_db->quote($_GET['phrase']),
		$table,
		$key_field,
		$sort_phrase,
		$where_field);
	$rows = $db->get_rows($query);
	$query = sprintf('SELECT %2$s, %2$s_name FROM %1$s_type ORDER BY sort_order',
		$table, $key_field);
	$types = $db->get_rows($query);
	// divide into each category
	foreach ($types as $type)
	{
		$type_key = $type[$key_field];
		foreach ($rows as $row)
			if ($row[$key_field] == $type[$key_field])
				$phrase[$table][$type_key][] = $row;
		$phrase[$table][$type_key]['name'] = $type[$key_field . '_name'];
	}
	// bulk
	foreach ($rows as $row)
		$phrase['all_' . $table][] = $row;
}

/**
 * Save phrase update
 *
 * @return unknown_type
 */
function save_form()
{
	global $db;
	global $_GET, $_POST;
	$is_new = ($_POST['is_new'] == 1);
	$old_key = $_GET['phrase'];
	$new_key = $_POST['phrase'];
	// main
	if (!$is_new)
	{
		$query = sprintf(
			'UPDATE phrase SET
				phrase = %2$s,
				etymology = %3$s,
				lex_class = %4$s
			WHERE phrase = %1$s',
			$db->_db->quote($old_key),
			$db->_db->quote($new_key),
			$db->_db->quote($_POST['etymology']),
			$db->_db->quote($_POST['lex_class'])
		);
	}
	else
	{
		$query = sprintf('INSERT INTO phrase (phrase, etymology, lex_class)
			VALUES (%1$s, %2$s, %3$s)',
			$db->_db->quote($new_key),
			$db->_db->quote($_POST['etymology']),
			$db->_db->quote($_POST['lex_class'])
		);
	}
	//die($query);
	$db->exec($query);

	save_sub_form('definition', 'def_uid', 'def_count', 'phrase',
		array('def_num', 'def_text'), array('discipline', 'sample'));
	save_sub_form('relation', 'rel_uid', 'rel_count', 'root_phrase',
		array('rel_type', 'related_phrase'));
	save_sub_form('derivation', 'drv_uid', 'drv_count', 'root_phrase',
		array('drv_type', 'derived_phrase'));

	redir('./?phrase=' . $_POST['phrase']);
}

/**
 * @return unknown_type
 */
function save_sub_form($table, $uid, $count_field, $phrase_field, $required, $optional = null)
{
	global $db;
	global $_GET, $_POST;
	$sub_item = $_POST[$count_field];
	$sub_query = '';
	for ($i = 0; $i < $sub_item; $i++)
	{
		$sql_field = '';
		$sql_value = '';
		$sql_update = '';
		$posted_uid = $uid . '_' . $i;
		// check if any of the fields are empty
		$is_empty = false;
		$fields = $required;
		if ($optional) $fields = array_merge($required, $optional);
		foreach ($fields as $field)
		{
			$value = $_POST[$field . '_' . $i];
			if (!$value && in_array($field, $required)) $is_empty = true;
			$sql_field .= ' , ' . $field;
			$sql_value .= ' , ' . $db->_db->quote($value);
			$sql_update .=  ' , ' . $field . ' = ' . $db->_db->quote($value);
		}
		// if not empty, update or add new
		if (!$is_empty)
		{
			if ($_POST[$posted_uid])
			{
				$sub_query = sprintf(
					'UPDATE %1$s SET %5$s = %3$s %4$s WHERE %6$s = %2$s;',
					$table,
					$db->_db->quote($_POST[$posted_uid]),
					$db->_db->quote($_POST['phrase']),
					$sql_update,
					$phrase_field,
					$uid
				);
				//echo($sub_query . '<br />');
				$db->exec($sub_query);
			}
			else
			{
				$sub_query = sprintf('INSERT INTO %1$s (%3$s %4$s)
					VALUES (%2$s %5$s);',
					$table,
					$db->_db->quote($_POST['phrase']),
					$phrase_field,
					$sql_field,
					$sql_value
				);
				//echo($sub_query . '<br />');
				$db->exec($sub_query);
			}
		}
		// if empty, delete
		else
		{
			if ($_POST[$posted_uid] != '')
			{
				$sub_query = sprintf('DELETE FROM %1$s WHERE %3$s = %2$s;',
					$table, $db->_db->quote($_POST[$posted_uid]), $uid);
				//echo($sub_query . '<br />');
				$db->exec($sub_query);
			}
		}
	}
}

/**
 * @param $url
 * @return unknown_type
 */
function redir($url)
{
	header('Location:' . $url);
}
?>