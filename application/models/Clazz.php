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
 * @Table(name="class")
 */
class Clazz {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="class_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="class_version") 
	 */
	private $version;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="class_name", unique=true, length=255)
	 */
	private $clazz;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Definition")
	 * @JoinTable(name="rel_definition_class",
	 * joinColumns={@JoinColumn(name="rel_class_id", referencedColumnName="class_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $definitions;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\ClazzCategory")
	 * @JoinTable(name="rel_class_category",
	 * joinColumns={@JoinColumn(name="rel_class_id", referencedColumnName="class_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_class_category_id", referencedColumnName="class_category_id")}
	 * )
	 */
	private $categories;
	
	public function __construct() {
		$this->definitions = new ArrayCollection();
		$this->categories = new ArrayCollection ();
	}
	
	/**
	 *
	 * @return int
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
	 * @param string $clazz
	 * @return void
	 */
	public function setClazz($clazz) {
		$this->clazz = $clazz;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getClazz() {
		return $this->clazz;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function addDefinition(Definition $definition) {
		if (! $this->definitions->contains ( $definition )) {
			$this->definitions [] = $definition;
			$definition->setClazz ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function removeDefinition(Definition $definition) {
		/*@var $removed  kateglo\application\models\Definition */
		$removed = $this->definitions->removeElement ( $definition );
		if ($removed !== null) {
			$removed->removeClazz();
		}
	}
	
	/**
	 * 
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDefinitions() {
		return $this->definitions;
	}

	/**
	 * 
	 * @param kateglo\application\models\ClazzCategory $category
	 * @return void
	 */
	public function setCategory(ClazzCategory $category) {
		if (! $this->categories->contains ( $category )) {
			$this->categories [0] = $category;
			$category->addClazz ( $this );
		}
	}
	
	/**
	 * 
	 * @param kateglo\application\models\ClazzCategory $category
	 * @return void
	 */
	public function removeCategory(ClazzCategory $category) {
		$removed = $this->categories->removeElement ( $category );
		if ($removed !== null) {
			$removed->removeType ();
		}
	}
	
	/**
	 * 
	 * @return kateglo\application\models\ClazzCategory
	 */
	public function getCategory() {
		return $this->categories->get(0);
	}
}

?>