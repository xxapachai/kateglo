<?php
/**
 *
 */
class home extends page
{

	/**
	 * Constructor
	 */
	function home(&$db, &$auth, $msg)
	{
		parent::page(&$db, &$auth, $msg);
	}

	/**
	 *
	 */
	function show()
	{
		// statistics
		$searches = $this->db->get_rows('SELECT phrase FROM searched_phrase
			ORDER BY search_count DESC LIMIT 0, 5;');
		if ($searches)
		{
			$search_result = '';
			$tmp = '<strong>%1$s</strong> [<a href="?mod=dict&action=view&phrase=%1$s">%2$s</a>, '
				. '<a href="?mod=glo&phrase=%1$s">%3$s</a>]';
			for ($i = 0; $i < $this->db->num_rows; $i++)
			{
				if ($this->db->num_rows > 2)
					$search_result .= $search_result ? ', ' : '';
				if ($i == $this->db->num_rows - 1 && $this->db->num_rows > 1)
					$search_result .= ' dan ';
				$search_result .= sprintf($tmp, $searches[$i]['phrase'],
					$this->msg['dict_short'], $this->msg['glo_short']);
			}
		}
		else
			$search_result = $this->msg['no_most_searched'];

		// stat count
		$dict_count = $this->db->get_row_value('SELECT COUNT(*) FROM phrase;');
		$glo_count = $this->db->get_row_value('SELECT COUNT(*) FROM glossary;');
		$prv_count = $this->db->get_row_value('SELECT COUNT(*) FROM proverb WHERE prv_type = 1;');

		// welcome
		$ret .= '<div align="center" style="padding: 10px 0px;">' . LF;
		$ret .= sprintf($this->msg['welcome'] . LF, $dict_count, $glo_count, $prv_count, $search_result);

		// random
		$query = 'SELECT phrase, lex_class FROM phrase
			WHERE (LEFT(phrase, 2) != \'a \' AND LEFT(phrase, 2) != \'b \')
			AND NOT ISNULL(updated) AND NOT ISNULL(lex_class)
			ORDER BY RAND() LIMIT 10;';
		$random_words = $this->db->get_rows($query);
		$url = './?mod=dict&action=view&phrase=';
		$ret .= '<p>' . LF;
		foreach ($random_words as $random_word)
		{
			$ret .= '<span style="padding: 0px 5px;">';
			$ret .= sprintf('<a href="%1$s%2$s">%2$s</a>%3$s',
				$url,
				$random_word['phrase'],
				''
			);
//				($random_word['lex_class'] ? ' (' . $random_word['lex_class'] . ')' : '')
			$ret .= '</span>' . LF;
		}

		$ret .= '</p>' . LF;
		$ret .= '</div>' . LF;

		// return
		return($ret);
	}

	/**
	 * Keywords
	 */
	function get_keywords()
	{
		return('bahasa Indonesia, glosarium, kamus, tesaurus');
	}

	/**
	 * Description
	 */
	function get_description()
	{
		return('Kamus, tesaurus, dan glosarium bahasa Indonesia dari milis Bahtera');
	}

};
?>