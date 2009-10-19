<?php
try {
	$kateglo = new PDO('mysql:host=localhost;dbname=kateglo', 'root', 'root');
	$kateglox = new PDO('mysql:host=localhost;dbname=kateglox', 'root', 'root');

	/* Migrate LEMMA*/
//	
//	foreach($kateglo->query('SELECT * FROM phrase ORDER BY phrase;') as $row) {
//		$sql = "INSERT INTO lemma (lemma_name) VALUES ('".$row['phrase']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM lemma ORDER BY lemma_name;') as $row) {
//		print_r($row);
//	}

	/* Migrate LEMMA TYPE*/
//	$lemma = array();
//	foreach($kateglox->query('SELECT * FROM lemma;') as $row) {
//		$abbrv = $row['lemma_name'];
//		$id = $row['lemma_id'];
//		$lemma[$abbrv] = $id;
//	}
//	
//	$type = array();
//	foreach($kateglox->query('SELECT * FROM type;') as $row) {
//		$abbrv = $row['type_abbreviation'];
//		$id = $row['type_id'];
//		$type[$abbrv] = $id;
//	}
//	
//	foreach($kateglo->query('SELECT * FROM phrase ORDER BY phrase;') as $row) {
//		if($type[$row['phrase_type']] !== null){
//			$sql = "INSERT INTO lemma_type (lemma_id, type_id) VALUES ('".$lemma[$row['phrase']]."', '".$type[$row['phrase_type']]."' ); ";
//			$kateglox->query($sql);
//			echo $sql."\n";
//		}
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM lemma_type;') as $row) {
//		print_r($row);
//	}

	/* Migrate GLOSSAR PHRASE FOUND*/
//	$lemma = array();
//	foreach($kateglox->query('SELECT * FROM lemma;') as $row) {
//		$abbrv = $row['lemma_name'];
//		$id = $row['lemma_id'];
//		$lemma[$abbrv] = $id;
//	}
//	
//	$discipline = array();
//	foreach($kateglox->query('SELECT * FROM discipline;') as $row) {
//		$abbrv = $row['discipline_abbreviation'];
//		$id = $row['discipline_id'];
//		$discipline[$abbrv] = $id;
//	}
//	
//	foreach($kateglo->query('select phrase.phrase as phrase, glossary.phrase as glosphrase, glossary.original, discipline from phrase left join glossary on phrase.phrase = glossary.phrase where glossary.phrase is not null;') as $row) {
//	
//		$sql = "INSERT INTO glossary (glossary_lemma_id, glossary_locale_id, glossary_discipline_id, glossary_name) VALUES ('".$lemma[$row['phrase']]."', '1', '".$discipline[$row['discipline']]."', '".$row['original']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM glossary;') as $row) {
//		print_r($row);
//	}

	/* Migrate GLOSSAR PHRASE NOT FOUND*/
	
//	foreach($kateglo->query('select phrase.phrase as phrase, glossary.phrase as glosphrase, glossary.original, discipline from glossary left join phrase on phrase.phrase = glossary.phrase where phrase.phrase is null order by glosphrase;') as $row) {
//	
//		$sql = "INSERT INTO lemma (lemma_name) VALUES ('".$row['glosphrase']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	
//	}
		
//	$discipline = array();
//	foreach($kateglox->query('SELECT * FROM discipline;') as $row) {
//		$abbrv = $row['discipline_abbreviation'];
//		$id = $row['discipline_id'];
//		$discipline[$abbrv] = $id;
//	}
//	
//	foreach($kateglo->query('select phrase.phrase as phrase, glossary.phrase as glosphrase, glossary.original, discipline from glossary left join phrase on phrase.phrase = glossary.phrase where phrase.phrase is null;') as $row) {
//		$lemma = '';
//		foreach($kateglox->query('SELECT * FROM lemma where lemma_name = \''.$row['glosphrase'].'\';') as $lem) {
//			$lemma = $lem['lemma_id'];
//		}
//		
//		$sql = "INSERT INTO glossary (glossary_lemma_id, glossary_locale_id, glossary_discipline_id, glossary_name) VALUES ('".$lemma."', '1', '".$discipline[$row['discipline']]."', '".$row['original']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM glossary;') as $row) {
//		print_r($row);
//	}


	/* Migrate DEFINITION*/
//	$lemma = array();
//	foreach($kateglox->query('SELECT * FROM lemma;') as $row) {
//		$abbrv = $row['lemma_name'];
//		$id = $row['lemma_id'];
//		$lemma[$abbrv] = $id;
//	}
//	
//	$lexical = array();
//	foreach($kateglox->query('SELECT * FROM lexical;') as $row) {
//		$abbrv = $row['lexical_abbreviation'];
//		$id = $row['lexical_id'];
//		$lexical[$abbrv] = $id;
//	}
//	
//	foreach($kateglo->query('select phrase.phrase as phrase, phrase.lex_class as plex, definition.lex_class as dlex, def_text from phrase left join definition on phrase.phrase = definition.phrase WHERE definition.phrase IS NOT NULL;') as $row) {
//		$lex = '';
//		
//		if(trim($row['dlex']) != ''){
//			if($row['dlex'] === 'p'){
//				$lex = $lexical['pron'];
//			}else{
//				$lex = $lexical[$row['dlex']];
//			}
//		}else{
//			$lex = $lexical[$row['plex']];
//		}
//		$sql = "INSERT INTO definition (definition_lemma_id, definition_lexical_id, definition_text) VALUES (".$lemma[$row['phrase']].", ".$lex.", '".$row['def_text']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM definition;') as $row) {
//		print_r($row);
//	}
	
	/* MIGRATE PROVERB*/
//	$phrase = array();
//	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
//		$phrase[$row['phrase_name']] = $row['phrase_id'];
//	}
//	
//	foreach($kateglo->query('SELECT * FROM proverb ORDER BY prv_uid;') as $row) {
//		$sql = "INSERT INTO proverb (proverb_phrase_id, proverb_text, proverb_meaning) VALUES (".$phrase[$row['phrase']].", '".$row['proverb']."', '".$row['meaning']."');";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM proverb;') as $row) {
//		print_r($row);
//	}
	
	
	/* MIGRATE RELATION */
//	$phrase = array();
//	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
//		$phrase[$row['phrase_name']] = $row['phrase_id'];
//	}
//	
//	$relationType = array();
//	foreach($kateglox->query('SELECT * FROM relation_type;') as $row) {
//		$relationType[$row['relation_type_abbreviation']] = $row['relation_type_id'];
//	}
//	
//	foreach($kateglo->query('SELECT * FROM relation;') as $row) {
//		$sql = "INSERT INTO relation (relation_type_id, relation_phrase_id, relation_phrase_relation) VALUES (".$relationType[$row['rel_type']].", ".$phrase[$row['root_phrase']].", ".$phrase[$row['related_phrase']].");";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
//	
//	$phrase = array();
//	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
//		$phrase[$row['phrase_name']] = $row['phrase_id'];
//	}
//	
//	foreach($kateglo->query('SELECT phrase, def_text FROM definition;') as $row) {
//		$sql = "INSERT INTO definition (definition_phrase_id, definition_text) VALUES (".$phrase[$row['phrase']].", '".$row['def_text']."');";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
	$kateglo = null;
	$kateglox = null;
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
