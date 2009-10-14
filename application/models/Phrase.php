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
 * @since 2009-10-07
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="phrase")
 */
class Phrase {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="phrase_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="phrase_name", unique=true, length=255)
	 */
	private $phrase;

	/**
	 * @var kateglo\application\models\PhraseType
	 * @ManyToOne(targetEntity="kateglo\application\models\PhraseType")
	 * @JoinColumn(name="phrase_type_id", referencedColumnName="phrase_type_id")
	 */
	private $type;

	/**
	 * @var kateglo\application\models\Lexical
	 * @ManyToOne(targetEntity="kateglo\application\models\Lexical")
	 * @JoinColumn(name="phrase_lexical_id", referencedColumnName="lexical_id")
	 */
	private $lexical;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Definition", mappedBy="phrase", cascade={"persist"})
	 */
	private $definitions;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Proverb", mappedBy="phrase", cascade={"persist"})
	 */
	private $proverbs;

	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Relation", mappedBy="phrase", cascade={"persist"})
	 */
	private $relations;

	public function __construct() {
		$this->definitions = new collections\ArrayCollection();
		$this->proverbs = new collections\ArrayCollection();
		$this->relations = new collections\ArrayCollection();
	}
	
	/**
	 * 
	 * @param int $id
	 * @return void
	 */
	public function setId($id){
		$this->id = $id;
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
	 * @param string $phrase
	 * @return void
	 */
	public function setPhrase($phrase){
		$this->phrase = $phrase;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getPhrase(){
		return $this->phrase;
	}
	
	/**
	 *
	 * @param kateglo\application\models\PhraseType $type
	 * @return void
	 */
	public function setType(models\PhraseType $type){
		$this->type = $type;
	}
	
	/**
	 *
	 * @return kateglo\application\models\PhraseType
	 */
	public function getType(){
		return $this->type;
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
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function addDefinition(models\Definition $definition){
		$this->definitions[] = $definition;
		$definition->setPhrase($this);
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
            $removed->removePhrase();
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
	 * @param kateglo\application\utilities\collections\ArrayCollection $proverbs
	 * @return void
	 */
	public function setProverbs(collections\ArrayCollection $proverbs){
		$this->proverbs = $proverbs;
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getProverbs(){
		return $this->proverbs;
	}
	
	/**
	 * 
	 * @param kateglo\application\utilities\collections\ArrayCollection $relations
	 * @return void
	 */
	public function setRelations(collections\ArrayCollection $relations){
		$this->relations = $relations;
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