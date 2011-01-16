<?php
namespace kateglo\application\faces;
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the GPL 2.0. For more information, see
 * <http://code.google.com/p/kateglo/>.
 */

use kateglo\application\utilities\collections\ArrayCollection;
/**
 *
 *
 * @package kateglo\application\faces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2010-12-16 22:33:38 +0100 (Do, 16 Dez 2010) $
 * @version $LastChangedRevision: 267 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Document {
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const ID = 'id';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const ENTRY = 'entry';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const ANTONYM = 'antonym';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const DEFINITION = 'definition';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const CLAZZ = 'class';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const CLAZZ_CATEGORY = 'classCategory';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const DISCIPLINE = 'discipline';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SAMPLE = 'sample';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const MISSPELLED = 'misspelled';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const RELATION = 'relation';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SYNONYM = 'synonym';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SPELLED = 'spelled';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SYLLABEL = 'syllabel';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const TYPE = 'type';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const TYPE_CATEGORY = 'type_category';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SOURCE = 'source';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const SOURCE_CATEGORY = 'sourceCategory';
	
	/**
	 * Enter description here ...
	 * @var int
	 */
	private $id;
	
	/**
	 * Enter description here ...
	 * @var string
	 */
	private $entry;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $antonyms;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $definitions;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $classes;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $classCategories;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $disciplines;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $samples;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $misspelleds;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $relations;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $synonyms;
	
	/**
	 * Enter description here ...
	 * @var string
	 */
	private $spelled;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $syllabels;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $types;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $typeCategories;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $source;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $sourceCategories;
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public function getEntry() {
		return $this->entry;
	}
	
	/**
	 * @param string $entry
	 */
	public function setEntry($entry) {
		$this->entry = $entry;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getAntonyms() {
		return $this->antonyms;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $antonyms
	 */
	public function setAntonyms($antonyms) {
		$this->antonyms = $antonyms;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getDefinitions() {
		return $this->definitions;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $definitions
	 */
	public function setDefinitions($definitions) {
		$this->definitions = $definitions;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getClasses() {
		return $this->classes;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $classes
	 */
	public function setClasses($classes) {
		$this->classes = $classes;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getClassCategories() {
		return $this->classCategories;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $classCategories
	 */
	public function setClassCategories($classCategories) {
		$this->classCategories = $classCategories;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getDisciplines() {
		return $this->disciplines;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $disciplines
	 */
	public function setDisciplines($disciplines) {
		$this->disciplines = $disciplines;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSamples() {
		return $this->samples;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $samples
	 */
	public function setSamples($samples) {
		$this->samples = $samples;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getMisspelleds() {
		return $this->misspelleds;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $misspelleds
	 */
	public function setMisspelleds($misspelleds) {
		$this->misspelleds = $misspelleds;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getRelations() {
		return $this->relations;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $relations
	 */
	public function setRelations($relations) {
		$this->relations = $relations;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSynonyms() {
		return $this->synonyms;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $synonyms
	 */
	public function setSynonyms($synonyms) {
		$this->synonyms = $synonyms;
	}
	
	/**
	 * @return string
	 */
	public function getSpelled() {
		return $this->spelled;
	}
	
	/**
	 * @param string $spelleds
	 */
	public function setSpelled($spelled) {
		$this->spelled = $spelled;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSyllabels() {
		return $this->syllabels;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $syllabels
	 */
	public function setSyllabels($syllabels) {
		$this->syllabels = $syllabels;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollections
	 */
	public function getTypes() {
		return $this->types;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $types
	 */
	public function setTypes($types) {
		$this->types = $types;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getTypeCategories() {
		return $this->typeCategories;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $typeCategories
	 */
	public function setTypeCategories($typeCategories) {
		$this->typeCategories = $typeCategories;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSource() {
		return $this->source;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}
	
	/**
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSourceCategories() {
		return $this->sourceCategories;
	}
	
	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $sourceCategories
	 */
	public function setSourceCategories($sourceCategories) {
		$this->sourceCategories = $sourceCategories;
	}

}

?>