<?php
namespace kateglo\application\services;
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
/** @noinspection PhpUndefinedNamespaceInspection */
use kateglo\application\faces;
use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\daos;
/**
 *
 *
 * @package kateglo\application\services
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class StaticData implements interfaces\StaticData {

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Entry
     */
    private $entry;

    /**
     *
     * @params \kateglo\application\daos\interfaces\Entry $entry
     * @return void
     *
     * @Inject
     */
    public function setEntry(daos\interfaces\Entry $entry) {
        $this->entry = $entry;
    }

    /**
     * Enter description here ...
     * @return \kateglo\application\faces\StaticData
     */
    public function getStaticData() {
        $staticData = new faces\StaticData();
        $staticData->setClazz($this->entry->getClasses());
        $staticData->setClazzCategory($this->entry->getClassCategories());
        $staticData->setDiscipline($this->entry->getDisciplines());
        $staticData->setSource($this->entry->getSourceCategories());
        $staticData->setType($this->entry->getTypes());
        $staticData->setTypeCategory($this->entry->getTypeCategories());
        $staticData->setLanguage($this->entry->getLanguages());
        return $staticData;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypes() {
        $result = $this->entry->getTypes();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypeCategories() {
        $result = $this->entry->getTypeCategories();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClasses() {
        $result = $this->entry->getClasses();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClassCategories() {
        $result = $this->entry->getClassCategories();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSourceCategories() {
        $result = $this->entry->getSourceCategories();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDisciplines() {
        $result = $this->entry->getDisciplines();
        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLanguages() {
        $result = $this->entry->getLanguages();
        return $result;
    }
}

?>