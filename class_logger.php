<?php
/**
 * Common log function wrapper
 *
 * @author ivan@lanin.org
 */
class logger
{
	var $db;
	var $auth;
	var $ses_id;

	function logger(&$db, &$auth)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->ses_id = session_id();
	}

	function log()
	{
		global $_SERVER, $_GET;

		// log session
		$query = sprintf('INSERT INTO sys_session (ses_id, ip_address,
			user_id, started) VALUES (\'%1$s\', \'%2$s\', \'%3$s\', NOW());',
			$this->ses_id, $_SERVER['REMOTE_ADDR'], $this->auth->getUsername());
		$this->db->exec($query);
		$description = sprintf('%1$s?%2$s',
			$_SERVER['SCRIPT_NAME'], $_SERVER['QUERY_STRING']);
		$query = sprintf('INSERT INTO sys_action (ses_id, action_time,
			description) VALUES (\'%1$s\', NOW(), \'%2$s\');',
			$this->ses_id, $description);
		$this->db->exec($query);

		// log searched phrase
		if ($_GET['phrase'])
		{
			$_GET['phrase'] = trim($_GET['phrase']);
			$query = sprintf('INSERT INTO searched_phrase (phrase)
				VALUES (\'%1$s\');', $_GET['phrase']);
			$this->db->exec($query);
			$query = sprintf('UPDATE searched_phrase SET
				search_count = search_count + 1, last_searched = NOW()
				WHERE phrase = %1$s;',
				$this->db->quote($_GET['phrase']));
			$this->db->exec($query);
		}
	}
}
?>