<?php 
function tpl_4b56c824_index__eKY9VmetFYs46A9Gc3m4WA($tpl, $ctx) {
$_thistpl = $tpl ;
$_translator = $tpl->getTranslator() ;
$ctx->setXmlDeclaration('<?xml version="1.0" encoding="UTF-8"?>',false) ;
?>

<?php $ctx->setDocType('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',false); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="/libs/extjs/resources/css/ext-all.css"/>
<link rel="stylesheet" type="text/css" href="/styles/main.css"/>
<script type="text/javascript" src="/libs/extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="/libs/extjs/ext-all.js"></script>
<title>Kateglo - Search</title>
</head>
<body>
	<div style="width:600px;margin-top:20px;margin-left:auto;margin-right:auto;">
	<form action="/search" method="get">
   	<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
   	<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
   		<h3 style="margin-bottom:5px;">Search Kateglo</h3>
		<div id="ext-gen4" class="x-form-field-wrap x-form-field-trigger-wrap" style="width: 570px;">
			<?php 
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->search, 'getFieldValue')))):  ;
$_tmp_1 = ' value="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<input id="searchLemma" class=" x-form-text x-form-field" type="text" name="query" size="40" onfocus="this.select();" style="width: 562px;" autocomplete="off"<?php echo $_tmp_1 ?>/>
			<img alt="" id="ext-gen5" class="x-form-trigger x-form-arrow-trigger" src="/libs/extjs/resources/images/default/s.gif" style="display: none;"/>
		</div>		
		<div style="padding-top:4px;">
           Search: <?php 
$ctx = $tpl->pushContext() ;
$ctx->context = $ctx->path($ctx->search, 'getLemmaRadioValue') ;
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->search, 'getRadioName')))):  ;
$_tmp_1 = ' name="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->search, 'getLemmaRadioValue')))):  ;
$_tmp_2 = ' value="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
?>
<input style="valign: middle;" checked="checked" type="radio"<?php echo $_tmp_1 ?><?php echo $_tmp_2 ?>/><?php $ctx = $tpl->popContext(); ?>
 
           <img style="valign: middle; border: 1px solid black;" src="/images/indonesia.png" alt="indonesia"/> - <img style="valign: middle; border: 1px solid black;" src="/images/indonesia.png" alt="indonesia"/>
			&nbsp;&nbsp; &nbsp; <?php 
$ctx = $tpl->pushContext() ;
$ctx->context = $ctx->path($ctx->search, 'getGlossaryRadioValue') ;
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->search, 'getRadioName')))):  ;
$_tmp_1 = ' name="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->search, 'getGlossaryRadioValue')))):  ;
$_tmp_2 = ' value="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
?>
<input style="valign: middle;" type="radio"<?php echo $_tmp_1 ?><?php echo $_tmp_2 ?>/><?php $ctx = $tpl->popContext(); ?>
 
			<img style="valign: middle; border: 1px solid black;" src="/images/indonesia.png" alt="indonesia"/> - <img style="valign: middle; border: 1px solid black;" src="/images/uk.png" alt="inggris"/>
        </div>
	 </div></div></div>
	 </form>
    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
	</div>
	<?php if ($ctx->hits->count() > 0 && $ctx->search->getLemmaRadioValue() == $ctx->search->getCheckedRadio()): ; ?>
<div>
		<table border="1">
			<thead>
			<tr><th align="center">Lemma</th><th align="center">Type</th><th align="center">Lexical</th><th align="center">Definitions</th></tr>
			</thead>
			<tbody>
				<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->hit = new PHPTAL_RepeatController($ctx->hits)
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->hit as $ctx->hit): ;
?>
<tr><td valign="middle"><?php $tpl->getGlobalContext()->lemma = $ctx->path($ctx->hit, 'lemma'); ?>
<a href="lemma/<?php echo phptal_escape($ctx->lemma) ?>"><?php echo phptal_escape($ctx->lemma) ?></a></td><td valign="middle"><?php echo phptal_escape($ctx->path($ctx->hit, 'type')); ?>
</td><td valign="middle"><?php echo phptal_escape($ctx->path($ctx->hit, 'lexical')); ?>
</td><td valign="middle"><?php echo phptal_tostring(nl2br($ctx->hit->definition)); ?>
</td></tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

			</tbody>
		</table>
	</div><?php endif; ?>

	<?php if ($ctx->hits->count() > 0 && $ctx->search->getGlossaryRadioValue() == $ctx->search->getCheckedRadio()): ; ?>
<div>
		<table border="1">
			<thead>
			<tr><th align="center">Glossary</th><th align="center">Lemma</th><th align="center">Locale</th><th align="center">Discipline</th></tr>
			</thead>
			<tbody>
				<?php 
$_tmp_2 = $ctx->repeat ;
$_tmp_2->hit = new PHPTAL_RepeatController($ctx->hits)
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_2->hit as $ctx->hit): ;
?>
<tr><td><?php echo phptal_escape($ctx->path($ctx->hit, 'glossary')); ?>
</td><td><?php $tpl->getGlobalContext()->lemma = $ctx->path($ctx->hit, 'lemma'); ?>
<a href="lemma/<?php echo phptal_escape($ctx->lemma) ?>"><?php echo phptal_escape($ctx->lemma) ?></a></td><td><?php echo phptal_escape($ctx->path($ctx->hit, 'localeName')); ?>
</td><td><?php echo phptal_escape($ctx->path($ctx->hit, 'disciplineName')); ?>
</td></tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

			</tbody>
		</table>
	</div><?php endif; ?>

</body>
</html><?php 
/* end */ ;

}

?><?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from C:\Dokumente und Einstellungen\arpu\pdtworkspace\kateglo\application\views\scripts\search\index.html (edit that file instead) */; ?>