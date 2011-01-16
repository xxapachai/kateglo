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
use Doctrine\Common\Collections\ArrayCollection;
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
 * @Table(name="meaning")
 */
class Meaning {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="meaning_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var kateglo\application\models\Entry
	 * @ManyToOne(targetEntity="kateglo\application\models\Entry")
	 * @JoinColumn(name="meaning_entry_id", referencedColumnName="entry_id")
	 */
	private $entry;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Definition", mappedBy="meaning", cascade={"persist"})
	 */
	private $definitions;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Type", mappedBy="meanings", cascade={"persist"})
	 */
	protected $types;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Antonym", mappedBy="meaning", cascade={"persist"})
	 */
	private $antonyms;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Synonym", mappedBy="meaning", cascade={"persist"})
	 */
	private $synonyms;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Relation", mappedBy="meaning", cascade={"persist"})
	 */
	private $relations;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Syllabel", mappedBy="meaning", cascade={"persist"})
	 */
	private $syllabels;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Misspelled", mappedBy="meaning", cascade={"persist"})
	 */
	private $misspelleds;
	
	/**
	 * @var kateglo\application\models\Misspelled
	 * @OneToOne(targetEntity="kateglo\application\models\Misspelled", mappedBy="misspelled", cascade={"persist"})
	 */
	private $spelled;
	
	/**
	 * 
	 * Construct
	 */
	function __construct() {
		$this->definitions = new ArrayCollection ();
		$this->types = new ArrayCollection ();
		$this->antonyms = new ArrayCollection ();
		$this->synonyms = new ArrayCollection ();
		$this->relations = new ArrayCollection ();
		$this->syllabels = new ArrayCollection ();
		$this->misspelleds = new ArrayCollection ();
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Entry $entry
	 * @return void
	 */
	public function setEntry(Entry $entry) {
		$this->entry = $entry;
	}
	
	/**
	 *
	 * @return kateglo\application\models\Entry
	 */
	public function getEntry() {
		return $this->entry;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeEntry() {
		if ($this->entry !== null) {
			/*@var $entry kateglo\application\models\Entry */
			$entry = $this->entry;
			$this->entry = null;
			$entry->removeMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function addDefinition(Definition $definition) {
		if (! $this->definitions->contains ( $definition )) {
			$this->definitions [] = $definition;
			$definition->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function removeDefinition(Definition $definition) {
		/*@var $removed kateglo\application\models\Definition */
		$removed = $this->definitions->removeElement ( $definition );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDefinitions() {
		return $this->definitions;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Type $type
	 * @return void
	 */
	public function addType(Type $type) {
		if (! $this->types->contains ( $type )) {
			$this->types [] = $type;
			$type->addMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Type $type
	 * @return void
	 */
	private function removeType(Type $type) {
		/*@var $removed kateglo\application\models\Type */
		$removed = $this->types->removeElement ( $type );
		if ($removed !== null) {
			$removed->removeMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @return kateglo\application\models\Type
	 */
	public function getTypes() {
		return $this->types;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Antonym $antonym
	 * @return void
	 */
	public function addAntonym(Antonym $antonym) {
		if (! $this->antonyms->contains ( $antonym )) {
			$this->antonyms [] = $antonym;
			$antonym->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Antonym $antonym
	 * @return void
	 */
	public function removeAntonym(Antonym $antonym) {
		/*@var $removed kateglo\application\models\Antonym */
		$removed = $this->antonyms->removeElement ( $antonym );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getAntonyms() {
		return $this->antonyms;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Synonym $synonym
	 * @return void
	 */
	public function addSynonym(Synonym $synonym) {
		if (! $this->synonyms->contains ( $synonym )) {
			$this->synonyms [] = $synonym;
			$synonym->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Synonym $synonym
	 * @return void
	 */
	public function removeSynonym(Synonym $synonym) {
		/*@var $removed kateglo\application\models\Synonym */
		$removed = $this->synonyms->removeElement ( $synonym );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getSynonyms() {
		return $this->synonyms;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function addRelation(Relation $relation) {
		if (! $this->relations->contains ( $relation )) {
			$this->relations [] = $relation;
			$relation->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function removeRelation(Relation $relation) {
		/*@var $removed kateglo\application\models\Relation */
		$removed = $this->relations->removeElement ( $relation );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getRelations() {
		return $this->relations;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Syllabel $syllabel
	 * @return void
	 */
	public function addSyllabel(Syllabel $syllabel) {
		if (! $this->syllabels->contains ( $syllabel )) {
			$this->syllabels [] = $syllabel;
			$syllabel->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Syllabel $syllabel
	 * @return void
	 */
	public function removeSyllabel(Syllabel $syllabel) {
		/*@var $removed kateglo\application\models\Syllabel */
		$removed = $this->syllabels->removeElement ( $syllabel );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getSyllabels() {
		return $this->syllabels;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Misspelled $misspelled
	 * @return void
	 */
	public function addMisspelled(Misspelled $misspelled) {
		if (! $this->misspelleds->contains ( $misspelled )) {
			$this->misspelleds [] = $misspelled;
			$misspelled->setMeaning ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Misspelled $misspelled
	 * @return void
	 */
	public function removeMisspelled(Misspelled $misspelled) {
		/*@var $removed kateglo\application\models\Misspelled */
		$removed = $this->misspelleds->removeElement ( $misspelled );
		if ($removed !== null) {
			$removed->removeMeaning ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getMisspelleds() {
		return $this->misspelleds;
	}
	
	/**
	 * 
	 * @return kateglo\application\models\Misspelled
	 */
	public function getSpelled() {
		return $this->spelled;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\models\Misspelled $spelled
	 */
	public function setSpelled(Misspelled $spelled) {
		if ($this->spelled !== $spelled) {
			$this->spelled = $spelled;
			$spelled->setMisspelled ( $this );
		}
	}
}

?>