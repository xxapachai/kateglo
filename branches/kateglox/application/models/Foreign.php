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
 * @Table(name="`foreign`")
 */
class Foreign {

    const CLASS_NAME = __CLASS__;

    /**
     * @var int
     * @Id
     * @Column(type="integer", name="foreign_id")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * Enter description here ...
     * @var int
     * @Version
     * @Column(type="integer", name="foreign_version")
     */
    private $version;

    /**
     *
     * @var string
     * @Column(type="string", name="foreign_name", length=2100)
     */
    private $foreign;

    /**
     * @var kateglo\application\models\Language
     * @ManyToOne(targetEntity="kateglo\application\models\Language")
     * @JoinColumn(name="foreign_language_id", referencedColumnName="language_id")
     */
    private $language;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="kateglo\application\models\Equivalent", mappedBy="foreign", cascade={"all"})
     */
    private $equivalents;

    public function __construct() {
        $this->equivalents = new ArrayCollection ();
    }

    /**
     * @return int
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
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getForeign() {
        return $this->foreign;
    }

    /**
     * @param string $foreign
     */
    public function setForeign($foreign) {
        $this->foreign = $foreign;
    }

    /**
     * @return \kateglo\application\models\Language
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
            $language->removeForeign($this);
        }
    }

    /**
     *
     * @param kateglo\application\models\Equivalent $equivalent
     * @return void
     */
    public function addEquivalent(Equivalent $equivalent) {
        $this->equivalents [] = $equivalent;
        $equivalent->setForeign($this);
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
            $removed->removeForeign();
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
        $array['foreign'] = $this->foreign;
        $array['language'] = ($this->getLanguage() instanceof Language) ? $this->getLanguage()->toArray() : null;
        return $array;
    }

}

?>