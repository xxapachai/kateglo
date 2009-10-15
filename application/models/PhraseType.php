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
 * @Table(name="phrase_type")
 */
class PhraseType {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * @var int
	 * @Id
	 * @Column(type="integer", name="phrase_type_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * @var string
	 * @Column(type="string", name="phrase_type_name", unique=true)
	 */
	private $type;
	
	/**
	 * 
	 * @var string
	 * @Column(type="string", name="phrase_type_abbreviation", unique=true)
	 */
	private $abbreviation;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Phrase", mappedBy="type", cascade={"persist"})
	 */
	private $phrases;
	
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
	 * @param string $type
	 * @return void
	 */
	public function setType($type){
		$this->type = $type;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getType(){
		return $getType;
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
	 * @param kateglo\application\utilities\collections\ArrayCollection $phrases
	 * @return void
	 */
	public function setPhrases(collections\ArrayCollection $phrases){
		$this->phrases = $phrases;
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getPhrases(){
		return $this->phrases;
	}
	
}
?>