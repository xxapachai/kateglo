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
 * @Table(name="entry")
 */
class Entry {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="entry_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 *
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="entry_version")
	 */
	private $version;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="entry_name", unique=true, length=255)
	 */
	private $entry;

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Meaning", mappedBy="entry", cascade={"all"})
	 */
	private $meanings;

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Source", mappedBy="entry", cascade={"all"})
	 */
	private $sources;

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Equivalent", mappedBy="entry", cascade={"all"})
	 */
	private $equivalents;

	/**
	 *
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->meanings = new ArrayCollection ();
		$this->sources = new ArrayCollection ();
		$this->equivalents = new ArrayCollection ();
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
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
	 * @return the $entry
	 */
	public function getEntry() {
		return $this->entry;
	}

	/**
	 * @param string $entry
	 * @return void
	 */
	public function setEntry($entry) {
		$this->entry = $entry;
	}

	/**
	 *
	 * @param kateglo\application\models\Meaning $meaning
	 * @return void
	 */
	public function addMeaning(Meaning $meaning) {
		$this->meanings [] = $meaning;
		$meaning->setEntry($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Meaning $meaning
	 * @return void
	 */
	public function removeMeaning(Meaning $meaning) {
		/*@var $removed kateglo\application\models\Meaning */
		$removed = $this->meanings->removeElement($meaning);
		if ($removed !== null) {
			$removed->removeEntry();
		}
	}

	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getMeanings() {
		return $this->meanings;
	}

	/**
	 *
	 * @param kateglo\application\models\Source $source
	 * @return void
	 */
	public function addSource(Source $source) {
		$this->sources [] = $source;
		$source->setEntry($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Source $source
	 * @return void
	 */
	public function removeSource(Source $source) {
		/*@var $removed kateglo\application\models\Source */
		$removed = $this->sources->removeElement($source);
		if ($removed !== null) {
			$removed->removeEntry();
		}
	}

	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getSources() {
		return $this->sources;
	}

	/**
	 *
	 * @param kateglo\application\models\Equivalent $equivalent
	 * @return void
	 */
	public function addEquivalent(Equivalent $equivalent) {
		$this->equivalents [] = $equivalent;
		$equivalent->setEntry($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Equivalent $equivalent
	 * @return void
	 */
	public function removeEquivalent(Equivalent $equivalent) {
		/*@var $removed kateglo\application\models\Equivalent */
		$removed = $this->equivalents->removeElement($equivalent);
		if ($removed !== null) {
			$removed->removeEntry();
		}
	}

	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getEquivalents() {
		return $this->equivalents;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$array['id'] = $this->id;
		$array['version'] = $this->version;
		$array['entry'] = $this->entry;

		$array['meanings'] = array();
		$meanings = $this->getMeanings();
		foreach ($meanings as $meaning) {
			$array['meanings'][] = $meaning->toArray();
		}

		$array['equivalents'] = array();
		$equivalents = $this->getEquivalents();
		foreach ($equivalents as $equivalent) {
			$array['equivalents'][] = $equivalent->toArray();
		}

		$array['sources'] = array();
		$sources = $this->getSources();
		foreach ($sources as $source) {
			$array['sources'][] = $source->toArray();
		}

		return $array;
	}
}

?>