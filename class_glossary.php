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
		$query = 'SELECT a.translation, a.phrase, b.discipline_name
			FROM translation a LEFT JOIN discipline b
			ON a.discipline = b.discipline ' . $where . '
			ORDER BY translation LIMIT 0, 50;';
		//echo($query);
		$rows = $this->db->get_rows($query);
		if ($this->db->num_rows > 0)
		{
			$ret .= '<table width="100%">' . LF;
			$ret .= '<tr>' . LF;
			$tmp = '<th>%1$s</th>' . LF;;
			$ret .= sprintf($tmp, $this->msg['en'], '40');
			$ret .= sprintf($tmp, $this->msg['id'], '40');
			$ret .= sprintf($tmp, $this->msg['discipline'], '40');
			$ret .= '</tr>' . LF;
			$tmp = '<td width="%2$s%%">%1$s</td>' . LF;;
			foreach ($rows as $row)
			{
				$ret .= '<tr>' . LF;
				$ret .= sprintf($tmp, $row['translation'], '40');
				$ret .= sprintf($tmp, $row['phrase'], '40');
				$ret .= sprintf($tmp, $row['discipline_name'], '20');
				$ret .= '</tr>' . LF;
			}
			$ret .= '<table>' . LF;
		}
		else
			$ret = 'Frasa tidak ditemukan.';

		return($ret);
	}
};
?>