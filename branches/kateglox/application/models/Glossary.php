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
use kateglo\application\models;
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
 * @Table(name="glossary")
 */
class Glossary {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="glossary_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var kateglo\application\models\Lemma
	 * @ManyToOne(targetEntity="kateglo\application\models\Lemma")
	 * @JoinColumn(name="glossary_lemma_id", referencedColumnName="lemma_id")
	 */
	private $lemma;

	/**
	 * @var kateglo\application\models\Locale
	 * @ManyToOne(targetEntity="kateglo\application\models\Locale")
	 * @JoinColumn(name="glossary_locale_id", referencedColumnName="locale_id")
	 */
	private $locale;
	
	/**
	 * @var kateglo\application\models\Discipline
	 * @ManyToOne(targetEntity="kateglo\application\models\Discipline")
	 * @JoinColumn(name="glossary_discipline_id", referencedColumnName="discipline_id")
	 */
	private $discipline;

	/**
	 *
	 * @var string
	 * @Column(type="text", name="glossary_name")
	 */
	private $glossary;
	
	/**
	 * @var kateglo\application\helpers\collections\ArrayCollection
     * @ManyToMany(targetEntity="kateglo\application\models\Source", mappedBy="glossaries")
     */
    private $sources;
	
	/**
	 * 
	 * @return void
	 */
	public function __construct(){
		$this->sources = new collections\ArrayCollection();
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
	 * @param kateglo\application\models\Lemma $lemma
	 * @return void
	 */
	public function setLemma(models\Lemma $lemma){
		$this->lemma = $lemma;
	}

	/**
	 *
	 * @return kateglo\application\models\Lemma
	 */
	public function getLemma(){
		return $this->lemma;
	}

	/**
	 *
	 * @return void
	 */
	public function removeLemma() {
		if ($this->lemma !== null) {
			/*@var $phrase kateglo\application\models\Lemma */
			$lemma = $this->lemma;
			$this->lemma = null;
			$lemma->removeDefinition($this);
		}
	}

	/**
	 *
	 * @param kateglo\application\models\Lexical $lexical
	 * @return void
	 */
	public function setLexical(models\Lexical $lexical){
		$this->lexical = $lexical;
	}

	/**
	 *
	 * @return kateglo\application\models\Lexical
	 */
	public function getLexical(){
		return $this->lexical;
	}

	/**
	 *
	 * @return void
	 */
	public function removeLexical() {
		if ($this->lexical !== null) {
			/*@var $phrase kateglo\application\models\Lexical */
			$lexical = $this->lexical;
			$this->lexical = null;
			$lexical->removeDefinition($this);
		}
	}

	/**
	 *
	 * @param string $definition
	 * @return void
	 */
	public function setDefinition($definition){
		$this->definition = $definition;
	}

	/**
	 *
	 * @return string
	 */
	public function getDefinition(){
		return $this->definition;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Discipline $discipline
	 * @return void
	 */
	public function setDiscipline(models\Discipline $discipline)
    {
        if (!$this->discipline->contains($discipline)) {
        	if($this->discipline[0] instanceof models\Discipline){
        		$this->removeDiscipline($this->discipline[0]);        		
        	}
            $this->discipline[0] = $discipline;
            $discipline->addDefinition($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Discipline $discipline
     * @return void
     */
    public function removeDiscipline(models\Discipline $discipline)
    {
        $removed = $this->discipline->removeElement($discipline);
        if ($removed !== null) {
            $removed->removeDefinition($this);
        }
    }

    /**
     * 
     * @return kateglo\application\models\Discipline
     */
    public function getDiscipline()
    {
        return $this->discipline[0];
    }
    
    /**
     * 
     * @param kateglo\application\models\Source $source
     * @return void
     */
	public function addSource(models\Source $source){
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->addGlossary($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Source $source
     * @return void
     */
    public function removeSource(models\Source $source){
        $removed = $this->sources->removeElement($source);
        if ($removed !== null) {
            $removed->removeGlossary($this);
        }
    }

    /**
     * 
     * @return kateglo\application\helpers\collections\ArrayCollection
     */
    public function getSources(){
        return $this->sources;
    }
}
?>