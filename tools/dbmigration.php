<?php
try {
	$kateglo = new PDO('mysql:host=localhost;dbname=kateglo', 'root', 'root');
	$kateglox = new PDO('mysql:host=localhost;dbname=kateglox', 'root', 'root');

	/* Migrate PHRASE

	$phraseType = array();
	foreach($kateglox->query('SELECT * FROM phrase_type;') as $row) {
		$abbrv = $row['phrase_type_abbreviation'];
		$id = $row['phrase_type_id'];
		$phraseType[$abbrv] = $id;
	}

	$lexical = array();
	foreach($kateglox->query('SELECT * FROM lexical;') as $row) {
		$abbrv = $row['lexical_abbreviation'];
		$id = $row['lexical_id'];
		$lexical[$abbrv] = $id;
	}
	
	foreach($kateglo->query('SELECT * FROM phrase ORDER BY phrase;') as $row) {
		$sql = "INSERT INTO phrase (phrase_type_id, phrase_lexical_id, phrase_name) VALUES (".$phraseType[$row['phrase_type']].", ".$lexical[$row['lex_class']].", '".$row['phrase']."' ); ";
		$kateglox->query($sql);
		echo $sql."\n";
	}
	
	foreach($kateglox->query('SELECT COUNT(*) FROM phrase ORDER BY phrase_name;') as $row) {
		print_r($row);
	}
	
	*/
	
	/* MIGRATE PROVERB
	$phrase = array();
	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
		$phrase[$row['phrase_name']] = $row['phrase_id'];
	}
	
	foreach($kateglo->query('SELECT * FROM proverb ORDER BY prv_uid;') as $row) {
		$sql = "INSERT INTO proverb (proverb_phrase_id, proverb_text, proverb_meaning) VALUES (".$phrase[$row['phrase']].", '".$row['proverb']."', '".$row['meaning']."');";
		$kateglox->query($sql);
		echo $sql."\n";
	}
	
	foreach($kateglox->query('SELECT COUNT(*) FROM proverb;') as $row) {
		print_r($row);
	}
	*/
	
	/* MIGRATE RELATION
	$phrase = array();
	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
		$phrase[$row['phrase_name']] = $row['phrase_id'];
	}
	
	$relationType = array();
	foreach($kateglox->query('SELECT * FROM relation_type;') as $row) {
		$relationType[$row['relation_type_abbreviation']] = $row['relation_type_id'];
	}
	
	foreach($kateglo->query('SELECT * FROM relation;') as $row) {
		$sql = "INSERT INTO relation (relation_type_id, relation_phrase_id, relation_phrase_relation) VALUES (".$relationType[$row['rel_type']].", ".$phrase[$row['root_phrase']].", ".$phrase[$row['related_phrase']].");";
		$kateglox->query($sql);
		echo $sql."\n";
	}
	*/
	
	$phrase = array();
	foreach($kateglox->query('SELECT phrase_id, phrase_name FROM phrase ORDER BY phrase_name;') as $row) {
		$phrase[$row['phrase_name']] = $row['phrase_id'];
	}
	
	foreach($kateglo->query('SELECT phrase, def_text FROM definition;') as $row) {
		$sql = "INSERT INTO definition (definition_phrase_id, definition_text) VALUES (".$phrase[$row['phrase']].", '".$row['def_text']."');";
		$kateglox->query($sql);
		echo $sql."\n";
	}
	
	$kateglo = null;
	$kateglox = null;
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
