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
 * @Table(name="lexical")
 */
class Lexical {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="lexical_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var string
	 * @Column(type="string", name="lexical_name", unique=true, length=255)
	 */
	private $lexical;
	
	/**
	 * @var string
	 * @Column(type="string", name="lexical_abbreviation", unique=true, length=255)
	 */
	private $abbreviation;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Definition", mappedBy="lexical", cascade={"persist"})
	 */
	private $definitions;
	
	
	/**
	 * 
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * 
	 * @param string $lexical
	 * @return void
	 */
	public function setLexical($lexical){
		$this->lexical = $lexical;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getLexical(){
		return $this->lexical;
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
     * @return kateglo\application\helpers\collections\ArrayCollection
     */
	public function getDefinitions(){
        return $this->definition;
    }

    /**
     * 
     * @param kateglo\application\models\Definition $definition
     * @return void
     */
    public function addDefinition(models\Definition $definition){
        $this->definitions[] = $definition;
        $definition->setLexical($this);
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
            $removed->removeLexical();
        }
    }
}
?>