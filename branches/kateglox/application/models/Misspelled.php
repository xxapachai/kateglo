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
 * @Table(name="misspelled")
 */
class Misspelled {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * @var int
	 * @Id
	 * @Column(type="integer", name="lemma_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * 
	 * @var kateglo\application\models\Lemma
	 * @ManyToOne(targetEntity="kateglo\application\models\Lemma")
     * @JoinColumn(name="lemma_id", referencedColumnName="lemma_id")
	 */
	protected $misspelled;
	
	/**
	 * 
	 * @var string
	 * @ManyToOne(targetEntity="kateglo\application\models\Lemma")
     * @JoinColumn(name="misspelled_id", referencedColumnName="lemma_id")
	 */
	protected $misspells;
	
	/**
	 * 
	 * @return void
	 */
	public function __construct(){
		$relations = new collections\ArrayCollection();
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
	 * @param kateglo\application\models\Lemma $misspelled
	 * @return void
	 */
	public function setMisspelled(models\Lemma $misspelled){
		$this->misspelled = $misspelled;
	}

	/**
	 *
	 * @return kateglo\application\models\Lemma
	 */
	public function getMisspelled(){
		return $this->misspelled;
	}

	/**
	 *
	 * @return void
	 */
	public function removeMisspelled() {
		if ($this->misspelled !== null) {
			/*@var $misspelled kateglo\application\models\Lemma */
			$misspelled = $this->misspelled;
			$this->misspelled = null;
			$misspelled->removeMisspelled($this);
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Lemma $misspells
	 * @return void
	 */
	public function setMisspells(models\Lemma $misspells){
		$this->misspells = $misspells;
	}

	/**
	 *
	 * @return kateglo\application\models\Lemma
	 */
	public function getMisspells(){
		return $this->misspells;
	}

	/**
	 *
	 * @return void
	 */
	public function removeMisspells() {
		if ($this->misspells !== null) {
			/*@var $misspells kateglo\application\models\Lemma */
			$misspells = $this->misspelled;
			$this->misspells = null;
			$misspells->removeMisspells($this);
		}
	}
}
?>