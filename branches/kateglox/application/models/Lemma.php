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
use  kateglo\application\models;
use kateglo\application\utilities\collections;
/**
 *
 *
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-10-07
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="lemma")
 */
class Lemma {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="lemma_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="lemma_name", unique=true, length=255)
	 */
	private $lemma;

	/**
	 * @var kateglo\application\models\Syllabel
	 * @OneToOne(targetEntity="kateglo\application\models\Syllabel", mappedBy="lemma", cascade={"persist"})
	 */
	private $syllabel;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Type", mappedBy="lemmas", cascade={"persist"})
	 */
	private $types;


	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Definition", mappedBy="lemma", cascade={"persist"})
	 */
	private $definitions;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Glossary", mappedBy="lemma", cascade={"persist"})
	 */
	private $glossaries;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Relation", mappedBy="parent", cascade={"persist"})
	 */
	private $relations;

	public function __construct() {
		$this->types = new collections\ArrayCollection();
		$this->definitions = new collections\ArrayCollection();
		$this->relations = new collections\ArrayCollection();
	}


	/**
	 *
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 *
	 * @param string $lemma
	 * @return void
	 */
	public function setLemma($lemma){
		$this->lemma = $lemma;
	}

	/**
	 *
	 * @return string
	 */
	public function getLemma(){
		return $this->lemma;
	}

	/**
	 *
	 * @param kateglo\application\models\Syllabel $syllabel
	 * @return void
	 */
	public function setSyllabel(models\Syllabel $syllabel){
		$this->syllabel = $syllabel;
	}

	/**
	 *
	 * @return kateglo\application\models\Syllabel
	 */
	public function getSyllabel(){
		return $this->syllabel;
	}
	
	/**
     * 
     * @param kateglo\application\models\Type $type
     * @return void
     */
	public function addType(models\Type $type){
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addLemma($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Type $type
     * @return void
     */
    public function removeType(models\Type $type)
    {
        $removed = $this->sources->removeElement($type);
        if ($removed !== null) {
            $removed->removeLemma($this);
        }
    }

    /**
     * 
     * @return kateglo\application\helpers\collections\ArrayCollection
     */
    public function getTypes()
    {
        return $this->types;
    }

	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function addDefinition(models\Definition $definition){
		$this->definitions[] = $definition;
		$definition->setLemma($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function removeDefinition(models\Definition $definition){
		/*@var $removed kateglo\application\models\Definition */
		$removed = $this->definitions->removeElement($definition);
		if ($removed !== null) {
			$removed->removeLemma();
		}
	}

	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getDefinitions(){
		return $this->definitions;
	}

	/**
	 *
	 * @param kateglo\application\models\Glossary $glossary
	 * @return void
	 */
	public function addGlossary(models\Glossary $glossary){
		$this->glossaries[] = $glossary;
		$glossary->setLemma($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function removeGlossary(models\Glossary $glossary){
		/*@var $removed kateglo\application\models\Definition */
		$removed = $this->glossaries->removeElement($glossary);
		if ($removed !== null) {
			$removed->removeLemma();
		}
	}

	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getGlossaries(){
		return $this->glossaries;
	}

	/**
	 *
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function addRelation(models\Relation $relation){
		$this->relations[] = $relation;
		$relation->setLemma($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Relation $relation
	 * @return void
	 */
	public function removeRelation(models\Relation $relation){
		/*@var $removed kateglo\application\models\Relation */
		$removed = $this->relations->removeElement($relation);
		if ($removed !== null) {
			$removed->removeLemma();
		}
	}

	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getRelations(){
		return $this->relations;
	}

}
?>