<?php
namespace kateglo\application\models;
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
use kateglo\application\utilities\collections;
use kateglo\application\models;
/**
 *
 *
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="definition")
 */
class Definition {
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="definition_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="definition_text", length=255)
	 */
	private $definition;
	
	/**
	 * @var kateglo\application\models\Meaning
	 * @ManyToOne(targetEntity="kateglo\application\models\Meaning")
	 * @JoinColumn(name="definition_meaning_id", referencedColumnName="meaning_id")
	 */
	private $meaning;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Clazz", mappedBy="definitions", cascade={"persist"})
	 */
	private $clazz;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Discipline", mappedBy="definitions", cascade={"persist"})
	 */
	private $disciplines;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Sample", mappedBy="definition", cascade={"persist"})
	 */
	private $samples;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Antonym")
	 * @JoinTable(name="rel_antonym_definition",
	 * joinColumns={@JoinColumn(name="rel_antonym_id", referencedColumnName="antonym_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $antonyms;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Synonym")
	 * @JoinTable(name="rel_synonym_definition",
	 * joinColumns={@JoinColumn(name="rel_synonym_id", referencedColumnName="synonym_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $synonyms;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Relation")
	 * @JoinTable(name="rel_relation_definition",
	 * joinColumns={@JoinColumn(name="rel_relation_id", referencedColumnName="relation_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $relations;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Misspelled")
	 * @JoinTable(name="rel_misspelled_definition",
	 * joinColumns={@JoinColumn(name="rel_misspelled_id", referencedColumnName="misspelled_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $misspelleds;
	
	function __construct() {
		$this->clazz = new collections\ArrayCollection ();
		$this->disciplines = new collections\ArrayCollection ();
		$this->samples = new collections\ArrayCollection ();
		$this->antonyms = new collections\ArrayCollection ();
		$this->synonyms = new collections\ArrayCollection ();
		$this->relations = new collections\ArrayCollection ();
		$this->misspelleds = new collections\ArrayCollection ();
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $definition
	 */
	public function getDefinition() {
		return $this->definition;
	}
	
	/**
	 * @param string $definition
	 */
	public function setDefinition($definition) {
		$this->definition = $definition;
	}
	
	/**
	 * @return the $meaning
	 */
	public function getMeaning() {
		return $this->meaning;
	}
	
	/**
	 * @param kateglo\application\models\Meaning $meaning
	 * @return void
	 */
	public function setMeaning(models\Meaning $meaning) {
		$this->meaning = $meaning;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeMeaning() {
		if ($this->meaning !== null) {
			/*@var $entry kateglo\application\models\Meaning */
			$meaning = $this->meaning;
			$this->meaning = null;
			$meaning->removeDefinition ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Clazz $clazz
	 * @return void
	 */
	public function setClazz(models\Clazz $clazz) {
		if (! $this->clazz->contains ( $clazz )) {
			$this->clazz [0] = $clazz;
			$clazz->addDefinition ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Clazz $clazz
	 * @return void
	 */
	public function removeClazz() {
		/*@var $entry kateglo\application\models\Clazz */
		$removed = $this->clazz->removeElement ( $this->clazz->get ( 0 ) );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 *
	 * @return kateglo\application\models\Clazz
	 */
	public function getClazz() {
		return $this->clazz->get ( 0 );
	}
	
	/**
	 *
	 * @param kateglo\application\models\Discipline $discipline
	 * @return void
	 */
	public function addDiscipline(models\Discipline $discipline) {
		if (! $this->disciplines->contains ( $discipline )) {
			$this->disciplines [] = $discipline;
			$discipline->addDefinition ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Discipline $discipline
	 * @return source
	 */
	public function removeDiscipline(models\Discipline $discipline) {
		/*@var $removed kateglo\application\models\Discipline */
		$removed = $this->disciplines->removeElement ( $discipline );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getDisciplines() {
		return $this->disciplines;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Sample $sample
	 * @return void
	 */
	public function addSample(models\Sample $sample) {
		$this->samples [] = $sample;
		$sample->setDefinition ( $this );
	}
	
	/**
	 *
	 * @param kateglo\application\models\Sample $sample
	 * @return void
	 */
	public function removeSample(models\Sample $sample) {
		/*@var $removed kateglo\application\models\Sample */
		$removed = $this->samples->removeElement ( $sample );
		if ($removed !== null) {
			$removed->removeDefinition ();
		}
	}
	
	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSamples() {
		return $this->samples;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Antonym $antonym
	 * @return void
	 */
	public function addAntonym(models\Antonym $antonym) {
		if (! $this->antonyms->contains ( $antonym )) {
			$this->antonyms [] = $antonym;
			$antonym->addDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Antonym $antonym
	 * @return void
	 */
	public function removeAntonym(models\Antonym $antonym) {
		/*@var $removed kateglo\application\models\Antonym */
		$removed = $this->antonyms->removeElement ( $antonym );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getAntonyms() {
		return $this->antonyms;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Synonym $synonym
	 * @return void
	 */
	public function addSynonym(models\Synonym $synonym) {
		if (! $this->synonyms->contains ( $synonym )) {
			$this->synonyms [] = $synonym;
			$synonym->addDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Synonym $synonym
	 * @return void
	 */
	public function removeSynonym(models\Synonym $synonym) {
		/*@var $removed kateglo\application\models\Synonym */
		$removed = $this->synonyms->removeElement ( $synonym );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getSynonyms() {
		return $this->synonyms;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function addRelation(models\Relation $relation) {
		if (! $this->relations->contains ( $relation )) {
			$this->relations [] = $relation;
			$relation->addDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function removeRelation(models\Relation $relation) {
		/*@var $removed kateglo\application\models\Relation */
		$removed = $this->relations->removeElement ( $relation );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getRelations() {
		return $this->relations;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Misspelled $misspelled
	 * @return void
	 */
	public function addMisspelled(models\Misspelled $misspelled) {
		if (! $this->misspelleds->contains ( $misspelled )) {
			$this->misspelleds [] = $misspelled;
			$misspelled->addDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Misspelled $misspelled
	 * @return void
	 */
	public function removeMisspelled(models\Misspelled $misspelled) {
		/*@var $removed kateglo\application\models\Misspelled */
		$removed = $this->relations->removeElement ( $misspelled );
		if ($removed !== null) {
			$removed->removeDefinition ( $this );
		}
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getMisspelleds() {
		return $this->misspelleds;
	}
}

?>