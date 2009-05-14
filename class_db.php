<?php
/**
 * Common database function wrapper
 *
 * @author ivan@lanin.org
 */
require_once('MDB2.php');

define(DB_RECORD_PER_PAGE, 30);

class db
{
	var $dsn;
	var $msg;
	var $num_rows;
	var $pager;        // pager values

	var $_db;

	/**
	 * @param $dsn
	 * @return Void
	 */
	function connect($dsn)
	{
		$this->dsn = sprintf('%1$s://%2$s:%3$s@%4$s/%5$s',
			'mysql', $dsn['user'], $dsn['pass'], $dsn['host'], $dsn['name']);
		$this->_db =& MDB2::factory($this->dsn);
		if (PEAR::isError($this->_db)) die($this->_db->getMessage());
	}

	/**
	 * Return array of rows and columns:
	 * - Rows are zero-based index array
	 * - Each contains associative array of columns
	 *
	 * @param $query string
	 * @return Array of rows
	 */
	function get_rows($query, $assoc = true)
	{
		$fetch_mode = $assoc ? MDB2_FETCHMODE_ASSOC : MDB2_FETCHMODE_ORDERED;
		$rows = $this->_db->queryAll($query, null, $fetch_mode);
		$this->num_rows = count($rows);
		return($rows);
	}

	/**
	 * Execute a query
	 *
	 * @param $query
	 * @return unknown_type
	 */
	function get_rows_paged($cols, $from)
	{
		global $_GET;
		$this->pager['pcurrent'] = $_GET['p'];
		$this->pager['rperpage'] = $_GET['rpp'];

		$rows = $this->_db->queryAll('SELECT COUNT(*) ' . $from);
		$this->pager['rcount'] = $rows[0][0];

		// record per page
		$is_reset = !is_numeric($this->pager['rperpage']);
		if (!$is_reset) $this->pager['rperpage'] = round($this->pager['rperpage'], 0);
		if (!$is_reset) $is_reset = ($this->pager['rperpage'] < 0);
		if ($is_reset)
			$this->pager['rperpage'] = DB_RECORD_PER_PAGE;
		if ($this->pager['rperpage'] == 0)
			$this->pager['rperpage'] = $this->pager['rcount'];
		if ($_GET['rpp']) $_GET['rpp'] = $this->pager['rperpage'];

		// prepare pager
		$this->pager['pcount'] = floor($this->pager['rcount'] / $this->pager['rperpage']);
		if ($this->pager['rcount'] % $this->pager['rperpage'] > 0)
		{
			$this->pager['pcount']++;
		}
		if (!is_numeric($this->pager['pcurrent']) |
		$this->pager['pcurrent'] < 1 |
		$this->pager['pcurrent'] > $this->pager['pcount'])
		$this->pager['pcurrent'] = 1;
		$this->pager['roffset'] = ($this->pager['pcurrent'] - 1) * $this->pager['rperpage'];
		$this->pager['rbegin'] = $this->pager['roffset'] + 1;
		$this->pager['rend'] = $this->pager['roffset'] + $this->pager['rperpage'];
		if ($this->pager['rend'] > $this->pager['rcount'])
		{
			$this->pager['rend'] = $this->pager['rcount'];
		}

		// sql statement
		$limit =  'LIMIT ' . $this->pager['roffset'] . ', ' . $this->pager['rperpage'];
		$query = 'SELECT ' . $cols . ' ' . $from . ' ' . $limit;
		return($this->get_rows($query));
	}

	/**
	 * Return first row of result as associative array of columns
	 *
	 * @param $query string
	 * @return Array of columns
	 */
	function get_row($query)
	{
		$rows = $this->get_rows($query);
		return($rows[0]);
	}

	/**
	 * Return all row as associative array of key and value
	 *
	 * @param $query string
	 * @return Array of columns
	 */
	function get_row_assoc($query, $key, $value, $has_empty = true)
	{
		$rows = $this->get_rows($query);
		if ($has_empty) $ret[''] = '';
		if ($this->num_rows > 0)
		{
			for ($i = 0; $i < $this->num_rows; $i++)
			{
				$ret[$rows[$i][$key]] = $rows[$i][$value];
			}
		}
		return($ret);
	}

	/**
	 * Return one value based on column index
	 *
	 * @param $query string
	 * @return value
	 */
	function get_row_value($query, $col_index = 0)
	{
		$rows = $this->get_rows($query, false);
		return($rows[0][$col_index]);
	}

	/**
	 * Execute a query
	 *
	 * @param $query
	 * @return unknown_type
	 */
	function exec($query)
	{
		$this->_db->exec($query);
	}

	function quote($value)
	{
		return($this->_db->quote(trim($value)));
	}

	function get_page_nav()
	{
		global $_GET;

		// url
		$url .= './?';
		$add_page_var = true;
		foreach($_GET as $key=>$val)
		{
			if ($key == 'p')
			{
				$add_page_var = false;
				$val = '%1$s';
			}
			$url .= $url ? '&' : '';
			$url .= $key . '=' . $val;
		}
		if ($add_page_var) $url .= '&p=%1$s';

		// return
		$ret .= sprintf($this->msg['page_nav'],
			$this->pager['rbegin'],
			$this->pager['rend'],
			$this->pager['rcount']);
		if ($this->pager['pcount'] > 1)
		{
			$ret .= ' ';
			$tmp = '<span style="padding: 0px 5px;"><a href="%2$s">%1$s</a></span>';
			$tmp2 = '<span style="padding: 0px 5px;"><strong>%1$s</span></strong>';
			$max = $this->pager['pcurrent'] + 9;
			if ($max > $this->pager['pcount']) $max = $this->pager['pcount'];
			// previous
			if ($this->pager['pcurrent'] > 1)
				$ret .= sprintf($tmp, $this->msg['page_prev'],
					sprintf($url, $this->pager['pcurrent'] - 1));
			// pages
			for ($i = $this->pager['pcurrent']; $i <= $max; $i++)
			{
				if ($i == $this->pager['pcurrent'])
					$ret .= sprintf($tmp2, $i);
				else
					$ret .= sprintf($tmp, $i, sprintf($url, $i));
			}
			// next
			if ($this->pager['pcurrent'] < $this->pager['pcount'])
				$ret .= sprintf($tmp, $this->msg['page_next'],
					sprintf($url, $this->pager['pcurrent'] + 1));
		}
		return($ret);
	}
};
?>