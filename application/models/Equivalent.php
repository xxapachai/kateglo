<?php
namespace kateglo\application\models;
/*
 *  $Id: Equivalent.php 286 2011-03-06 10:56:42Z arthur.purnama $
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
 * @since $LastChangedDate: 2011-03-06 11:56:42 +0100 (So, 06 Mrz 2011) $
 * @version $LastChangedRevision: 286 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="equivalent")
 */
class Equivalent {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="equivalent_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="equivalent_version") 
	 */
	private $version;
	
	/**
	 * @var kateglo\application\models\Entry
	 * @ManyToOne(targetEntity="kateglo\application\models\Entry")
	 * @JoinColumn(name="equivalent_entry_id", referencedColumnName="entry_id")
	 */
	private $entry;
	
	/**
	 * @var kateglo\application\models\Foreign
	 * @ManyToOne(targetEntity="kateglo\application\models\Foreign")
	 * @JoinColumn(name="equivalent_foreign_id", referencedColumnName="foreign_id")
	 */
	private $foreign;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Discipline", mappedBy="equivalents", cascade={"all"})
	 */
	private $disciplines;
	
	public function __construct() {
		$this->disciplines = new ArrayCollection ();
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param int $version
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

	/**
	 * @return kateglo\application\models\Entry
	 */
	public function getEntry() {
		return $this->entry;
	}
	
	/**
	 * @param kateglo\application\models\Entry $entry
	 * @return void
	 */
	public function setEntry(Entry $entry) {
		$this->entry = $entry;
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
			$entry->removeEquivalent ( $this );
		}
	}

	/**
	 * @return kateglo\application\models\Foreign
	 */
	public function getForeign() {
		return $this->foreign;
	}
	
	/**
	 * @param kateglo\application\models\Foreign $foreign
	 * @return void
	 */
	public function setForeign(Foreign $foreign) {
		$this->foreign = $foreign;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeForeign() {
		if ($this->foreign !== null) {
			/*@var $foreign kateglo\application\models\Foreign */
			$foreign = $this->foreign;
			$this->foreign = null;
			$foreign->removeEquivalent ( $this );
		}
	}	
	
	/**
	 *
	 * @param kateglo\application\models\Discipline $discipline
	 * @return void
	 */
	public function addDiscipline(Discipline $discipline) {
		if (! $this->disciplines->contains ( $discipline )) {
			$this->disciplines [] = $discipline;
			$discipline->addEquivalent ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Discipline $discipline
	 * @return source
	 */
	public function removeDiscipline(Discipline $discipline) {
		/*@var $removed kateglo\application\models\Discipline */
		$removed = $this->disciplines->removeElement ( $discipline );
		if ($removed !== null) {
			$removed->removeEquivalent ( $this );
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDisciplines() {
		return $this->disciplines;
	}
}

?>