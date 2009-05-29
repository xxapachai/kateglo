<?php
/**
 * Prototype for page class
 */
class page
{
	var $db;
	var $auth;
	var $msg;

	/**
	 * Constructor
	 */
	function page(&$db, &$auth, $msg)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->msg = $msg;
	}

	/**
	 * Process page
	 */
	function process()
	{
	}

	/**
	 * Show page
	 */
	function show()
	{
	}

	/**
	 * Keywords
	 */
	function get_keywords()
	{
		return;
	}

	/**
	 * Description
	 */
	function get_description()
	{
		return;
	}

};
?>