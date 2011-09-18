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
use Doctrine\Common\Collections\ArrayCollection;
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
 * @Table(name="wordoftheday")
 */
class WordOfTheDay {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="wordoftheday_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 *
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="wordoftheday_version")
	 */
	private $version;

	/**
	 *
	 * Enter description here ...
	 * @var date
	 * @Column(type="date", name="wordoftheday_date")
	 */
	private $date;

	/**
	 * @var \kateglo\application\models\Entry
	 * @ManyToOne(targetEntity="kateglo\application\models\Entry")
	 * @JoinColumn(name="wordoftheday_entry_id", referencedColumnName="entry_id")
	 */
	private $entry;

	/**
	 *
	 * Construct
	 */
	public function __construct() {
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
	 *
	 * @param date $date
	 * @return void
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 *
	 * @return date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 *
	 * @param kateglo\application\models\Entry $entry
	 * @return void
	 */
	public function setEntry(Entry $entry) {
		$this->entry = $entry;
	}

	/**
	 *
	 * @return \kateglo\application\models\Entry
	 */
	public function getEntry() {
		return $this->entry;
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
			$entry->removeMeaning($this);
		}
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$array['id'] = $this->id;
		$array['version'] = $this->version;
		$array['date'] = $this->date;
		$array['entry'] = $this->entry->toArray();
		return $array;
	}
}

?>