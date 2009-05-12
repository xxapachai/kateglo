<?php
/**
 * Library of common functions
 */

/**
 * Redirect to a certain URL page
 *
 * @param $url
 */
function redir($url)
{
	header('Location:' . $url);
}

function login($username = null, $status = null, &$auth = null)
{
	global $msg;
	$form = new form('login_form');
	$form->setup($msg);
	$form->addElement('text', 'username', $msg['username']);
	$form->addElement('password', 'password', $msg['password']);
	$form->addElement('submit', null, $msg['login']);
	$form->addRule('username', sprintf($msg['required_alert'], $msg['username']), 'required', null, 'client');
	$form->addRule('password', sprintf($msg['required_alert'], $msg['password']), 'required', null, 'client');
	return($form->toHtml());
}
?>