<?php
/**
 * Common database function wrapper
 *
 * @author ivan@lanin.org
 */
require_once('MDB2.php');

class db
{
	var $dsn;
	var $num_rows;

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
	function get_rows($query)
	{
		$rows = $this->_db->queryAll($query, null, MDB2_FETCHMODE_ASSOC);
		$this->num_rows = count($rows);
		return($rows);
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
		return($this->_db->quote($value));
	}
};
?>