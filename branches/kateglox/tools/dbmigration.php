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

//	foreach($kateglo->query('select proverb.phrase as phrase from proverb left join phrase on phrase.phrase = proverb.phrase where phrase.phrase is null;') as $row) {
//	
//		$sql = "INSERT INTO lemma (lemma_name) VALUES ('".$row['phrase']."' ); ";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	
//	}

//	foreach($kateglo->query('SELECT * FROM proverb ORDER BY proverb;') as $row) {
//		foreach($kateglox->query("SELECT * FROM lemma WHERE lemma_name = '".$row['phrase']."' ") as $lemmaRow){
//			$child = $lemmaRow['lemma_id'];
//		}
//		
//		foreach($kateglox->query("SELECT * FROM lemma WHERE lemma_name = '".$row['proverb']."' ") as $lemmaRow){
//			$parent = $lemmaRow['lemma_id'];
//		}
//		//insert into relation
//		$sql = "INSERT INTO relation (relation_type_id, relation_parent_id, relation_child_id) VALUES (4, ".$parent.", ".$child.")";
//		$kateglox->query($sql);
//		echo $sql."\n";
//		$sql = "INSERT INTO relation (relation_type_id, relation_child_id, relation_parent_id) VALUES (4, ".$parent.", ".$child.")";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	foreach($kateglo->query('SELECT * FROM proverb ORDER BY proverb;') as $row) {
//		foreach($kateglox->query("SELECT * FROM lemma WHERE lemma_name = '".$row['phrase']."' ") as $lemmaRow){
//			$relation = $lemmaRow['lemma_id'];
//		}
//		//insert into lemma
//		$sql = "INSERT INTO lemma (lemma_name) VALUES ('".$row['proverb']."');";
//		$kateglox->query($sql);
//		echo $sql."\n";
//		
//		//get id
//		$id = $kateglox->lastInsertId();
//		echo $id."\n";
//		
//		//insert into definition
//		$sql = "INSERT INTO definition (definition_lemma_id, definition_lexical_id, definition_text) VALUES (".$id.", 7, '".$row['meaning']."');";
//		$kateglox->query($sql);
//		echo $sql."\n";
//		
//		//insert into relation
//		$sql = "INSERT INTO relation (relation_type, relation_parent_id, relation_child_id) VALUES (4, ".$relation.", ".$id.")";
//		$kateglox->query($sql);
//		echo $sql."\n";
//		$sql = "INSERT INTO relation (relation_type, relation_child_id, relation_parent_id) VALUES (4, ".$relation.", ".$id.")";
//		$kateglox->query($sql);
//		echo $sql."\n";
//	}
//	
//	foreach($kateglox->query('SELECT COUNT(*) FROM lemma;') as $row) {
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
	
	
	/*Migrate DEFINITION SOURCE*/
//	foreach($kateglo->query('select external_ref.phrase as phrase, def_text, url, label from external_ref left join phrase on phrase.phrase = external_ref.phrase left join definition on definition.phrase = phrase.phrase where external_ref.url like \'http://id.wikipedia.org%\';') as $row) {
//		$sql = "INSERT INTO source (source_type_id, source_url, source_label) VALUES (4, '".$row['url']."', '".$row['label']."');";
//		$kateglox->query($sql);
//		echo $sql."\n";
//		
//		$id = $kateglox->lastInsertId();
//		echo $id."\n";
//		
//		$lemmaId = '';
//		foreach($kateglox->query("SELECT * FROM lemma WHERE lemma_name = '".$row['phrase']."'") as $lemRow){
//			$lemmaId = $lemRow['lemma_id'];
//		}
//		echo $lemmaId."\n";
//		
//		foreach($kateglox->query("SELECT * FROM definition WHERE definition_lemma_id = '".$lemmaId."'") as $defRow){
//			$sql = "INSERT INTO definition_source (definition_id, source_id) VALUES (".$defRow['definition_id'].", ".$id.")";
//			$kateglox->query($sql);
//			echo $sql."\n";
//		}
//	}
	
	/*Migrate GLOSSARY SOURCE*/
//	$discipline = array();
//	foreach($kateglox->query('SELECT * FROM discipline;') as $row) {
//		$abbrv = $row['discipline_abbreviation'];
//		$id = $row['discipline_id'];
//		$discipline[$abbrv] = $id;
//	}
//	$lemma = '';
//	foreach($kateglox->query('SELECT * FROM lemma where lemma_name = \''.$row['glosphrase'].'\';') as $lem) {
//		$lemma = $lem['lemma_id'];
//	}
//	
//
//	foreach($kateglo->query('select glossary.phrase as phrase, original, discipline, url, label from glossary left join external_ref on glossary.phrase = external_ref.phrase where external_ref.url like \'http://en.wikipedia.org%\';') as $row) {
//		
//		$id = '';
//		foreach($kateglox->query("SELECT * from source WHERE source_url = '".$row['url']."'") as $soRow){
//			$id = $soRow['source_id'];
//			echo "source id: ".$id."\n";
//		}
//		
//		$lemmaId = '';
//		foreach($kateglox->query("SELECT * FROM lemma WHERE lemma_name = '".$row['phrase']."'") as $lemRow){
//			$lemmaId = $lemRow['lemma_id'];			
//			echo "lemma id: ".$lemmaId."\n";
//			
//			$i = 0;
//			foreach($kateglox->query("SELECT * FROM glossary WHERE glossary_lemma_id = ".$lemmaId.";") as $defRow){
//				$i++;
//			}
//			
//			if($i === 0){
//				$sql = "INSERT INTO glossary (glossary_lemma_id, glossary_locale_id, glossary_discipline_id, glossary_name) VALUES ('".$lemmaId."', '1', '".$discipline[$row['discipline']]."', '".$row['original']."' ); ";
//				$kateglox->query($sql);
//				echo $sql."\n";
//				
//				$newGlossarId = $kateglox->lastInsertId();
//				echo "new glossar id: ".$newGlossarId."\n";
//				
//				$sql = "INSERT INTO glossary_source (glossary_id, source_id) VALUES (".$newGlossarId.", ".$id.")";
//				$kateglox->query($sql);
//				echo $sql."\n";
//			}else{
//				foreach($kateglox->query("SELECT * FROM glossary WHERE glossary_lemma_id = ".$lemmaId.";") as $glosRow){
//					$sql = "INSERT INTO glossary_source (glossary_id, source_id) VALUES (".$glosRow['glossary_id'].", ".$id.")";
//					$kateglox->query($sql);
//					echo $sql."\n";
//				}
//			}
//		}
//		
//		attention there is source without relation!!!
//		
//	}
	
	foreach($kateglo->query('select phrase, content from sys_cache order by content;') as $row) {
		$content = trim($row['content']);
		if(strpos($content, '<b>') === 0){
			$getEnd = strpos($content, '</b>')-3;
			$getRawSyllabel = substr($content, 3, $getEnd);
			if(strpos($getRawSyllabel, '<sup>') === 0){
				$getSupEnd = strpos($getRawSyllabel, '</sup>')+6;
				$getSyllabel = substr($getRawSyllabel, $getSupEnd);				
			}else{
				$getSyllabel = $getRawSyllabel;
			}
			
			$syllabel = strip_tags(html_entity_decode($getSyllabel, ENT_QUOTES, 'UTF-8'));
			$getLemma = $kateglox->query('select lemma_id, lemma_name from lemma where lemma_name = \''.$row['phrase'].'\';');
			if(count($getLemma) > 0){
				foreach ($getLemma as $lemmaRow){
					$sql = 'insert into syllabel (syllabel_lemma_id, syllabel_name) values ('.$lemmaRow['lemma_id'].', \''.$syllabel.'\')';
					$kateglox->query($sql);
					echo $sql."\n";
				}
			}else{
				echo "NOT FOUND :".$syllabel."\n";
			}
		}
	}
	
	$kateglo = null;
	$kateglox = null;
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
