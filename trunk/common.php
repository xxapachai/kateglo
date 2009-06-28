<?php
/**
 * Library of common functions
 */

define(PROCESS_NONE, 0); // mark no process
define(PROCESS_SUCCEED, 1); // mark process succeed
define(PROCESS_FAILED, 2); // mark process failed

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
	global $msg, $auth, $is_post;
	$welcome = $auth->checkAuth() ? 'login_success' : 'login_welcome';
	$welcome = $msg[$welcome];
	if ($is_post && !$auth->checkAuth()) $welcome = $msg['login_failed'] . ' ' . $welcome;

	$ret .= '<h1>' . $msg['login'] . '</h1>' . LF;
	if (!$auth->checkAuth()) $ret .= sprintf('<p>%1$s</p>' . LF, $msg['login_beta']);
	$ret .= sprintf('<p>%1$s</p>' . LF, $welcome);

	if (!$auth->checkAuth())
	{
		$form = new form('login_form', null, './?mod=user&action=login');
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
	$form->addElement('text', 'phrase', $msg['enter_phrase'],
		array('size' => 15, 'maxlength' => 255));
	$form->addElement('select', 'mod', null, array(
		'dictionary' => $msg['dictionary'],
		'glossary' => $msg['glossary'],
		'proverb' => $msg['proverb'],
	));
	$form->addElement('submit', 'search', $msg['search_button']);

	$ret .= $form->begin_form();
	$ret .= '<table cellpadding="0" cellspacing="0" width="100%"><tr>' . LF;

	// logo
	$ret .= '<td width="1%">' . LF;
	$ret .= '<a href="./"><img src="images/logo.png" width="32" height="32" border="0" alt="Kateglo" title="Kateglo" /></a>' . LF;
	$ret .= '</td>' . LF;

	// search form
	$template = '<td style="padding-right:2px;">%1$s</td>' . LF;
	$ret .= '<td><table cellpadding="0" cellspacing="0"><tr>' . LF;
	$ret .= sprintf($template, $form->get_element('search'));
	$ret .= sprintf($template, $form->get_element('phrase'));
	$ret .= sprintf($template, $msg['search_in']);
	$ret .= sprintf($template, $form->get_element('mod'));
	$ret .= '</tr></table></td>' . LF;

	// navigation
	$ret .= '<td align="right">' . LF;
	if ($auth->checkAuth())
	{
		$ret .= sprintf('<strong>%3$s</strong> | <a href="%5$s">%4$s</a> | <a href="%2$s">%1$s</a>' . LF,
			$msg['logout'], './?mod=user&action=logout',
			$auth->getUsername(),
			$msg['change_pwd'], './?mod=user&action=password'
		);
	}
	else
		$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['login'], './?mod=user&action=login');
	$ret .= '</td>' . LF;

	$ret .= '</tr></table>' . LF;
	$ret .= $form->end_form();

	return($ret);
}

/**
 * @return Search form HTML
 */
function show_header2()
{
	global $msg, $auth, $db;
	global $_GET;

	$form = new form('search_form', 'get');
	$form->setup($msg);
	$form->addElement('text', 'phrase', $msg['enter_phrase'],
		array('size' => 20, 'maxlength' => 255));
	$form->addElement('select', 'mod', null, array(
		'dictionary' => $msg['dictionary'],
		'glossary' => $msg['glossary'],
		'proverb' => $msg['proverb'],
	));
	$form->addElement('submit', 'search', $msg['search_button']);

	$ret .= $form->begin_form();
	$ret .= '<table cellpadding="0" cellspacing="0" width="100%"><tr>' . LF;
	// logo
	$ret .= '<td width="1%">' . LF;
	$ret .= '<a href="./"><img src="images/kateglo50.png" width="162" height="50" border="0" alt="Kateglo" title="Kateglo" /></a>' . LF;
	$ret .= '</td>' . LF;

	// search form
	$template = '<td style="padding-left:5px;">%1$s</td>' . LF;
	$ret .= '<td align="right"><table cellpadding="0" cellspacing="0"><tr>' . LF;
	$ret .= sprintf($template, $form->get_element('phrase'));
	$ret .= sprintf($template, $msg['search_in']);
	$ret .= sprintf($template, $form->get_element('mod'));
	$ret .= sprintf($template, $form->get_element('search'));
	$ret .= '</tr></table></td>' . LF;
	$ret .= '</tr></table>' . LF;

	// navigation
//	$ret .= '<table cellpadding="0" cellspacing="0" width="100%"><tr>' . LF;
//	$ret .= '<td align="right">' . LF;
//	if ($auth->checkAuth())
//	{
//		$ret .= sprintf('<strong>%3$s</strong> | <a href="%5$s">%4$s</a> | <a href="%2$s">%1$s</a>' . LF,
//			$msg['logout'], './?mod=user&action=logout',
//			$auth->getUsername(),
//			$msg['change_pwd'], './?mod=user&action=password'
//		);
//	}
//	else
//		$ret .= sprintf('<a href="%2$s">%1$s</a>' . LF, $msg['login'], './?mod=user&action=login');
//	$ret .= '</td>' . LF;
//	$ret .= '</tr></table>' . LF;

	$ret .= $form->end_form();

	return($ret);
}

/**
 * Footer
 */
function show_footer()
{
	global $msg;
	$ret .= sprintf('<p class="footer">' .
		'<span style="float:right;">' .
		'<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">' .
		'<img title="%6$s" alt="%6$s" style="border-width:0" ' .
		'src="./images/cc-by-nc-sa.png" />' .
		'</a></span>' .
		'<a href="%2$s">%3$s</a>' .
		'&nbsp;&#183;&nbsp;' .
		'<a href="%7$s">API</a>' .
		'&nbsp;&#183;&nbsp;' .
		'<a href="%4$s">%5$s</a>' .
		'</p>' . LF,
		APP_SHORT,
		'./?mod=doc&doc=README.txt',
		APP_VERSION,
		'./?mod=comment',
		$msg['comment_link'],
		'CC-BY-NC-SA',
		'./api.php'
	);
	return($ret);
}

/**
 * Footer
 */
function show_footer2()
{
	global $msg;
	$ret .= sprintf('<p class="footer">' .
		'<span style="float:right;">' .
		'<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">' .
		'<img title="%6$s" alt="%6$s" style="border-width:0" ' .
		'src="./images/cc-by-nc-sa.png" />' .
		'</a></span>' .
		'<a href="%2$s">%3$s</a>' .
		'&nbsp;&#183;&nbsp;' .
		'<a href="%7$s">API</a>' .
		'&nbsp;&#183;&nbsp;' .
		'<a href="%4$s">%5$s</a>' .
		'</p>' . LF,
		APP_SHORT,
		'./?mod=doc&doc=README.txt',
		APP_VERSION,
		'./?mod=comment',
		$msg['comment_link'],
		'CC-BY-NC-SA',
		'./api.php'
	);
	return($ret);
}

/**
 * External stat
 */
function get_external_stat()
{

	// gostats
	$ret .= '<!-- GoStats JavaScript Based Code -->';
	$ret .= '<script type="text/javascript" src="http://gostats.com/js/counter.js"></script>';
	$ret .= '<script type="text/javascript">_gos=\'gostats.com\';_goa=728945;_got=5;_goi=1;_goz=0;_gol=\'web traffic software\';_GoStatsRun();</script>';
	$ret .= '<noscript><a target="_blank" title="web traffic software" href="http://gostats.com"><img alt="web traffic software" src="http://gostats.com/bin/count/a_728945/t_5/i_1/counter.png"  style="border-width:0" /></a></noscript>';
	$ret .= '<!-- End GoStats JavaScript Based Code -->' . LF;

	// google analytics
	$ret .= '<script type="text/javascript"> var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E")); </script> <script type="text/javascript"> try { var pageTracker = _gat._getTracker("UA-2254800-2"); pageTracker._trackPageview(); } catch(err) {}</script>' . LF;

	return($ret);
}
?>