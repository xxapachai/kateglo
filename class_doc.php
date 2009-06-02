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
		$file_name = $_GET['doc'];
		$file_url = './docs/' . $file_name;
		if (file_exists($file_url))
			$ret = nl2br(htmlentities(file_get_contents($file_url)));
		$this->title = $file_name;
		return($ret);
	}
};
?>