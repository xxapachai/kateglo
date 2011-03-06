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
 * @Table(name="type_category")
 */
class TypeCategory {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="type_category_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="type_category_version") 
	 */
	private $version;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="type_category_name", unique=true, length=255)
	 */
	private $category;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Type", mappedBy="categories", cascade={"all"})
	 */
	private $types;

	/**
	 * 
	 * Construct
	 */
	public function __construct() {
		$this->types = new ArrayCollection ();
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
	 * @return the $category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 *
	 * @param kateglo\application\models\Type $type
	 * @return void
	 */
	public function addType(Type $type) {
		if (! $this->types->contains ( $type )) {
			$this->types [] = $type;
			$type->setCategory ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Type $type
	 * @return void
	 */
	public function removeType(Type $type) {
		/*@var $removed kateglo\application\models\Type */
		$removed = $this->types->removeElement ( $type );
		if ($removed !== null) {
			$removed->removeCategory ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getTypes() {
		return $this->types;
	}	
}

?>