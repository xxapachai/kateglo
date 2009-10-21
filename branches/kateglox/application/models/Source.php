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
 * @Table(name="source")
 */
class Source {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="source_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @Column(type="string", name="source_url", unique=true, length=255)
	 */
	private $url;

	/**
	 * @var string
	 * @Column(type="string", name="source_label", unique=true, length=255)
	 */
	private $label;

	/**
	 * @var kateglo\application\helpers\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Definition", cascade={"persist"})
	 * @JoinTable(name="definition_source",
	 *      joinColumns={@JoinColumn(name="source_id", referencedColumnName="source_id")},
	 *      inverseJoinColumns={@JoinColumn(name="definition_id", referencedColumnName="definition_id")}
	 *  )
	 */
	private $definitions;
	
	/**
	 * @var kateglo\application\helpers\collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Glossary", cascade={"persist"})
	 * @JoinTable(name="definition_discipline",
	 *      joinColumns={@JoinColumn(name="source_id", referencedColumnName="source_id")},
	 *      inverseJoinColumns={@JoinColumn(name="glossary_id", referencedColumnName="glossary_id")}
	 *  )
	 */
	private $glossaries;


	/**
	 * 
	 * @return void
	 */
	public function __construct(){
		$this->definitions = new collections\ArrayCollection();
		$this->glossaries = new collections\ArrayCollection();
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
	 * @param string $discipline
	 * @return void
	 */
	public function setDiscipline($discipline){
		$this->discipline = $discipline;
	}

	/**
	 *
	 * @return string
	 */
	public function getDiscipline(){
		return $this->discipline;
	}

	/**
	 *
	 * @param string $abbreviation
	 * @return void
	 */
	public function setAbbreviation($abbreviation){
		$this->abbreviation = $abbreviation;
	}

	/**
	 *
	 * @return string
	 */
	public function getAbbreviation(){
		return $this->abbreviation;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */	
	public function addDefinition(models\Definition $definition){
        if (!$this->definitions->contains($definition)) {
            $this->definitions[] = $definition;
            $definition->addSource($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Definition $definition
     * @return void
     */
    public function removeDefinition(models\Definition $definition){
        $removed = $this->definitions->removeElement($definition);
        if ($removed !== null) {
            $removed->removeSource($this);
        }
    }

    /**
     * 
     * @return kateglo\application\helpers\collections\ArrayCollection
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
    	if (!$this->glossaries->contains($glossary)) {
            $this->glossaries[] = $glossary;
            $glossary->addSource($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Glossary $glossary
     * @return void
     */
    public function removeGlossary(models\Glossary $glossary){
    	/*@var $removed kateglo\application\models\Glossary */
        $removed = $this->glossaries->removeElement($glossary);
        if ($removed !== null) {
            $removed->removeSource();
        }
    }
    
    /**
     * 
     * @return kateglo\application\helpers\collections\ArrayCollection
     */
	public function getGlossaries(){
        return $this->glossaries;
    }

}
?>