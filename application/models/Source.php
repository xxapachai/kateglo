<?php
namespace kateglo\application\models;
/*
 *  $Id: Source.php 286 2011-03-06 10:56:42Z arthur.purnama $
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
 * @since $LastChangedDate: 2011-03-06 11:56:42 +0100 (So, 06 Mrz 2011) $
 * @version $LastChangedRevision: 286 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="source")
 */
class Source {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="source_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="source_version") 
	 */
	private $version;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="source_url", length=2100)
	 */
	private $url;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="source_text")
	 */
	private $source;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="source_text_clean")
	 */
	private $clean;
	
	/**
	 * @var kateglo\application\models\Meaning
	 * @ManyToOne(targetEntity="kateglo\application\models\SourceCategory")
	 * @JoinColumn(name="source_category_id", referencedColumnName="source_category_id")
	 */
	private $category;
	
	/**
	 * @var kateglo\application\models\Entry
	 * @ManyToOne(targetEntity="kateglo\application\models\Entry")
	 * @JoinColumn(name="source_entry_id", referencedColumnName="entry_id")
	 */
	private $entry;
	
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
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * @return the $category
	 */
	public function getSource() {
		return $this->source;
	}
	
	/**
	 * @param string $category
	 */
	public function setSource($source) {
		$this->source = $source;
	}
	
	/**
	 * @return the $clean
	 */
	public function getClean() {
		return $this->clean;
	}
	
	/**
	 * @param string $clean
	 */
	public function setClean($clean) {
		$this->clean = $clean;
	}
	
	/**
	 * @return kateglo\application\models\SourceCategory
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * @param kateglo\application\models\SourceCategory $category
	 * @return void
	 */
	public function setCategory(SourceCategory $category) {
		$this->category = $category;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeCategory() {
		if ($this->category !== null) {
			/*@var $category kateglo\application\models\SourceCategory */
			$category = $this->category;
			$this->category = null;
			$category->removeSource ( $this );
		}
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
			$entry->removeSource ( $this );
		}
	}

}

?>