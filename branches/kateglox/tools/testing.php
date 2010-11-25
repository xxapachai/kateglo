<?php

/*@var $look kateglo\application\services\interfaces\Search */
$look = utilities\Injector::getInstance ( interfaces\Search::INTERFACE_NAME );
$myres = $look->entry ( 'a-' );
echo "<br />";
print_r ( $myres->getEntry () );
echo "<br />";

/*@var $meaning kateglo\application\models\Meaning */
foreach ( $myres->getMeanings () as $meaning ) {
	/*@var $type kateglo\application\models\Type */
	foreach ( $meaning->getTypes () as $type ) {
		print_r ( $type->getType () );
		echo ' ';
		if (($type->getCategory () instanceof kateglo\application\models\TypeCategory)) {
			echo ' ' . $type->getCategory ()->getCategory () . ' ';
		}
		echo '<br />';
	}
	echo '<br />';
	
	/*@var $syllabel kateglo\application\models\Syllabel */
	foreach ( $meaning->getSyllabels () as $syllabel ) {
		print_r ( $syllabel->getSyllabel () );
		echo ' ';
		/*@var $pronounciation kateglo\application\models\Pronounciation */
		foreach ( $syllabel->getPronounciations () as $pronounciation ) {
			echo ' <i>' . $pronounciation->getPronounciation () . '</i> ';
		}
		echo '<br />';
	}
	echo '<br />';
	
	/*@var $definition kateglo\application\models\Definition */
	foreach ( $meaning->getDefinitions () as $definition ) {
		if ($definition->getClazz () instanceof kateglo\application\models\Clazz) {
			echo ' ';
			print_r ( $definition->getClazz ()->getClazz () );
			echo ' ';
			if (($definition->getClazz ()->getCategory () instanceof kateglo\application\models\ClazzCategory))
				$definition->getClazz ()->getCategory ()->getCategory ();
		}
		
		print_r ( $definition->getDefinition () );
		foreach ( $definition->getDisciplines () as $discipline ) {
			/*@var $discipline kateglo\application\models\Discipline */
			echo " (" . $discipline->getDiscipline () . ")";
			echo ", ";
		}
		
		foreach ( $definition->getSamples () as $sample ) {
			/*@var $discipline kateglo\application\models\Sample */
			echo " (" . $sample->getSample () . ")";
			echo ", ";
		}
		echo '<br />';
	}
	echo '<br />';
	
	/*@var $antonym kateglo\application\models\Antonym */
	foreach ( $meaning->getAntonyms () as $antonym ) {
		if ($antonym->getAntonym () instanceof kateglo\application\models\Meaning) {
			echo " " . $antonym->getAntonym ()->getEntry ()->getEntry () . " <br />";
		}
	}
	echo '<br />';
	
	/*@var $synonym kateglo\application\models\Synonym */
	foreach ( $meaning->getSynonyms () as $synonym ) {
		if ($synonym->getSynonym () instanceof kateglo\application\models\Meaning) {
			echo " " . $synonym->getSynonym ()->getEntry ()->getEntry () . " <br />";
		}
	}
	echo '<br />';
	
	/*@var $relation kateglo\application\models\Relation */
	foreach ( $meaning->getRelations () as $relation ) {
		if ($relation->getRelation () instanceof kateglo\application\models\Meaning) {
			echo " " . $relation->getRelation ()->getEntry ()->getEntry () . " <br />";
		}
	}
	echo '<br />';
	
	/*@var $misspelled kateglo\application\models\Misspelled */
	foreach ( $meaning->getMisspelleds () as $misspelled ) {
		if ($misspelled->getMisspelled () instanceof kateglo\application\models\Meaning) {
			echo " " . $misspelled->getMisspelled ()->getEntry ()->getEntry () . " <br />";
		}
	}
	echo '<br />';
	
	if ($meaning->getSpelled () instanceof kateglo\application\models\Misspelled) {
		echo " " . $meaning->getSpelled ()->getMeaning ()->getEntry ()->getEntry () . " <br />";
	}
	echo '<br />';

}
/*@var $source kateglo\application\models\Source  */
foreach ( $myres->getSources () as $source ) {
	echo " (" . $source->getCategory ()->getCategory () . ") " . $source->getSource () . "<br />";
}
echo '<br />';
die ();
?>