<?php
/**
 * User class for user related operation
 */
class user
{

	var $db; // database object
	var $auth; // authentication object
	var $msg; // messages string
	var $status;

	/**
	 * Constructor
	 */
	function user(&$db, &$auth, $msg)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->msg = $msg;
		$this->status = PROCESS_NONE;
	}

	/**
	 * Process password change
	 */
	function change_password()
	{
		global $_POST;
		$this->status = PROCESS_FAILED;
		$query = 'SELECT COUNT(*) FROM sys_user WHERE user_id = %1$s AND pass_key = MD5(%2$s);';
		$query = sprintf($query,
			$this->db->quote($this->auth->getUsername()),
			$this->db->quote($_POST['pwd_current'])
			);
		$found = $this->db->get_row_value($query);
		if ($found)
		{
			$query = 'UPDATE sys_user SET pass_key = MD5(%3$s)
				WHERE user_id = %1$s AND pass_key = MD5(%2$s);';
			$query = sprintf($query,
				$this->db->quote($this->auth->getUsername()),
				$this->db->quote($_POST['pwd_current']),
				$this->db->quote($_POST['pwd_new'])
				);
			$this->db->exec($query);
			$this->status = PROCESS_SUCCEED;
		}
	}

	/**
	 * Return HTML code of change password form
	 */
	function change_password_form()
	{
		if ($this->auth->checkAuth())
		{
			// welcome message
			$msg = $this->msg['pwd_welcome'];
			if ($this->status == PROCESS_FAILED)
				$msg = $this->msg['pwd_chg_failed'] . ' ' . $msg;
			if ($this->status == PROCESS_SUCCEED)
				$msg = $this->msg['pwd_chg_succeed'];
			$ret .= sprintf('<p>' . $msg . '</p>') . LF;

			// form
			if ($this->status != PROCESS_SUCCEED)
			{
				$form = new form('change_password_form', null, './?mod=auth&action=password');
				$form->setup($this->msg);
				$form->addElement('password', 'pwd_current', $this->msg['pwd_current']);
				$form->addElement('password', 'pwd_new', $this->msg['pwd_new']);
				$form->addElement('password', 'pwd_retype', $this->msg['pwd_retype']);
				$form->addElement('submit', null, $this->msg['submit']);
				$form->addRule('pwd_current', sprintf($this->msg['required_alert'], $this->msg['pwd_current']), 'required', null, 'client');
				$form->addRule('pwd_new', sprintf($this->msg['required_alert'], $this->msg['pwd_new']), 'required', null, 'client');
				$form->addRule('pwd_retype', sprintf($this->msg['required_alert'], $this->msg['pwd_retype']), 'required', null, 'client');
				$form->addRule(array('pwd_new', 'pwd_retype'), $this->msg['pwd_nomatch'], 'compare', null, 'client');
				$ret .= $form->toHtml();
			}
			return($ret);
		}
		return($ret);
	}
};
?>