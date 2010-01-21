<?php 
function tpl_4b4c56fc_index__b7doMdCWdmT8oXqHpDLJcg($tpl, $ctx) {
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
<script type="text/javascript" src="/javascripts/search.js"></script>
<title><?php echo phptal_escape($ctx->path($ctx->lemma, 'getLemma')); ?>
</title>
</head>
<body>
<div style="width:600px;margin-top:20px;margin-left:auto;margin-right:auto;">
   	<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
   	<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
   		<h3 style="margin-bottom:5px;">Search Kateglo</h3>
		<?php 
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->search, 'getFieldValue')))):  ;
$_tmp_1 = ' value="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<input type="text" onfocus="this.select();" size="40" name="searchLemma" id="searchLemma"<?php echo $_tmp_1 ?>/>
		<?php 
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->search, 'getFieldValue')))):  ;
$_tmp_1 = ' value="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<input type="text" onfocus="this.select();" size="40" name="searchGlossary" id="searchGlossary" style="display:none;"<?php echo $_tmp_1 ?>/>
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
<input checked="checked" type="radio" onclick="changeContext('<?php echo phptal_escape($ctx->context) ?>');"<?php echo $_tmp_1 ?><?php echo $_tmp_2 ?>/><?php $ctx = $tpl->popContext(); ?>
 Lemma
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
<input type="radio" onclick="changeContext('<?php echo phptal_escape($ctx->context) ?>');"<?php echo $_tmp_1 ?><?php echo $_tmp_2 ?>/><?php $ctx = $tpl->popContext(); ?>
 Glossary
        </div>
	 </div></div></div>
    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
</div>
<div>
	<table border="1">
		<tr><?php 
$tpl->getGlobalContext()->getLemma = $ctx->path($ctx->lemma, 'getLemma') ;
$ctx = $tpl->pushContext() ;
$ctx->syllabel = utf8_encode($ctx->lemma->getSyllabel()->getSyllabel()) ;
?>
<td colspan="3" style="text-align: center; font-weight: bold; font-size: 14px;"><?php echo phptal_escape($ctx->getLemma) ?> (<?php echo phptal_escape($ctx->syllabel) ?>)</td><?php $ctx = $tpl->popContext(); ?>
</tr>
		<tr><td colspan="3" style="font-weight: bold">Types</td></tr>
		<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->type = new PHPTAL_RepeatController($ctx->path($ctx->lemma, 'getTypes'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->type as $ctx->type): ;
?>
<tr><td><?php echo phptal_escape($ctx->path($ctx->type, 'getId')); ?>
</td><td colspan="2"><?php echo phptal_escape($ctx->path($ctx->type, 'getType')); ?>
</td></tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

		<?php if ($ctx->lemma->getRelations()->count() > 0): ; ?>
<tr><td colspan="3" style="font-weight: bold">Relations</td></tr><?php endif; ?>

		<?php 
$_tmp_2 = $ctx->repeat ;
$_tmp_2->relation = new PHPTAL_RepeatController($ctx->path($ctx->lemma, 'getRelations'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_2->relation as $ctx->relation): ;
?>
<tr>
			<td><?php echo phptal_escape($ctx->path($ctx->relation, 'getType/getType')); ?>
</td>
			<td colspan="2">
				<?php 
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->relation, 'getChild/getLemma')))):  ;
$_tmp_1 = ' href="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<a<?php echo $_tmp_1 ?>><?php echo phptal_escape($ctx->path($ctx->relation, 'getChild/getLemma')); ?>
</a>
			</td>
		</tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

		<?php if ($ctx->lemma->getDefinitions()->count() > 0): ; ?>
<tr><td colspan="3" style="font-weight: bold">Definitions</td></tr><?php endif; ?>

		<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->definition = new PHPTAL_RepeatController($ctx->path($ctx->lemma, 'getDefinitions'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->definition as $ctx->definition): ;
?>
<tr>
			<td><?php echo phptal_escape($ctx->path($ctx->definition, 'getLexical/getLexical')); ?>
</td>
			<?php if ($ctx->definition->getSources()->count() > 0): ; ?>
<td><?php echo phptal_escape($ctx->path($ctx->definition, 'getDefinition')); ?>
</td><?php endif; ?>

			<?php 
if ($ctx->definition->getSources()->count() > 0):  ;
$_tmp_2 = $ctx->repeat ;
$_tmp_2->definitionSource = new PHPTAL_RepeatController($ctx->path($ctx->definition, 'getSources'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_2->definitionSource as $ctx->definitionSource): ;
?>
<td><?php 
if (NULL !== ($_tmp_3 = ($ctx->path($ctx->definitionSource, 'getUrl')))):  ;
$_tmp_3 = ' href="'.phptal_escape($_tmp_3).'"' ;
else:  ;
$_tmp_3 = '' ;
endif ;
if (NULL !== ($_tmp_4 = ('_blank'))):  ;
$_tmp_4 = ' target="'.phptal_escape($_tmp_4).'"' ;
else:  ;
$_tmp_4 = '' ;
endif ;
?>
<a<?php echo $_tmp_3 ?><?php echo $_tmp_4 ?>><?php echo phptal_escape($ctx->path($ctx->definitionSource, 'getLabel')); ?>
</a></td><?php 
endforeach ;
$ctx = $tpl->popContext() ;
endif ;
?>

			<?php if ($ctx->definition->getSources()->count() == 0): ; ?>
<td colspan="2"><?php echo phptal_escape($ctx->path($ctx->definition, 'getDefinition')); ?>
</td><?php endif; ?>

		</tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

		<?php if ($ctx->lemma->getGlossaries()->count() > 0): ; ?>
<tr><td colspan="3" style="font-weight: bold">Glossaries</td></tr><?php endif; ?>

		<?php 
$_tmp_3 = $ctx->repeat ;
$_tmp_3->glossary = new PHPTAL_RepeatController($ctx->path($ctx->lemma, 'getGlossaries'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_3->glossary as $ctx->glossary): ;
?>
<tr>
			<td><?php echo phptal_escape($ctx->path($ctx->glossary, 'getDiscipline/getDiscipline')); ?>
</td>
			<?php 
$tpl->getGlobalContext()->glossaryLocale = $ctx->path($ctx->glossary, 'getLocale/getLocale') ;
$ctx = $tpl->pushContext() ;
$ctx->glossaryText = $ctx->path($ctx->glossary, 'getGlossary') ;
if ($ctx->glossary->getSources()->count() > 0):  ;
?>
<td>(<?php echo phptal_escape($ctx->glossaryLocale) ?>) <?php echo phptal_escape($ctx->glossaryText) ?></td><?php 
endif ;
$ctx = $tpl->popContext() ;
?>

			<?php 
if ($ctx->glossary->getSources()->count() > 0):  ;
$_tmp_4 = $ctx->repeat ;
$_tmp_4->glossarySource = new PHPTAL_RepeatController($ctx->path($ctx->glossary, 'getSources'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_4->glossarySource as $ctx->glossarySource): ;
?>
<td><?php 
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->glossarySource, 'getUrl')))):  ;
$_tmp_2 = ' href="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
if (NULL !== ($_tmp_1 = ('_blank'))):  ;
$_tmp_1 = ' target="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<a<?php echo $_tmp_2 ?><?php echo $_tmp_1 ?>><?php echo phptal_escape($ctx->path($ctx->glossarySource, 'getLabel')); ?>
</a></td><?php 
endforeach ;
$ctx = $tpl->popContext() ;
endif ;
?>

			<?php 
$tpl->getGlobalContext()->glossaryLocale = $ctx->path($ctx->glossary, 'getLocale/getLocale') ;
$ctx = $tpl->pushContext() ;
$ctx->glossaryText = $ctx->path($ctx->glossary, 'getGlossary') ;
if ($ctx->glossary->getSources()->count() == 0):  ;
?>
<td colspan="2">(<?php echo phptal_escape($ctx->glossaryLocale) ?>) <?php echo phptal_escape($ctx->glossaryText) ?></td><?php 
endif ;
$ctx = $tpl->popContext() ;
?>

		</tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

	</table>
</div>
</body>
</html><?php 
/* end */ ;

}

?><?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from C:\Dokumente und Einstellungen\arpu\pdtworkspace\kateglo\application\views\scripts\lemma\index.html (edit that file instead) */; ?>