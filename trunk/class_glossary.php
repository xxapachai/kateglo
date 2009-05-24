<?php
/**
 *
 *
 *
 */
class glossary
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
	function show_main()
	{
		global $_GET;
		$ret .= sprintf('<h1>%1$s</h1>' . LF, $this->msg['glossary']);
		// if there's phrase
		if ($_GET['phrase'] || $_GET['dc'] || $_GET['src'])
		{
			$ret .= $this->show_result();
		}
		// nothing, show main page
		else
		{
//			$ret .= '<table width="100%"><tr valign="top">' . LF;
//			$ret .= '<td width="50%">' . LF;
			$ret .= '<p><strong>' . $this->msg['glo_by_discipline'] . '</strong></p>' . LF;
			$rows = $this->db->get_rows('SELECT * FROM discipline ORDER BY discipline;');
			if ($row_count = $this->db->num_rows)
			{
//				$ret .= '<ol>' . LF;
				$ret .= '<blockquote>' . LF;
				$i = 0;
				foreach ($rows as $row)
				{
					if ($i > 0) $ret .= ', ';
					$ret .= sprintf('<a href="./?mod=glo&dc=%2$s">%1$s</a>',
						$row['discipline_name'], $row['discipline']);
					$i++;
				}
				$ret .= '</blockquote>' . LF;
//				$ret .= '</ol>' . LF;
			}
//			$ret .= '</td>' . LF;
//			$ret .= '<td width="50%">' . LF;
			$ret .= '<p><strong>' . $this->msg['glo_by_source'] . '</strong></p>' . LF;
			$rows = $this->db->get_rows('SELECT * FROM ref_source ORDER BY ref_source_name;');
			if ($row_count = $this->db->num_rows)
			{
//				$ret .= '<ol>' . LF;
				$ret .= '<blockquote>' . LF;
				$i = 0;
				foreach ($rows as $row)
				{
					if ($i > 0) $ret .= ', ';
					$ret .= sprintf('<a href="./?mod=glo&src=%2$s">%1$s</a>',
						$row['ref_source_name'], $row['ref_source']);
					$i++;
				}
				$ret .= '</blockquote>' . LF;
//				$ret .= '</ol>' . LF;
			}
//			$ret .= '</td>' . LF;
//			$ret .= '</tr></table>' . LF;
		}
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
		$phrase1 = ($lang == 'id') ? 'phrase' : 'translation';
		$phrase2 = ($lang == 'id') ? 'translation' : 'phrase';
		$wp1 = 'wp' . $msg1;
		$wp2 = 'wp' . $msg2;
		if ($phrase)
		{
			$where .= $where ? ' AND ' : ' WHERE ';
			$lang_id = 'a.phrase LIKE \'%' . $this->db->quote($phrase, null, false) . '%\'';
			$lang_en = 'a.translation LIKE \'%' .  $this->db->quote($phrase, null, false) . '%\'';
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
		$cols = 'a.translation, a.phrase, b.discipline_name, a.tr_uid, a.discipline, a.ref_source, a.wpid, a.wpen';
		$from = 'FROM translation a
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
					'&action=form&mod=glo&uid=' . $row['tr_uid'];
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
			$ret = '<p>Frasa tidak ditemukan.</p>' . LF;
		return($ret);
	}

	/**
	 *
	 */
	function show_form()
	{
		$query = 'SELECT a.* FROM translation a
			WHERE a.tr_uid = ' . $this->db->quote($_GET['uid']);
		$this->entry = $this->db->get_row($query);
		$is_new = is_array($this->entry);

		$form = new form('entry_form', null, './' . $this->get_url_param());
		$form->setup($this->msg);
		$form->addElement('text', 'translation', $this->msg['en'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('text', 'phrase', $this->msg['id'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('select', 'discipline', $this->msg['discipline'], $this->db->get_row_assoc('SELECT * FROM discipline ORDER BY discipline_name', 'discipline', 'discipline_name'));
		$form->addElement('select', 'ref_source', $this->msg['ref_source'], $this->db->get_row_assoc('SELECT * FROM ref_source', 'ref_source', 'ref_source_name'));
		$form->addElement('text', 'wpen', $this->msg['wpen'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('text', 'wpid', $this->msg['wpid'], array('size' => 40, 'maxlength' => '255'));
		$form->addElement('hidden', 'tr_uid');
		$form->addElement('hidden', 'is_new', $is_new);
		$form->addElement('submit', 'save', $this->msg['save']);
		$form->addRule('phrase', sprintf($this->msg['required_alert'], $this->msg['id']), 'required', null, 'client');
		$form->addRule('translation', sprintf($this->msg['required_alert'], $this->msg['en']), 'required', null, 'client');
		$form->addRule('discipline', sprintf($this->msg['required_alert'], $this->msg['discipline']), 'required', null, 'client');
		$form->addRule('ref_source', sprintf($this->msg['required_alert'], $this->msg['ref_source']), 'required', null, 'client');
		$form->setDefaults($this->entry);

		$ret .= $form->toHtml();
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
				'UPDATE translation SET
					phrase = %1$s,
					translation = %2$s,
					discipline = %3$s,
					ref_source = %6$s,
					wpid = %7$s,
					wpen = %8$s,
					updater = %4$s,
					updated = NOW()
				WHERE tr_uid = %5$s;',
				$this->db->quote($_POST['phrase']),
				$this->db->quote($_POST['translation']),
				$this->db->quote($_POST['discipline']),
				$this->db->quote($this->auth->getUsername()),
				$this->db->quote($_POST['tr_uid']),
				$this->db->quote($_POST['ref_source']),
				$this->db->quote($_POST['wpid']),
				$this->db->quote($_POST['wpen'])
			);
		}
		else
		{
			$query = sprintf('INSERT INTO translation (phrase, translation,
				discipline, ref_source, wpid, wpen, updater, updated)
				VALUES (%1$s, %2$s, %3$s, %5$s, %6$s, %7$s, %4$s, NOW());',
				$this->db->quote($_POST['phrase']),
				$this->db->quote($_POST['translation']),
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
};
?>