<?php
/**
 * Retrieve data from KBBI
 *
 * OPKODE: 1 = sama dengan, 2 = diawali, 3 = memuat
 * @created 2009-03-30 11:02 <IL>
 */
class kbbi
{
	var $param;
	var $mode;
	var $query;
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
};
?>