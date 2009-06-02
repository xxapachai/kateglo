<?php
/**
 *
 *
 *
 */
class glossary extends page
{
	var $db;
	var $auth;
	var $msg;
	var $entry;
	var $sublist = false;

	/**
	 * Constructor
	 */
	function glossary(&$db, &$auth, $msg)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->msg = $msg;
	}

	/**
	 *
	 */
	function process()
	{
		global $_GET;
		global $_SERVER;
		$is_post = ($_SERVER['REQUEST_METHOD'] == 'POST');
		switch ($_GET['action'])
		{
			case 'form':
				if ($is_post && $this->auth->checkAuth() && $_GET['action'] == 'form')
					$this->save_form();
				break;
			default:
				break;
		}
	}

	/**
	 *
	 */
	function show()
	{
		global $_GET;
		switch ($_GET['action'])
		{
			case 'form':
				$ret .= $this->show_form();
				break;
			default:
				$ret .= $this->show_main();
				break;
		}
		return($ret);
	}

	/**
	 *
	 */
	function show_main()
	{
		global $_GET;

		// title
		$this->title = $this->msg['glossary'];
		$disciplines = $this->db->get_row_assoc('
			SELECT discipline, discipline_name
			FROM discipline ORDER BY discipline_name;',
			'discipline', 'discipline_name');
		if (array_key_exists($_GET['dc'], $disciplines))
			$this->title .= ' ' . $disciplines[$_GET['dc']];
		$ret .= sprintf('<h1>%1$s</h1>' . LF, $this->title);

		// search
		if (!$sublist)
			$ret .= $this->show_search();

		// if there's phrase
		if ($_GET['phrase'] || $_GET['dc'] || $_GET['src'])
		{
			$ret .= $this->show_result();
		}
		// nothing, show main page
		else
		{
			$is_main = true;
			$ret .= '<p><strong>' . $this->msg['glo_by_discipline'] . '</strong></p>' . LF;
			$rows = $this->db->get_rows('SELECT * FROM discipline ORDER BY discipline;');
			if ($row_count = $this->db->num_rows)
			{
				$ret .= '<blockquote>' . LF;
				$i = 0;
				foreach ($rows as $row)
				{
					if ($i > 0) $ret .= '; ';
					$ret .= sprintf('<a href="./?mod=glo&dc=%2$s">%1$s</a> (%3$s)',
						$row['discipline_name'], $row['discipline'], $row['glossary_count']);
					$i++;
				}
				$ret .= '</blockquote>' . LF;
			}
			$ret .= '<p><strong>' . $this->msg['glo_by_source'] . '</strong></p>' . LF;
			$rows = $this->db->get_rows('SELECT * FROM ref_source ORDER BY ref_source_name;');
			if ($row_count = $this->db->num_rows)
			{
				$ret .= '<blockquote>' . LF;
				$i = 0;
				foreach ($rows as $row)
				{
					if ($i > 0) $ret .= '; ';
					$ret .= sprintf('<a href="./?mod=glo&src=%2$s">%1$s</a> (%3$s)',
						$row['ref_source_name'], $row['ref_source'], $row['glossary_count']);
					$i++;
				}
				$ret .= '</blockquote>' . LF;
			}
		}

		// return
		return($ret);
	}

	/**
	 *
	 */
	function show_result()
	{
		global $_GET;

		$phrase = trim($_GET['phrase']);
		$discipline = trim($_GET['dc']);
		$src = trim($_GET['src']);
		$lang = trim($_GET['lang']);
		$msg1 = ($lang == 'id') ? 'id' : 'en';
		$msg2 = ($lang == 'id') ? 'en' : 'id';
		$phrase1 = ($lang == 'id') ? 'phrase' : 'original';
		$phrase2 = ($lang == 'id') ? 'original' : 'phrase';
		$wp1 = 'wp' . $msg1;
		$wp2 = 'wp' . $msg2;
		if ($phrase)
		{
			$where .= $where ? ' AND ' : ' WHERE ';

			$op_open = ($_GET['op'] == '2') ? '[[:<:]]' : ($_GET['op'] == '3' ? '' : '%');
			$op_close = ($_GET['op'] == '2') ? '[[:>:]]' : ($_GET['op'] == '3' ? '' : '%');
			$op_type = ($_GET['op'] == '2') ? 'REGEXP' : ($_GET['op'] == '3' ? '=' : 'LIKE');
			$op_template = 'a.%1$s %2$s \'%3$s%4$s%5$s\'';
			$lang_id = sprintf($op_template, 'phrase', $op_type, $op_open, $this->db->quote($phrase, null, false), $op_close);
			$lang_en = sprintf($op_template, 'original', $op_type, $op_open, $this->db->quote($phrase, null, false), $op_close);
			switch ($lang)
			{
				case 'en':
					$where .= $lang_en;
					break;
				case 'id':
					$where .= $lang_id;
					break;
				default:
					$where .= ' (' . $lang_id . ' OR ' . $lang_en . ') ';
					break;
			}
		}
		if ($discipline)
		{
			$where .= $where ? ' AND ' : ' WHERE ';
			$where .= ' a.discipline = \'' . $discipline . '\' ';
		}
		if ($src)
		{
			$where .= $where ? ' AND ' : ' WHERE ';
			$where .= ' a.ref_source = \'' . $src . '\' ';
		}
		if ($this->sublist) $this->db->defaults['rperpage'] = 20;
		$cols = 'a.original, a.phrase, b.discipline_name, a.glo_uid, a.discipline, a.ref_source, a.wpid, a.wpen';
		$from = 'FROM glossary a
			LEFT JOIN discipline b ON a.discipline = b.discipline
			LEFT JOIN ref_source c ON a.ref_source = c.ref_source
			' . $where . '
			ORDER BY ' . $phrase1;
		$rows = $this->db->get_rows_paged($cols, $from);

		// header and new button
		if (!$this->sublist)
		{
			if ($this->auth->checkAuth())
				$ret .= sprintf('<p>%1$s</p>' . LF,
					sprintf('<a href="./%1$s&action=form&mod=glo">%2$s</a>',
						$this->get_url_param(array('search', 'action', 'uid', 'mod')),
						$this->msg['new']));
		}
		if ($this->db->num_rows > 0)
		{
			$ret .= '<p>';
			$ret .= $this->db->get_page_nav();
			$ret .= '</p>' . LF;

			$ret .= '<table class="list" width="100%">' . LF;

			// header
			$ret .= '<tr>' . LF;
			$tmp = '<th width="%2$s%%">%1$s</th>' . LF;;
			$ret .= sprintf($tmp, '&nbsp;', '1');
			$ret .= sprintf($tmp, $this->msg[$msg1], '25');
			$ret .= sprintf($tmp, $this->msg[$msg2], '25');
			$ret .= sprintf($tmp, $this->msg['keyword'], '30');
			$ret .= sprintf($tmp, $this->msg['discipline'], '10');
			$ret .= sprintf($tmp, $this->msg['ref_source'], '10');
			if ($this->auth->checkAuth())
				$ret .= sprintf($tmp, '&nbsp;', '1');
			$ret .= '</tr>' . LF;

			// rows
			$tmp = '<td align="%2$s">%1$s</td>' . LF;;
			foreach ($rows as $row)
			{
				$url = './' . $this->get_url_param(array('search', 'action', 'uid', 'mod')) .
					'&action=form&mod=glo&uid=' . $row['glo_uid'];
				$discipline = './' . $this->get_url_param(array('search', 'uid', 'dc')).
					'&dc=' . $row['discipline'];
				$ret .= '<tr valign="top">' . LF;
				$ret .= sprintf($tmp, ($this->db->pager['rbegin'] + $i) . '.', 'left');
				if ($row[$wp1])
					$ret .= sprintf($tmp, sprintf('<a href="http://%2$s.wikipedia.org/wiki/%3$s">%1$s</a>', $row[$phrase1], $msg1, $row[$wp1]), 'left');
				else
					$ret .= sprintf($tmp, $row[$phrase1], 'left');
				if ($row[$wp2])
					$ret .= sprintf($tmp, sprintf('<a href="http://%2$s.wikipedia.org/wiki/%3$s">%1$s</a>', $row[$phrase2], $msg2, $row[$wp2]), 'left');
				else
					$ret .= sprintf($tmp, $row[$phrase2], 'left');
				$ret .= sprintf($tmp, $this->parse_keywords($row['phrase']), 'left');
				if ($_GET['dc'])
					$ret .= sprintf($tmp, $row['discipline_name'], 'center');
				else
					$ret .= sprintf($tmp, sprintf('<a href="%1$s">%2$s</a>', $discipline, $row['discipline_name']), 'center');
				$ret .= sprintf($tmp, $row['ref_source'], 'center');
				// operation
				if ($this->auth->checkAuth())
					$ret .= sprintf($tmp,
						sprintf('<a href="%1$s">%2$s</a>', $url, $this->msg['edit']), 'left');
				$ret .= '</tr>' . LF;
				$i++;
			}
			$ret .= '</table>' . LF;

			$ret .= '<p>';
			$ret .= $this->db->get_page_nav();
			$ret .= '</p>' . LF;
		}
		else
			$ret = '<p>' . $this->msg['nf'] . '</p>' . LF;
		return($ret);
	}

	/**
	 *
	 */
	function show_search()
	{
		$form = new form('search_glo', 'get');
		$form->setup($msg);
		$form->addElement('hidden', 'mod', 'glo');
		$form->addElement('text', 'phrase', $this->msg['phrase']);
		$form->addElement('select', 'dc', $this->msg['discipline'],
			$this->db->get_row_assoc('SELECT discipline, discipline_name FROM discipline ORDER BY discipline_name', 'discipline', 'discipline_name')
			);
		$form->addElement('select', 'lang', $this->msg['lang'],
			$this->db->get_row_assoc('SELECT lang, lang_name FROM language ORDER BY lang', 'lang', 'lang_name')
			);
		$form->addElement('select', 'src', $this->msg['ref_source'],
			$this->db->get_row_assoc('SELECT ref_source, ref_source_name FROM ref_source', 'ref_source', 'ref_source_name')
			);
		$form->addElement('select', 'op', null, array('1' => 'Mirip', '2' => 'Memuat', '3' => 'Persis'));
		$form->addElement('submit', 'search', $this->msg['search_button']);

		$template = '%1$s: %2$s ' . LF;
		$ret .= '<fieldset style="border: solid 1px #999;">' . LF;
		$ret .= '<legend>' . $this->msg['search'] . '</legend>' . LF;
		$ret .= $form->begin_form();
		$ret .= sprintf($template, $this->msg['search_op'], $form->get_element('op'));
		$ret .= sprintf($template, $this->msg['phrase'], $form->get_element('phrase'));
		$ret .= '<br />';
		$ret .= sprintf($template, $this->msg['discipline'], $form->get_element('dc'));
		$ret .= sprintf($template, $this->msg['lang'], $form->get_element('lang'));
		$ret .= '<br />';
		$ret .= sprintf($template, $this->msg['ref_source'], $form->get_element('src'));
		$ret .= $form->get_element('mod');
		$ret .= $form->get_element('search');
		$ret .= $form->end_form();
		$ret .= '</fieldset>' . LF;

		return($ret);
	}

	/**
	 *
	 */
	function show_form()
	{
		$query = 'SELECT a.* FROM glossary a
			WHERE a.glo_uid = ' . $this->db->quote($_GET['uid']);
		$this->entry = $this->db->get_row($query);
		$is_new = is_array($this->entry);

		$form = new form('entry_form', null, './' . $this->get_url_param());
		$form->setup($this->msg);
		$form->addElement('text', 'original', $this->msg['en'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('text', 'phrase', $this->msg['id'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('select', 'discipline', $this->msg['discipline'], $this->db->get_row_assoc('SELECT * FROM discipline ORDER BY discipline_name', 'discipline', 'discipline_name'));
		$form->addElement('select', 'ref_source', $this->msg['ref_source'], $this->db->get_row_assoc('SELECT * FROM ref_source', 'ref_source', 'ref_source_name'));
		$form->addElement('text', 'wpen', $this->msg['wpen'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('text', 'wpid', $this->msg['wpid'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('hidden', 'glo_uid');
		$form->addElement('hidden', 'is_new', $is_new);
		$form->addElement('submit', 'save', $this->msg['save']);
		$form->addRule('phrase', sprintf($this->msg['required_alert'], $this->msg['id']), 'required', null, 'client');
		$form->addRule('original', sprintf($this->msg['required_alert'], $this->msg['en']), 'required', null, 'client');
		$form->addRule('discipline', sprintf($this->msg['required_alert'], $this->msg['discipline']), 'required', null, 'client');
		$form->addRule('ref_source', sprintf($this->msg['required_alert'], $this->msg['ref_source']), 'required', null, 'client');
		$form->setDefaults($this->entry);

		$ret .= $form->toHtml();
		return($ret);
	}

	/**
	 * Save glossary
	 *
	 * @return unknown_type
	 */
	function save_form()
	{
		global $_GET, $_POST;
		$is_new = (!$_POST['is_new']);
		// main
		if (!$is_new)
		{
			$query = sprintf(
				'UPDATE glossary SET
					phrase = %1$s,
					original = %2$s,
					discipline = %3$s,
					ref_source = %6$s,
					wpid = %7$s,
					wpen = %8$s,
					updater = %4$s,
					updated = NOW()
				WHERE glo_uid = %5$s;',
				$this->db->quote($_POST['phrase']),
				$this->db->quote($_POST['original']),
				$this->db->quote($_POST['discipline']),
				$this->db->quote($this->auth->getUsername()),
				$this->db->quote($_POST['glo_uid']),
				$this->db->quote($_POST['ref_source']),
				$this->db->quote($_POST['wpid']),
				$this->db->quote($_POST['wpen'])
			);
		}
		else
		{
			$query = sprintf('INSERT INTO glossary (phrase, original,
				discipline, ref_source, wpid, wpen, updater, updated)
				VALUES (%1$s, %2$s, %3$s, %5$s, %6$s, %7$s, %4$s, NOW());',
				$this->db->quote($_POST['phrase']),
				$this->db->quote($_POST['original']),
				$this->db->quote($_POST['discipline']),
				$this->db->quote($this->auth->getUsername()),
				$this->db->quote($_POST['ref_source']),
				$this->db->quote($_POST['wpid']),
				$this->db->quote($_POST['wpen'])
			);
		}
		//die($query);
		$this->db->exec($query);

		// redirect
		redir('./' . $this->get_url_param(array('action', 'uid')));
	}

	/**
	 *
	 */
	function get_url_param($exclude = null)
	{
		global $_GET;
		$ret = '';
		foreach ($_GET as $key => $val)
		{
			$is_excluded = false;
			$is_excluded = (trim($val) == '');
			if ($exclude)
				if (in_array($key, $exclude))
					$is_excluded = true;
			if (!$is_excluded)
			{
				$ret .= $ret ? '&' : '?';
				$ret .= $key . '=' . $val;
			}
		}
		if (!$ret) $ret = '?';
		return($ret);
	}

	/**
	 *
	 */
	function parse_keywords($string)
	{
		$keywords = preg_split("/[\s,-;=\'(\)]+/", $string);
		$clean_key = array();
		foreach($keywords as $word)
		{
			$word = trim($word);
			if ($word && !in_array($word, $clean_key))
			{
				$clean_key[] = $word;
			}
		}
		sort($clean_key);
		// cleaned key
		$url = '<a href="./?mod=dict&action=view&phrase=%1$s">%1$s</a>';
		foreach($clean_key as $word)
		{
			{
				$keyword .= $keyword ? '; ' : '';
				$keyword .= sprintf($url, $word);
			}
		}
		return($keyword);
	}};
?>