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
	$form->addElement('text', 'phrase', $msg['enter_phrase']);
	$form->addElement('select', 'mod', null, array('dict' => 'Kamus', 'glo' => 'Glosarium'));
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
function read_doc($file_name)
{
	$file_url = './docs/' . $file_name;
	if (file_exists($file_url))
		$ret = nl2br(htmlentities(file_get_contents($file_url)));
	return($ret);
}

function get_external_stat()
{

	$ret .= '<div style="display:none !important;">' . LF;

	// gostats
	$ret .= '<!-- GoStats JavaScript Based Code -->';
	$ret .= '<script type="text/javascript" src="http://gostats.com/js/counter.js"></script>';
	$ret .= '<script type="text/javascript">_gos=\'gostats.com\';_goa=728945;_got=5;_goi=1;_goz=0;_gol=\'web traffic software\';_GoStatsRun();</script>';
	$ret .= '<noscript><a target="_blank" title="web traffic software" href="http://gostats.com"><img alt="web traffic software" src="http://gostats.com/bin/count/a_728945/t_5/i_1/counter.png"  style="border-width:0" /></a></noscript>';
	$ret .= '<!-- End GoStats JavaScript Based Code -->' . LF;

	// google analytics
	$ret .= '<script type="text/javascript"> var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E")); </script> <script type="text/javascript"> try { var pageTracker = _gat._getTracker("UA-2254800-2"); pageTracker._trackPageview(); } catch(err) {}</script>' . LF;

	// statcounter
	// $ret .= '<!-- Start of StatCounter Code --> <script type="text/javascript"> var sc_project=4786656; var sc_invisible=1; var sc_partition=54; var sc_click_stat=1; var sc_security="f8096c17"; </script><script type="text/javascript" src="http://www.statcounter.com/counter/counter.js"></script><noscript><div class="statcounter"><a title="counter for blogspot" href="http://www.statcounter.com/blogger/" target="_blank"><img class="statcounter" src="http://c.statcounter.com/4786656/0/f8096c17/1/" alt="counter for blogspot" style="display:none;" /></a></div></noscript> <!-- End of StatCounter Code -->' . LF;

	// sitemeter
	$ret .= '<!-- Site Meter --> <script type="text/javascript" src="http://s18.sitemeter.com/js/counter.js?site=s18kateglo"> </script> <noscript> <a href="http://s18.sitemeter.com/stats.asp?site=s18kateglo" target="_top"> <img src="http://s18.sitemeter.com/meter.asp?site=s18kateglo" alt="Site Meter" border="0" style="display:none;" /></a> </noscript> <!-- Copyright (c)2009 Site Meter -->' . LF;

	$ret .= '</div>' . LF;
	return($ret);
}
?>