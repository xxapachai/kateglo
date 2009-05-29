<?php
/**
 *
 *
 *
 */
class doc extends page
{
	var $db;
	var $auth;
	var $msg;
	var $title;

	/**
	 * Constructor
	 */
	function doc(&$db, &$auth, $msg)
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
	}

	/**
	 *
	 */
	function show()
	{
		global $_GET;
		$ret .= read_doc($_GET['doc']);
		$this->title = $_GET['doc'];
		return($ret);
	}
};
?>