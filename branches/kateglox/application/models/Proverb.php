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
 * @Table(name="proverb")
 */
class Proverb {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="proverb_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var kateglo\application\models\Phrase
	 * @ManyToOne(targetEntity="kateglo\application\models\Phrase")
	 * @JoinColumn(name="proverb_phrase_id", referencedColumnName="phrase_id")
	 */
	private $phrase;
	
	/**
	 *
	 * @var string
	 * @Column(type="text", name="proverb_text")
	 */
	private $proverb;
	
	/**
	 *
	 * @var string
	 * @Column(type="text", name="proverb_meaning")
	 */
	private $meaning;
	
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
	 * @param kateglo\application\models\Phrase $phrase
	 * @return void
	 */
	public function setPhrase(models\Phrase $phrase){
		$this->phrase = $phrase;
	}
	
	/**
	 *
	 * @return kateglo\application\models\Phrase
	 */
	public function getPhrase(){
		return $this->phrase;
	}
	
	/**
	 * 
	 * @param string $proverb
	 * @return void
	 */
	public function setProverb($proverb){
		$this->proverb = $proverb;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getProverb(){
		return $this->proverb;
	}
	
	/**
	 * 
	 * @param string $meaning
	 * @return void
	 */
	public function setMeaning($meaning){
		$this->meaning = $meaning;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getMeaning(){
		return $this->meaning;
	}
}
?>