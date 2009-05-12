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
	global $msg, $auth;

	$welcome = $auth->checkAuth() ? 'login_success' : 'login_welcome';
	$welcome = $msg[$welcome];
	if ($status < 0) $welcome = $msg['login_failed'] . ' ' . $welcome;
	$ret .= sprintf('<p>%1$s</p>' . LF, $welcome);

	if (!$auth->checkAuth())
	{
		$form = new form('login_form', null, './?mod=auth&action=login');
		$form->setup($msg);
		$form->addElement('text', 'username', $msg['username']);
		$form->addElement('password', 'password', $msg['password']);
		$form->addElement('submit', null, $msg['login']);
		$form->addRule('username', sprintf($msg['required_alert'], $msg['username']), 'required', null, 'client');
		$form->addRule('password', sprintf($msg['required_alert'], $msg['password']), 'required', null, 'client');
		$ret .= $form->toHtml();
	}
		return($ret);
}

/**
 * @return Search form HTML
 */
function show_header()
{
	global $msg, $auth;

	$form = new form('search_form', 'get');
	$form->setup($msg);
	$form->addElement('text', 'phrase', $msg['enter_phrase']);
	$form->addElement('submit', 'search', $msg['search']);

	// navigation
	$ret .= '<div style="float:right;">';
	$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['home'], './');
	$ret .= ' | ';
	if ($auth->checkAuth())
		$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['logout'], './?mod=auth&action=logout');
	else
		$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['login'], './?mod=auth&action=login');
	$ret .= '</div>';

	// search form
	$ret .= $form->begin_form();
	$ret .= $form->get_element('phrase');
	$ret .= $form->get_element('search');
	$ret .= $form->end_form();
	return($ret);
}
?>