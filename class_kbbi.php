<?php
/**
 * Retrieve data from KBBI
 *
 * OPKODE: 1 = sama dengan, 2 = diawali, 3 = memuat
 * @created 2009-03-30 11:02 <IL>
 * Jw = Jawa, Mk = Minangkabau
 * n = nomina, v = verba, adv = adverbia, a = adjektiva, num = numeralia, p = partikel (artikel, preposisi, konjungsi, interjeksi), pron = pronomina
 * pb = peribahasa
 */
class kbbi
{
	var $param;
	var $mode;
	var $query;
	var $found = false;
	var $auto_parse = false;
	var $raw_entries; // individual match from kbbi
	var $parsed_entries; // parsed value
	var $clean_entries; // parsed individual
	var $last_lex; // last lexical class

	//$modes = array('sama dengan', 'diawali', 'memuat');

	/*
	 * Get result from KBBI
	 */
	function query($query, $mode)
	{
		$this->query = $query;
		$this->mode = $mode;
		$this->param = array(
			'more' => 0,
			'head' => 0,
			'opcode' => $this->mode,
			'param' => $this->query,
			'perintah' => 'Cari',
			'perintah2' => '',
			'dftkata' => '',
		);
		$this->get_words();
		if ($this->param['dftkata'])
		{
			$words = explode(';', $this->param['dftkata']);
			// $ret .= 'Ditemukan ' . count($words) . ' entri<hr size="1" />' . LF;
			foreach ($words as $word)
			{
				$ret .= $this->define($word) . '' . LF;
			}
			$this->found = true;
		}
		else
		{
			$ret .= 'Tidak ditemukan entri yang sesuai.' . LF;
		}
		return($ret);
	}

	/*
	 * Get result from KBBI
	 */
	function get_words()
	{
		$url = 'http://pusatbahasa.diknas.go.id/kbbi/index.php';
		$data = 'OPKODE=%1$s&PARAM=%2$s&HEAD=%3$s&MORE=%4$s&PERINTAH2=%5$s&%6$s';
		if ($this->param['perintah'] != '')
		{
			$perintah = 'PERINTAH=' . $this->param['perintah'];
		}
		$data = sprintf($data,
			$this->param['opcode'], $this->param['param'], $this->param['head'],
			$this->param['more'], $this->param['perintah2'], $perintah
		);
		$result = $this->get_curl($url, $data);
		$pattern = '/<input type="hidden" name="DFTKATA" value="(.+)" >.+' .
			'<input type="hidden" name="MORE" value="(.+)" >.+' .
			'<input type="hidden" name="HEAD" value="(.+)" >/s';
		preg_match($pattern, $result, $match);
	//	var_dump($match);
	//	echo('<br />');
		if (is_array($match))
		{
			if ($match[2] == 1)
			{
				$this->param['perintah'] = '';
				$this->param['perintah2'] = 'Berikut';
				$this->param['head'] = $match[3] + 15;
				$this->get_words();
			}
			$this->param['dftkata'] .= $this->param['dftkata'] ? ';' : '';
			$this->param['dftkata'] .= $match[1];
		}
		// if (is_array($match)) return($match[2]);
	}

	/*
	 * Get result from KBBI
	 */
	function define($query)
	{
		$url = 'http://pusatbahasa.diknas.go.id/kbbi/index.php';
		$data .= 'DFTKATA=%2$s&HEAD=0&KATA=%2$s&MORE=0&OPKODE=1&PARAM=&PERINTAH2=Tampilkan';
		$data .= sprintf($data, '1', $query);
		$result = $this->get_curl($url, $data);
		$pattern = '/(<p style=\'margin-left:\.5in;text-indent:-\.5in\'>)(.+)(<\/p>)/s';
		preg_match($pattern, $result, $match);
		if (is_array($match))
		{
			$def = trim($match[2]);
			$this->raw_entries[] = $def;
			$def = str_replace('<br>', '<br><br>', $def);
			$return = $def;
			return($return);
		}
	}

	/*
	 * Get result from KBBI
	 */
	function get_curl($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return($result);
	}

	/**
	 */
	function parse($phrase)
	{
		if ($phrase)
		{
			// get parsed entries
			$this->query($phrase, 1);
			$entry_idx = 0;
			$entry_count = count($this->raw_entries);
			for ($i = 0; $i < $entry_count; $i++)
			{
				$this->parse_raw($this->raw_entries[$i], &$entry_idx);
			}
			$this->parse_final();

			// get clean entries
			if (!$this->parsed_entries) return;
			foreach ($this->parsed_entries as $parsed)
			{
				$clean = &$this->clean_entries[trim($parsed['word'])];
				if ($parsed['info'] && !$clean['info']) $clean['info'] = $parsed['info'];
				if ($parsed['pron']) $clean['pron'] = $parsed['pron'];
				if ($parsed['type']) $clean['type'] = $parsed['type'];
				if ($parsed['def'] && !$parsed['pb'])
				{
					$clean['definitions'][] = '';
					$def_idx = count($clean['definitions']) - 1;
					$clean['definitions'][$def_idx]['index'] = $def_idx + 1;
					$clean['definitions'][$def_idx]['text'] = $parsed['def'];
					$clean['definitions'][$def_idx]['sample'] = $parsed['sample'];
					if ($clean['info'] != $parsed['info'])
					{
						$clean['definitions'][$def_idx]['info'] = $parsed['info'];
					}
				}
				$this->parse_info(&$clean);
			}

			// get clean entries
			foreach ($this->clean_entries as &$clean)
			{
				$this->parse_info(&$clean);
			}
		}
	}

	/**
	 */
	function parse_raw($raw_data, &$entry_idx)
	{
		$defs = explode("<br>", $raw_data);
		$pattern = '/(?:-|~)*<b>.*<\/b>/U';
		foreach ($defs as $def)
		{
			preg_match_all($pattern, $def, $matches);
			$start = $entry_idx; // temporary var to mark start of index
			if (count($matches) > 0)
			{
				$def = trim($def);
				// keys
				if (substr($def, 0, 4) == '</i>') $def = substr($def, 4);
				$this->parse_raw_item($matches, 'raw_key', &$entry_idx);
				$entry_idx = $start; // revert back to start of index
				// values
				$matches = preg_split($pattern, $def, null, PREG_SPLIT_NO_EMPTY);
				$this->parse_raw_item($matches, 'raw_value', &$entry_idx);
			}
		}
	}

	/**
	 */
	function parse_raw_item($matches, $key, &$entry_idx)
	{
		$arr = is_array($matches[0]) ? $matches[0] : $matches;
		foreach ($arr as $val)
		{
			$this->parsed_entries[$entry_idx][$key] = $val;
			$entry_idx++;
		}
	}

	/**
	 */
	function parse_final()
	{
		// process
		$j = 0;
		$delim = ' ';
		$abbrev = array(
			'dl' => 'dalam',
			'dng' => 'dengan',
			'dr' => 'dari',
			'dp' => 'daripada',
			'kpd' => 'kepada',
			'krn' => 'karena',
			'msl' => 'misal',
			'pd' => 'pada',
			'sbg' => 'sebagai',
			'spt' => 'seperti',
			'tsb' => 'tersebut',
			'tt' => 'tentang',
			'yg' => 'yang',
		);
		if (!$this->parsed_entries) return;
		foreach($this->parsed_entries as &$entry)
		{
			// clean caption
			$entry['raw_all'] = trim($entry['raw_key'] . ' ' . $entry['raw_value']);
			$entry['a1'] = trim(str_replace('&#183;', '', strip_tags($entry['raw_key'])));

			// fix if there's a sup
			if (strpos($entry['raw_key'], '<sup>'))
			{
				$entry['a1'] = preg_replace('/\d/', '', $entry['a1']);
				$entry['word'] = trim($entry['a1']);
				$word = $entry['word'];
				$is_compound = false;
			}

			// first entry
			if ($j == 0)
			{
				$word = trim($entry['a1']);
				$root = $word;
			}

			// mark new word -> TODO:shouldn't rely too much on this. What's for one satu suku kata?
			if (strpos($entry['raw_key'], '&#183;') !== false)
			{
				$entry['word'] = trim($entry['a1']);
				$word = $entry['word'];
				$is_compound = false;
			}

			// fill if no word found
			if (!$entry['word'])
			{
				$entry['word'] = trim($is_compound ? $compound : $word);
			}

			// if -- found
			if (strpos($entry['a1'], '--') !== false)
			{
				$entry['word'] = trim(str_replace('--', $root, $entry['a1']));
				$compound = $entry['word'];
				$is_compound = true;
			}
			// if ~ found
			if (strpos($entry['a1'], '~') !== false)
			{
				$entry['word'] = trim(str_replace('~', $word, $entry['a1']));
				$compound = $entry['word'];
				$is_compound = true;
			}

			// peribahasa
			if (!$entry['raw_key']) $entry['pb'] = 'pb';
			$entry['word'] = trim(str_replace(' ', $delim, $entry['word']));

			// trailing _
			if (substr($entry['word'], -1) == $delim)
			{
				$entry['word'] = trim(substr($entry['word'], 0, strlen($entry['word']) - 1));
				$entry['idx'] = 1;
				$word = $entry['word'];
			}
			// trailing _1
			if (substr($entry['word'], -2) == $delim . '1')
			{
				$entry['word'] = trim(substr($entry['word'], 0, strlen($entry['word']) - 2));
				$entry['idx'] = 1;
				$word = $entry['word'];
			}
			// index
			if (is_numeric($entry['a1'])) $entry['idx'] = trim($entry['a1']);

			// definition and sample
			if ($def_sample = split(':', $entry['raw_value']))
			{
				$entry['def'] = trim($def_sample[0]);
				$entry['sample'] = trim($def_sample[1]);
			}
			else
			{
				$entry['def'] = trim($entry['raw_value']);
				$entry['sample'] = '';
			}

			// pronounciation
			$pattern = '/^\/([^\/]+)\/(.+)/';
			if (preg_match($pattern, $entry['def'], $pron))
			{
				$entry['pron'] = trim($pron[1]);
				$entry['def'] = trim($pron[2]);
			}

			// sample
			$entry['sample'] = preg_replace('/<\/?i>/', '', $entry['sample']);

			// definition
			$pattern = '/^<i>[^<]+<\/i>/U';
			if (!$entry['pb'])
			{
				// info
				preg_match_all($pattern, $entry['def'], $info);
				if (is_array($info))
				{
					$info_raw = trim($info[0][0]);
					$entry['info'] = trim(strip_tags($info_raw)); // FIXME: Ack sample
				}
				// definition
				$entry['def'] = trim(str_replace($info_raw, '', $entry['def']));
				if ($entry['def']) $entry['def'] = trim(strip_tags($entry['def']));
			}
			else
			{
				$entry['def'] = trim(str_replace('--', $entry['word'], $entry['def']));
				$entry['def'] = trim(str_replace(', pb', ': ', $entry['def']));
				$entry['def'] = trim(strip_tags($entry['def']));
			}

			// common abbreviation
			foreach ($abbrev as $key => $value)
			{
				$entry['sample'] = preg_replace('/\b' . $key . '\b/', $value, $entry['sample']);
				$entry['def'] = preg_replace('/\b' . $key . '\b/', $value, $entry['def']);
			}

			// type
			if ($word == $root) $entry['type'] = 'r';
			if ($is_compound) $entry['type'] = 'c';
			if ($word != $root && !$is_compound) $entry['type'] = 'f';

			// trim
			if (substr($entry['def'], -1, 1) == ';') $entry['def'] = substr($entry['def'], 0, strlen($entry['def']) - 1);
			if (substr($entry['sample'], -1, 1) == ';') $entry['sample'] = substr($entry['sample'], 0, strlen($entry['sample']) - 1);

			$entry['sample'] = preg_replace('/(^|\s)-(\s|$)/', '\1--\2', $entry['sample']);

			$j++;
		}
	}

	/**
	 */
	function parse_info(&$clean)
	{
		$info = explode(' ', $clean['info']);
		$lex_classes = array('n' => 'n', 'v' => 'v', 'adv' => 'adv', 'a' => 'adj', 'p' => 'l', 'num' => 'num', 'pron' => 'pron');
		if ($info)
		{
			$count = count($info);
			for ($i = 0; $i < $count; $i++)
			{
				if (array_key_exists($info[$i], $lex_classes))
				{
					$clean['lex_class'] = $lex_classes[$info[$i]];
					$this->last_lex = $clean['lex_class'];
				}
			}
		}
		if (!$clean['lex_class']) $clean['lex_class'] = $this->last_lex;
	}

};
?>