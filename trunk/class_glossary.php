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

	/**
	 *
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
	function show_result()
	{
		global $_GET;
		$phrase = trim($_GET['phrase']);
		$discipline = trim($_GET['dc']);
		$lang = trim($_GET['lang']);
		if ($phrase)
		{
			$where .= $where ? ' AND ' : ' WHERE ';
			$lang_id = 'a.phrase LIKE \'%' . $phrase . '%\'';
			$lang_en = 'a.translation LIKE \'%' . $phrase . '%\'';
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
		$cols = 'a.translation, a.phrase, b.discipline_name';
		$from = 'FROM translation a LEFT JOIN discipline b
			ON a.discipline = b.discipline ' . $where . '
			ORDER BY translation';
		//echo($query);
		$rows = $this->db->get_rows_paged($cols, $from);
		if ($this->db->num_rows > 0)
		{
			$ret .= $this->db->get_page_nav();
			$ret .= '<table width="100%" class="list">' . LF;
			$ret .= '<tr>' . LF;
			$tmp = '<th width="%2$s%%">%1$s</th>' . LF;;
			$ret .= sprintf($tmp, $this->msg['en'], '30');
			$ret .= sprintf($tmp, $this->msg['id'], '30');
			$ret .= sprintf($tmp, $this->msg['discipline'], '10');
			$ret .= sprintf($tmp, $this->msg['keyword'], '30');
			$ret .= '</tr>' . LF;
			$tmp = '<td>%1$s</td>' . LF;;
			foreach ($rows as $row)
			{
				$ret .= '<tr>' . LF;
				$ret .= sprintf($tmp, $row['translation']);
				$ret .= sprintf($tmp, $row['phrase']);
				$ret .= sprintf($tmp, $row['discipline_name']);
				$ret .= sprintf($tmp, $this->parse_keywords($row['phrase']));
				$ret .= '</tr>' . LF;
			}
			$ret .= '<table>' . LF;
			$ret .= $this->db->get_page_nav();
		}
		else
			$ret = 'Frasa tidak ditemukan.';

		return($ret);
	}

	function parse_keywords($string)
	{
		$keywords = preg_split("/[\s,-;\(\)]+/", $string);
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
		$url = '<a href="./?mod=dict&phrase=%1$s">%1$s</a>';
		foreach($clean_key as $word)
		{
			{
				$keyword .= $keyword ? '; ' : '';
				$keyword .= sprintf($url, $word);
			}
		}
		return($keyword);
	}
};
?>