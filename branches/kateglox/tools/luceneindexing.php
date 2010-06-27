<?php
date_default_timezone_set ( "Europe/Berlin" );

defined('DOCUMENT_ROOT')
|| define('DOCUMENT_ROOT', realpath(dirname(__FILE__)));

defined('ZF_PATH')
|| define('ZF_PATH', realpath(DOCUMENT_ROOT . '/../../ZendFramework/library'));

defined('INDEX_PATH')
|| define('INDEX_PATH', realpath(DOCUMENT_ROOT . '/../build/index'));

set_include_path(implode(PATH_SEPARATOR, array(
realpath(DOCUMENT_ROOT . '/../library'),
realpath(ZF_PATH),
get_include_path(),
)));
require_once 'Zend/Search/Lucene.php';
require_once 'Zend/Search/Lucene/Field.php';
require_once 'Zend/Search/Lucene/Document.php';

//$kateglox = new PDO('mysql:host=localhost;dbname=kateglox', 'root', 'mysql123');
$kateglox = new PDO('mysql:host=localhost;dbname=kateglox', 'root', 'root', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//$kateglox = new PDO('mysql:host=127.0.0.1;port=8889;dbname=kateglox', 'root', 'root', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

$lemmaIndex = Zend_Search_Lucene::create(INDEX_PATH.'/lemma_index');

foreach($kateglox->query('SELECT lemma.*, type.* FROM lemma LEFT JOIN lemma_type ON lemma.lemma_id = lemma_type.lemma_id LEFT JOIN type ON type.type_id = lemma_type.type_id  ORDER BY lemma.lemma_name;') as $lemma) {
	$doc = new Zend_Search_Lucene_Document();
	
	$doc->addField(Zend_Search_Lucene_Field::text('lemma', $lemma['lemma_name']));
	$doc->addField(Zend_Search_Lucene_Field::unIndexed('id', $lemma['lemma_id']));
	$doc->addField(Zend_Search_Lucene_Field::text('type', $lemma['type_abbreviation']));

	$definitions = '';
	$lexicals = array();
	$definitionSources = '';
	$definitionSourceTypes = array();
	foreach($kateglox->query('SELECT definition_id, definition_text, lexical_name, lexical_abbreviation FROM definition LEFT JOIN lexical ON definition_lexical_id = lexical_id WHERE definition_lemma_id = '.$lemma['lemma_id'].'; ') as $definition){
		if($definitions != ''){
			$definitions .= "\n\n";
		}
		$definitions .= $definition['definition_text'];

		if($definition['lexical_abbreviation'] != ''){
			if(!in_array($definition['lexical_abbreviation'], $lexicals)){
				$lexicals[] = $definition['lexical_abbreviation'];
			}
		}

		foreach($kateglox->query('SELECT source_label, source_type_name, source_type_abbreviation FROM definition_source LEFT JOIN source ON definition_source.source_id = source.source_id LEFT JOIN source_type on source_type.source_type_id = source.source_type_id WHERE definition_id = '.$definition['definition_id'].';') as $source){
			if($definitionSources != ''){
				$definitionSources .= ' ';
			}
			$definitionSources .= $source['source_label'];
			if($source['source_type_abbreviation'] != ''){
				if(!in_array($source['source_type_abbreviation'], $definitionSourceTypes)){
					$definitionSourceTypes[] = $source['source_type_abbreviation'];
				}
			}
		}
	}
	
	$doc->addField(Zend_Search_Lucene_Field::text('definition', $definitions));	

	$doc->addField(Zend_Search_Lucene_Field::text('lexical', implode("\n",$lexicals)));

	if($definitionSources != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('defSource', $definitionSources));
	}

	if(count($definitionSourceTypes) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('defSourceType', implode(' ',$definitionSourceTypes)));
	}


	$relationTypes = array();
	foreach($kateglox->query('SELECT relation_type_name, relation_type_abbreviation FROM relation LEFT JOIN relation_type ON relation.relation_type_id = relation_type.relation_type_id WHERE relation_parent_id = '.$lemma['lemma_id'].'; ') as $relationType){
		if(!in_array($relationType['relation_type_name'], $relationTypes)){
			$relationTypes[] = $relationType['relation_type_abbreviation'];
		}
	}

	if(count($relationTypes) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('relationType', implode(' ',$relationTypes)));
	}


	$glossaries = '';
	$locales = array();
	$disciplines = array();
	$glossarySources = '';
	$glossarySourcesType = array();
	foreach($kateglox->query('SELECT glossary_id, glossary_name, locale_name, locale_abbreviation, discipline_name FROM glossary LEFT JOIN locale ON glossary.glossary_locale_id = locale.locale_id LEFT JOIN discipline ON glossary_discipline_id = discipline.discipline_id WHERE glossary_lemma_id = '.$lemma['lemma_id'].'; ') as $glossary){
		if($glossaries != ''){
			$glossaries .= ' ';
		}
		$glossaries .= $glossary['glossary_name'];

		if($glossary['locale_abbreviation'] != ''){
			if(!in_array($glossary['locale_abbreviation'], $locales)){
				$locales[] = $glossary['locale_abbreviation'];
			}
		}
		
		if($glossary['discipline_name'] != ''){
			if(!in_array($glossary['discipline_name'], $disciplines)){
				$disciplines[] = $glossary['discipline_name'];
			}
		}
		
		foreach($kateglox->query('SELECT source_label, source_type_name, source_type_abbreviation FROM glossary_source LEFT JOIN source ON glossary_source.source_id = source.source_id LEFT JOIN source_type on source_type.source_type_id = source.source_type_id WHERE glossary_id = '.$glossary['glossary_id'].';') as $source){
			if($glossarySources != ''){
				$glossarySources .= ' ';
			}
			$glossarySources .= $source['source_label'];
			if($source['source_type_abbreviation'] != ''){
				if(!in_array($source['source_type_abbreviation'], $glossarySourcesType)){
					$glossarySourcesType[] = $source['source_type_abbreviation'];
				}
			}
		}
	}
	
	if($glossaries != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('glossary', $glossaries));
	}

	if(count($locales) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('locale', implode(' ',$locales)));
	}
	
	if(count($disciplines) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('discipline', implode(' ',$disciplines)));
	}

	if($glossarySources != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('gloSource', $glossarySources));
	}

	if(count($glossarySourcesType) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('gloSourceType', implode(' ',$glossarySourcesType)));
	}

	echo $lemma['lemma_name']."\n";
	
	$lemmaIndex->addDocument($doc);
}

$glossaryIndex = Zend_Search_Lucene::create(INDEX_PATH.'/glossary_index');

foreach($kateglox->query('SELECT glossary.*, locale.*, discipline.*, lemma.* FROM glossary LEFT JOIN lemma ON glossary.glossary_lemma_id = lemma.lemma_id LEFT JOIN locale ON glossary.glossary_locale_id = locale.locale_id LEFT JOIN discipline ON glossary.glossary_discipline_id = discipline.discipline_id  ORDER BY glossary.glossary_name;') as $glossary) {
	
	$doc = new Zend_Search_Lucene_Document();

	$doc->addField(Zend_Search_Lucene_Field::text('glossary', $glossary['glossary_name']));
	$doc->addField(Zend_Search_Lucene_Field::text('lemma', $glossary['lemma_name']));
	$doc->addField(Zend_Search_Lucene_Field::text('localeName', $glossary['locale_name']));
	$doc->addField(Zend_Search_Lucene_Field::text('locale', $glossary['locale_abbreviation']));
	$doc->addField(Zend_Search_Lucene_Field::text('disciplineName', $glossary['discipline_name']));
	$doc->addField(Zend_Search_Lucene_Field::text('discipline', $glossary['discipline_abbreviation']));
	$doc->addField(Zend_Search_Lucene_Field::unIndexed('glossaryId', $glossary['glossary_id']));
	
	$glossarySources = '';
	$glossarySourcesType = array();
	foreach($kateglox->query('SELECT source_label, source_type_name FROM glossary_source LEFT JOIN source ON glossary_source.source_id = source.source_id LEFT JOIN source_type on source_type.source_type_id = source.source_type_id WHERE glossary_id = '.$glossary['glossary_id'].';') as $source){
		if($glossarySources != ''){
			$glossarySources .= ' ';
		}
		$glossarySources .= $source['source_label'];
		if($source['source_type_abbreviation'] != ''){
			if(!in_array($source['source_type_abbreviation'], $glossarySourcesType)){
				$glossarySourcesType[] = $source['source_type_abbreviation'];
			}
		}
	}
	if($glossarySources != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('gloSource', $glossarySources));
	}

	if(count($glossarySourcesType) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('gloSourceType', implode(' ',$glossarySourcesType)));
	}	
	
	$definitions = '';
	$lexicals = array();
	$definitionSources = '';
	$definitionSourceTypes = array();
	foreach($kateglox->query('SELECT definition_id, definition_text, lexical_name FROM definition LEFT JOIN lexical ON definition_lexical_id = lexical_id WHERE definition_lemma_id = '.$glossary['lemma_id'].'; ') as $definition){
		if($definitions != ''){
			$definitions .= "\n\n";
		}
		$definitions .= $definition['definition_text'];

		if($definition['lexical_abbreviation'] != ''){
			if(!in_array($definition['lexical_abbreviation'], $lexicals)){
				$lexicals[] = $definition['lexical_abbreviation'];
			}
		}

		foreach($kateglox->query('SELECT source_label, source_type_name FROM definition_source LEFT JOIN source ON definition_source.source_id = source.source_id LEFT JOIN source_type on source_type.source_type_id = source.source_type_id WHERE definition_id = '.$definition['definition_id'].';') as $source){
			if($definitionSources != ''){
				$definitionSources .= ' ';
			}
			$definitionSources .= $source['source_label'];
			if($source['source_type_abbreviation'] != ''){
				if(!in_array($source['source_type_abbreviation'], $definitionSourceTypes)){
					$definitionSourceTypes[] = $source['source_type_abbreviation'];
				}
			}
		}
	}

	if($definitions != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('definition', $definitions));
	}

	if(count($lexicals) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('lexical', implode(' ',$lexicals)));
	}

	if($definitionSources != ''){
		$doc->addField(Zend_Search_Lucene_Field::unStored('defSource', $definitionSources));
	}

	if(count($definitionSourceTypes) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('defSourceType', implode(' ',$definitionSourceTypes)));
	}

	$relationTypes = array();
	foreach($kateglox->query('SELECT relation_type_name FROM relation LEFT JOIN relation_type ON relation.relation_type_id = relation_type.relation_type_id WHERE relation_parent_id = '.$glossary['lemma_id'].'; ') as $relationType){
		if(!in_array($relationType['relation_type_name'], $relationTypes)){
			$relationTypes[] = $relationType['relation_type_abbreviation'];
		}
	}

	if(count($relationTypes) > 0){
		$doc->addField(Zend_Search_Lucene_Field::unStored('relationType', implode(' ',$relationTypes)));
	}
	
	echo $glossary['glossary_name']."\n";
	$glossaryIndex->addDocument($doc);
}


$lemmaIndex = Zend_Search_Lucene::open(INDEX_PATH.'/lemma_index');
$glossaryIndex = Zend_Search_Lucene::open(INDEX_PATH.'/glossary_index');
$lemmaIndex->optimize();
$glossaryIndex->optimize();

?>