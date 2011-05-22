<?php
namespace kateglo\application\daos;
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
use Doctrine\ORM\EntityManager;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\models;
/**
 *
 *
 * @package kateglo\application\daos
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Entry implements interfaces\Entry {

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return void
     *
     * @Inject
     */
    public function setEntityManager(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @see kateglo\application\daos\interfaces\Entry::getByEntry()
     * @param string $entry
     * @return kateglo\application\models\Entry
     */
    public function getByEntry($entry) {
        $query = $this->entityManager->createQuery("
			SELECT 	entry
			FROM " . models\Entry::CLASS_NAME . " entry
			WHERE entry.entry = :entry");
        $query->setParameter('entry', $entry);
        $query->useResultCache(true, 43200, __METHOD__.':'.$entry);
        $result = $query->getResult();
        if (count($result) === 1) {
            if (!($result [0] instanceof models\Entry)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result [0];
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypes() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	type
			FROM " . models\Type::CLASS_NAME . " type");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\Type)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypeCategories() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	typeCategory
			FROM " . models\TypeCategory::CLASS_NAME . " typeCategory");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\TypeCategory)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClasses() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	class
			FROM " . models\Clazz::CLASS_NAME . " class");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\Clazz)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClassCategories() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	classCategory
			FROM " . models\ClazzCategory::CLASS_NAME . " classCategory");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\ClazzCategory)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSourceCategories() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	sourceCategory
			FROM " . models\SourceCategory::CLASS_NAME . " sourceCategory");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\SourceCategory)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDisciplines() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	discipline
			FROM " . models\Discipline::CLASS_NAME . " discipline");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\Discipline)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getLanguages() {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	language
			FROM " . models\Language::CLASS_NAME . " language");
        $query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\Language)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

}

?>