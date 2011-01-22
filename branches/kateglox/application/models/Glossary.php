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
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
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
	protected $id;
	
	/**
	 * @var kateglo\application\models\Entry
	 * @ManyToOne(targetEntity="kateglo\application\models\Entry")
	 * @JoinColumn(name="glossary_entry_id", referencedColumnName="entry_id")
	 */
	private $entry;
	
	/**
	 * @var kateglo\application\models\Language
	 * @ManyToOne(targetEntity="kateglo\application\models\Language")
	 * @JoinColumn(name="glossary_language_id", referencedColumnName="language_id")
	 * @OrderBy({"locale" = "ASC"})
	 */
	private $language;
	
	/**
	 * @var kateglo\application\models\Discipline
	 * @ManyToOne(targetEntity="kateglo\application\models\Discipline")
	 * @JoinColumn(name="glossary_discipline_id", referencedColumnName="discipline_id")
	 */
	private $discipline;
	
	function __construct() {
	
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
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
			$entry->removeGlossary ( $this );
		}
	}

	/**
	 * @return kateglo\application\models\Language
	 */
	public function getLanguage() {
		return $this->language;
	}
	
	/**
	 * @param kateglo\application\models\Language $language
	 * @return void
	 */
	public function setLanguage(Language $language) {
		$this->language = $language;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeLanguage() {
		if ($this->language !== null) {
			/*@var $language kateglo\application\models\Language */
			$language = $this->language;
			$this->language = null;
			$language->removeGlossary ( $this );
		}
	}	

	/**
	 * @return kateglo\application\models\Discipline
	 */
	public function getDiscipline() {
		return $this->discipline;
	}
	
	/**
	 * @param kateglo\application\models\Discipline $discipline
	 * @return void
	 */
	public function setDiscipline(Discipline $discipline) {
		$this->discipline = $discipline;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeDiscipline() {
		if ($this->discipline !== null) {
			/*@var $discipline kateglo\application\models\Discipline */
			$discipline = $this->discipline;
			$this->discipline = null;
			$discipline->removeGlossary ( $this );
		}
	}	
}

?>