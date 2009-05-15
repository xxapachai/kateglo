<?php
/**
 * Phrase class
 */
class phrase
{
	var $db;
	var $auth;
	var $msg;

	/**
	 * @return unknown_type
	 */
	function show_phrase()
	{
		global $_GET;

		$phrase = $this->get_phrase();

		// kbbi header
		$ret .= '<table cellpadding="0" cellspacing="0"><tr valign="top"><td width="60%">' . LF;

		// header
		$ret .= sprintf('<h1>%1$s</h1>' . LF, $_GET['phrase']);
		if ($this->auth->checkAuth())
		{
			if ($phrase)
				$ret .= sprintf('<p><a href="%1$s">%2$s</a></p>' . LF,
					sprintf('./?mod=dict&action=form&phrase=%1$s', $_GET['phrase']),
					$this->msg['edit']
				);
			else
			{
				$ret .= sprintf('<p><a href="%1$s">%2$s</a></p>' . LF,
					sprintf('./?mod=dict&action=form&phrase=%1$s', $_GET['phrase']),
					$this->msg['new']
				);
			}
		}

		// found?
		if ($phrase)
		{

			$template = '<tr><td>%1$s:</td><td>%2$s</td></tr>' . LF;
			$ret .= '<table>' . LF;
			$ret .= sprintf($template, $this->msg['lex_class'], $phrase['lex_class_name']);
			$ret .= sprintf($template, $this->msg['etymology'], $phrase['etymology']);
			if ($phrase['root'])
			{
				$ret .= sprintf($template, $this->msg['root_phrase'],
					$this->merge_phrase_list($phrase['root'], 'root_phrase'));
			}
			$ret .= '</table>' . LF;


			// definition
			$ret .= sprintf('<h2>%1$s</h2>' . LF, $this->msg['definition']);
			$defs = $phrase['definition'];
			if ($defs)
			{
				$ret .= '<ol>' . LF;
				foreach ($defs as $def)
				{
					$ret .= sprintf('<li>%2$s%1$s%3$s</li>' . LF,
						$def['def_text'],
						$def['discipline'] ? '<em>(' . $def['discipline'] . ')</em> ' : '',
						$def['sample'] ? ': <em>' . $def['sample'] . '</em> ' : ''
					);
				}
				$ret .= '</ol>' . LF;
			}

			// relation and derivation
			$ret .= $this->show_relation($phrase, 'relation', 'related_phrase');
		}
		else
		{
			$ret .= sprintf('<p>%1$s</p>', sprintf($this->msg['phrase_na'], $_GET['phrase']));
			// derivation and relation
			$this->get_phrase_rd(&$phrase, 'relation', 'rel_type', 'related_phrase', true);
			$ret .= $this->show_relation($phrase, 'relation', 'root_phrase');
		}
		$ret .= $this->show_kbbi();

		return($ret);
	}

	function show_kbbi()
	{
		$kbbi = new kbbi();
		$ret .= '</td><td width="1%">&nbsp;</td><td width="40%" style="background:#EEE; padding: 10px;">' . LF;
		$ret .= sprintf('<h2>%1$s</h2>' . LF, $this->msg['kbbi_ref']);
		$ret .= $kbbi->query($_GET['phrase'], 1) . '</b></i>' . LF;
		$ret .= '</td></tr></table>' . LF;

		return($ret);
	}

	/**
	 * @return unknown_type
	 */
	function show_relation($phrase, $type_name, $col_name)
	{
		// derivation
		$type_count = count($phrase[$type_name]);
		$col_width = round(100 / $type_count) . '%';
		$ret .= sprintf('<h2>%1$s</h2>' . LF, $this->msg['thesaurus']);
		$ret .= '<ol>' . LF;
		foreach ($phrase[$type_name] as $type_key => $type)
		{
			$ret .= sprintf('<li><strong>%1$s:</strong> ', $type['name']);
			$ret .= $this->merge_phrase_list($type, $col_name, count($type) - 1);
			$ret .= '</li>' . LF;
		}
		$ret .= '</ol>' . LF;
		return($ret);
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
				$ret .= sprintf('<a href="./?mod=dict&phrase=%1$s">%1$s</a>', $phrases[$i][$col_name]);
				$ret .= ($i < $count - 1) ? ', ': '';
			}
		}
		else
		{
			$ret = '-';
		}
		return($ret);
	}

	/**
	 * Show form
	 */
	function show_form()
	{
		global $_GET;
		$phrase = $this->get_phrase();
		if (!$phrase) $is_new = true;
		$url = './?mod=dict&action=form&phrase=' . ($phrase ? $_GET['phrase'] : '') . '';
		if ($is_new) $phrase['phrase'] = $_GET['phrase'];

		$form = new form('phrase_form', null, $url);
		$form->setup($this->msg);

		// main elements
		$form->addElement('text', 'phrase', $this->msg['phrase'],
			array('size' => 40, 'maxlength' => '255'));
		$form->addElement('select', 'lex_class', $this->msg['lex_class'],
			$this->db->get_row_assoc('SELECT * FROM lexical_class', 'lex_class', 'lex_class_name'));
		$form->addElement('text', 'etymology', $this->msg['etymology'],
			array('size' => 40, 'maxlength' => '255'));
		$form->addElement('submit', 'save', $this->msg['save']);
		$form->addRule('phrase', sprintf($this->msg['required_alert'], $this->msg['phrase']), 'required', null, 'client');
		$form->addRule('lex_class', sprintf($this->msg['required_alert'], $this->msg['lex_class']), 'required', null, 'client');
		$form->setDefaults($phrase);
		$ret .= $form->begin_form();
		$title = !$is_new ? $phrase['phrase'] :
			($_GET['phrase'] ? $_GET['phrase'] : $this->msg['new_flag']);
		$template = '<tr><td>%1$s:</td><td>%2$s</td></tr>' . LF;


		// kbbi header
		$ret .= '<table cellpadding="0" cellspacing="0"><tr valign="top"><td width="60%">' . LF;

		// header
		$ret .= sprintf('<h1>%1$s</h1>' . LF, $title);
		$ret .= sprintf('<p><a href="%1$s">%2$s</a></p>' . LF,
			'./?mod=dict' . ($is_new ? '' : '&phrase=' . $_GET['phrase']), $this->msg['cancel']);
		$ret .= '<table>' . LF;
		$ret .= sprintf($template, $this->msg['phrase'], $form->get_element('phrase'));
		$ret .= sprintf($template, $this->msg['lex_class'], $form->get_element('lex_class'));
		$ret .= sprintf($template, $this->msg['etymology'], $form->get_element('etymology'));
		$ret .= '</table>' . LF;

		// definition
		$ret .= $this->show_sub_form(&$form, &$phrase,
			array(
				'def_uid' => array('type' => 'hidden'),
				'def_num' => array('type' => 'text', 'width' => '1%',
					'option1' => array('size' => 3, 'maxlength' => 5)),
				'def_text' => array('type' => 'text', 'width' => '50%',
					'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%')),
				'sample' => array('type' => 'text', 'width' => '50%',
					'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%')),
				'discipline' => array('type' => 'select', 'width' => '1%',
					'option1' => $this->db->get_row_assoc('SELECT * FROM discipline', 'discipline', 'discipline_name')),
				),
			'definition', 'definition', 'definition', 'def_count');

		// relation
		$ret .= $this->show_sub_form(&$form, &$phrase,
			array(
				'rel_uid' => array('type' => 'hidden'),
				'rel_type' => array('type' => 'select', 'width' => '1%',
					'option1' => $this->db->get_row_assoc('SELECT * FROM relation_type', 'rel_type', 'rel_type_name')),
				'related_phrase' => array('type' => 'text', 'width' => '99%',
					'option1' => array('size' => 50, 'maxlength' => 255, 'style' => 'width:100%'))),
			'relation', 'thesaurus', 'all_relation', 'rel_count');

		// end
		$form->applyFilter('__ALL__', 'trim');
		$ret .= $def_hidden;
		$ret .= '<input name="is_new" type="hidden" value="' . $is_new . '" />' . LF;
		$ret .= sprintf('<p>%1$s</p>', $form->get_element('save'));
		$ret .= $form->end_form();
		//var_dump($form->toArray());
		//die();

		// kbbi definition
		$ret .= $this->show_kbbi();

		return($ret);
	}

	/**
	 * @return unknown_type
	 */
	function show_sub_form(&$form, &$phrase, $field_def, $name, $heading, $phrase_field, $count_name)
	{
		// definition
		$hidden_field = '';
		$defs = &$phrase[$phrase_field];
		$new_def = $field_def;
		foreach ($new_def as $key => $val) $new_def[$key] = '';
		$defs[] = $new_def;
		$defs[] = $new_def;
		$def_count = count($defs);
		$ret .= sprintf('<h2>%1$s</h2>' . LF, $this->msg[$heading]);
		$ret .= '<table>' . LF;
		if ($defs)
		{
			for ($i = 0; $i < $def_count; $i++)
			{
				$def = $defs[$i];
				$ret .= '<tr>' . LF;
				foreach ($field_def as $field_key => $field)
				{
					$field_name = $field_key . '_' . $i;
					$form->addElement($field['type'], $field_name, $this->msg['number'], $field['option1']);
					$form->setDefaults(array($field_name => $defs[$i][$field_key]));
					if ($field['type'] != 'hidden')
					{
						$ret .= sprintf('<td width="%2$s">%1$s</td>' . LF,
							$form->get_element($field_name), $field['width']);
					}
					else
					{
						$hidden_field .= $form->get_element($field_name) . LF;
					}
				}
				$ret .= '</tr>' . LF;
			}
		}
		$hidden_field .= '<input name="' . $count_name . '" type="hidden" value="' . $def_count . '" />' . LF;
		$ret .= '</table>' . LF;
		$ret .= $hidden_field;
		return($ret);
	}

	/**
	 * Get phrase
	 *
	 * @return Phrase structure
	 */
	function get_phrase()
	{
		global $_GET;
		// phrase
		$query = sprintf('SELECT a.*, b.lex_class_name FROM phrase a, lexical_class b
			WHERE a.lex_class = b.lex_class AND a.phrase = %1$s',
			$this->db->quote($_GET['phrase']));
		$phrase = $this->db->get_row($query);
		if ($phrase)
		{
			// definition
			$query = sprintf('SELECT a.*, b.discipline_name
				FROM definition a LEFT JOIN discipline b
				ON a.discipline = b.discipline
				WHERE a.phrase = %1$s
				ORDER BY a.def_num, a.def_uid',
				$this->db->quote($_GET['phrase']), $this->db->quote($class_name));
			$rows = $this->db->get_rows($query);
			$phrase['definition'] = $rows;

			// derivation and relation
			$this->get_phrase_rd(&$phrase, 'relation', 'rel_type', 'related_phrase');
		}
		return($phrase);
	}

	/**
	 * @return unknown_type
	 */
	function get_phrase_rd(&$phrase, $table, $key_field, $sort_phrase, $reverse = false)
	{
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
			$this->db->quote($_GET['phrase']),
			$table,
			$key_field,
			$sort_phrase,
			$where_field);
		$rows = $this->db->get_rows($query);
		$query = sprintf('SELECT %2$s, %2$s_name FROM %1$s_type ORDER BY sort_order',
			$table, $key_field);
		$types = $this->db->get_rows($query);
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
				$this->db->quote($old_key),
				$this->db->quote($new_key),
				$this->db->quote($_POST['etymology']),
				$this->db->quote($_POST['lex_class'])
			);
		}
		else
		{
			$query = sprintf('INSERT INTO phrase (phrase, etymology, lex_class)
				VALUES (%1$s, %2$s, %3$s)',
				$this->db->quote($new_key),
				$this->db->quote($_POST['etymology']),
				$this->db->quote($_POST['lex_class'])
			);
		}
		//die($query);
		$this->db->exec($query);

		$this->save_sub_form('definition', 'def_uid', 'def_count', 'phrase',
			array('def_num', 'def_text'), array('discipline', 'sample'));
		$this->save_sub_form('relation', 'rel_uid', 'rel_count', 'root_phrase',
			array('rel_type', 'related_phrase'));

		redir('./?mod=dict&phrase=' . $_POST['phrase']);
	}

	/**
	 * @return unknown_type
	 */
	function save_sub_form($table, $uid, $count_field, $phrase_field, $required, $optional = null)
	{
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
				$sql_value .= ' , ' . $this->db->quote($value);
				$sql_update .=  ' , ' . $field . ' = ' . $this->db->quote($value);
			}
			// if not empty, update or add new
			if (!$is_empty)
			{
				if ($_POST[$posted_uid])
				{
					$sub_query = sprintf(
						'UPDATE %1$s SET %5$s = %3$s %4$s WHERE %6$s = %2$s;',
						$table,
						$this->db->quote($_POST[$posted_uid]),
						$this->db->quote($_POST['phrase']),
						$sql_update,
						$phrase_field,
						$uid
					);
					//echo($sub_query . '<br />');
					$this->db->exec($sub_query);
				}
				else
				{
					$sub_query = sprintf('INSERT INTO %1$s (%3$s %4$s)
						VALUES (%2$s %5$s);',
						$table,
						$this->db->quote($_POST['phrase']),
						$phrase_field,
						$sql_field,
						$sql_value
					);
					//echo($sub_query . '<br />');
					$this->db->exec($sub_query);
				}
			}
			// if empty, delete
			else
			{
				if ($_POST[$posted_uid] != '')
				{
					$sub_query = sprintf('DELETE FROM %1$s WHERE %3$s = %2$s;',
						$table, $this->db->quote($_POST[$posted_uid]), $uid);
					//echo($sub_query . '<br />');
					$this->db->exec($sub_query);
				}
			}
		}
	}
};
?>