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
	global $msg, $auth, $db;
	global $_GET;

	$form = new form('search_form', 'get');
	$form->setup($msg);
	$form->addElement('text', 'phrase', $msg['enter_phrase'], array('style'=>'height:20px;'));
	$form->addElement('select', 'mod', null,
		array('dict' => 'Kamus', 'glo' => 'Glosarium'),
		array('style'=>'height:20px;', 'onchange'=>'this.form.elements[\'dc\'].style.display = (this.value == \'glo\' ? \'block\' : \'none\');this.form.elements[\'lang\'].style.display = (this.value == \'glo\' ? \'block\' : \'none\');')
		);
	$form->addElement('select', 'dc', null,
		$db->get_row_assoc('SELECT discipline, discipline_name FROM discipline', 'discipline', 'discipline_name'),
		array('style'=>'height:20px;' . ($_GET['mod'] != 'glo' ? 'display:none;' : ''))
		);
	$form->addElement('select', 'lang', null,
		$db->get_row_assoc('SELECT lang, lang_name FROM language', 'lang', 'lang_name'),
		array('style'=>'height:20px;' . ($_GET['mod'] != 'glo' ? 'display:none;' : ''))
		);
	$form->addElement('submit', 'search', $msg['search_button']);

	$ret .= $form->begin_form();
	$ret .= '<table cellpadding="0" cellspacing="0" width="100%"><tr>' . LF;

	// logo
	$ret .= '<td width="1%">' . LF;
	$ret .= '<a href="./"><img src="images/logo.png" width="32" height="32" alt="Kateglo" title="Kateglo" /></a>' . LF;
	$ret .= '</td>' . LF;

	// search form
	$ret .= '<td><table cellpadding="0" cellspacing="0"><tr>' . LF;
	$template = '<td style="padding-right:2px;">%1$s</td>' . LF;
	$ret .= sprintf($template, $form->get_element('search'));
	$ret .= sprintf($template, $form->get_element('phrase'));
	$ret .= sprintf($template, $msg['search_in']);
	$ret .= sprintf($template, $form->get_element('mod'));
	$ret .= sprintf($template, $form->get_element('dc'));
	$ret .= sprintf($template, $form->get_element('lang'));
	$ret .= '</tr></table></td>' . LF;

	// navigation
	$ret .= '<td align="right">' . LF;
	if ($auth->checkAuth())
		$ret .= sprintf('<strong>%3$s</strong> | <a href="%2$s">%1$s</a>' . LF,
			$msg['logout'], './?mod=auth&action=logout', $auth->getUsername());
	else
		$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['login'], './?mod=auth&action=login');
	$ret .= '</td>' . LF;

	$ret .= '</tr></table>' . LF;
	$ret .= $form->end_form();

	return($ret);
}
?>