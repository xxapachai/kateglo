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
 * @Table(name="type")
 */
class Type
{

    const CLASS_NAME = __CLASS__;

    /**
     * @var int
     * @Id
     * @Column(type="integer", name="type_id")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * Enter description here ...
     * @var int
     * @Version
     * @Column(type="integer", name="type_version")
     */
    private $version;

    /**
     *
     * @var string
     * @Column(type="string", name="type_name", unique=true, length=255)
     */
    private $type;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="kateglo\application\models\Meaning")
     * @JoinTable(name="rel_meaning_type",
     * joinColumns={@JoinColumn(name="rel_type_id", referencedColumnName="type_id")},
     * inverseJoinColumns={@JoinColumn(name="rel_meaning_id", referencedColumnName="meaning_id")}
     * )
     */
    private $meanings;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="kateglo\application\models\TypeCategory")
     * @JoinTable(name="rel_type_category",
     * joinColumns={@JoinColumn(name="rel_type_id", referencedColumnName="type_id")},
     * inverseJoinColumns={@JoinColumn(name="rel_type_category_id", referencedColumnName="type_category_id")}
     * )
     */
    private $categories;

    public function __construct()
    {
        $this->meanings = new ArrayCollection ();
        $this->categories = new ArrayCollection ();
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param kateglo\application\models\Meaning $meaning
     * @return void
     */
    public function addMeaning(Meaning $meaning)
    {
        if (!$this->meanings->contains($meaning)) {
            $this->meanings [] = $meaning;
            $meaning->addType($this);
        }
    }

    /**
     *
     * @param \kateglo\application\models\Meaning $meaning
     * @return void
     */
    public function removeMeaning(Meaning $meaning)
    {
        if ($this->meanings->contains($meaning)) {
            $this->meanings->removeElement($meaning);
            $meaning->removeType($this);
        }
    }

    /**
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getMeanings()
    {
        return $this->meanings;
    }

    /**
     *
     * @param \kateglo\application\models\TypeCategory $category
     * @return void
     */
    public function setCategory(TypeCategory $category)
    {
        if (!$this->categories->contains($category)) {
            $this->categories [0] = $category;
            $category->addType($this);
        }
    }

    /**
     *
     * @param kateglo\application\models\TypeCategory $category
     * @return void
     */
    public function removeCategory()
    {
        $removed = $this->categories->removeElement($this->categories->get(0));
        if ($removed !== null) {
            $removed->removeType($this);
        }
    }

    /**
     *
     * @return kateglo\application\models\TypeCategory
     */
    public function getCategory()
    {
        return $this->categories->get(0);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array['id'] = $this->id;
        $array['version'] = $this->version;
        $array['type'] = $this->type;
        $array['category'] = ($this->getCategory() instanceof TypeCategory) ? $this->getCategory()->toArray() : null;

        return $array;
    }
}

?>