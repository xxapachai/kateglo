<?php
try {
	$kateglo = new PDO('mysql:host=localhost;dbname=kateglo', 'root', 'mysql123');
	$kateglox = new PDO('mysql:host=localhost;dbname=kateglox', 'root', 'mysql123');

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
	
//	$newLemma = array();
//	foreach($kateglo->query('select rel_type, root_phrase,related_phrase from relation left join phrase pr on root_phrase = phrase left join phrase p on related_phrase = p.phrase where pr.phrase is not null and p.phrase is not null;') as $row) {
//		$bum = $row;
//		foreach($kateglox->query('SELECT lemma_id, lemma_name FROM lemma WHERE lemma_name = \''.$bum['root_phrase'].'\';') as $rowx) {
//			if($rowx['lemma_id'] == ''){
//				$kateglox->query('INSERT INTO lemma (lemma_name) VALUES (\''.$bum['root_phrase'].'\')');
//			}
//		}
//		foreach($kateglox->query('SELECT lemma_id, lemma_name FROM lemma WHERE lemma_name = \''.$bum['related_phrase'].'\';') as $rowx) {
//			if($rowx['lemma_id'] == ''){
//				$kateglox->query('INSERT INTO lemma (lemma_name) VALUES (\''.$bum['related_phrase'].'\')');
//			}
//		}
//	}
//	$i = 1;
//	foreach($kateglo->query('select DISTINCT(relation.rel_type) from relation left join relation_type on relation.rel_type = relation_type.rel_type where relation_type.rel_type is null;') as $row) {
//		
//		$kateglox->query('INSERT INTO relation_type (relation_type_name, relation_type_abbreviation) VALUES (\''.$i.'\', \''.$row['rel_type'].'\')');
//		$i++;
//	}
//	
	
	
//	$relationType = array();
//	foreach($kateglox->query('SELECT * FROM relation_type;') as $row) {
//		$relationType[$row['relation_type_abbreviation']] = $row['relation_type_id'];
//	}
//	
//	foreach($kateglo->query('select rel_type, root_phrase, pr.phrase, related_phrase, p.phrase from relation left join phrase pr on root_phrase = phrase left join phrase p on related_phrase = p.phrase where pr.phrase is not null and p.phrase is not null;') as $row) {
//		$bum = $row;
//		$rootPhrase = '';
//		$relatedPhrase = '';
//		foreach($kateglox->query('SELECT lemma_id, lemma_name FROM lemma WHERE lemma_name = \''.$bum['root_phrase'].'\';') as $rowx) {
//			$rootPhrase = $rowx['lemma_id'];
//		}
//		foreach($kateglox->query('SELECT lemma_id, lemma_name FROM lemma WHERE lemma_name = \''.$bum['related_phrase'].'\';') as $rowx) {
//			$relatedPhrase = $rowx['lemma_id'];
//		}
//		$sql = "INSERT INTO relation (relation_type_id, relation_parent_id, relation_child_id) VALUES (".$relationType[$row['rel_type']].", ".$rootPhrase.", ".$relatedPhrase.");";
//		$sql2 = "INSERT INTO relation (relation_type_id, relation_child_id, relation_parent_id) VALUES (".$relationType[$row['rel_type']].", ".$rootPhrase.", ".$relatedPhrase.");";
//		$kateglox->query($sql);
//		$kateglox->query($sql2);
//		echo $sql."\n";
//		echo $sql2."\n";
//	}
	

	$kateglo = null;
	$kateglox = null;
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
