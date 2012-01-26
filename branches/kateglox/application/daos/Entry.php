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
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\models;
use Doctrine\ORM\Query\ResultSetMapping;

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
class Entry implements interfaces\Entry
{

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
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @see kateglo\application\daos\interfaces\Entry::getById()
     * @param int $id
     * @param null $version
     * @return kateglo\application\models\Entry
     */
    public function getById($id, $version = null)
    {
        if (is_int($version)) {
            $result = $this->entityManager->find(models\Entry::CLASS_NAME, $id, LockMode::OPTIMISTIC, $version);
        } else {
            $result = $this->entityManager->find(models\Entry::CLASS_NAME, $id);
        }
        if (!($result instanceof models\Entry)) {
            throw new DomainObjectNotFoundException ();
        }

        return $result;
    }

    /**
     *
     * @see kateglo\application\daos\interfaces\Entry::getByEntry()
     * @param string $entry
     * @param null $version
     * @return kateglo\application\models\Entry
     */
    public function getByEntry($entry, $version = null)
    {
        /** @var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	entry
			FROM " . models\Entry::CLASS_NAME . " entry
			WHERE entry.entry = :entry");
        $query->setParameter('entry', $entry);
        /** @var $result \kateglo\application\models\Entry */
        $result = $query->getResult();
        if (!($result[0] instanceof models\Entry)) {
            throw new DomainObjectNotFoundException ();
        } else {
            $result = $result[0];
            if (is_int($version)) {
                if ($result->getVersion() != $version) {
                    throw OptimisticLockException::lockFailedVersionMissmatch(\kateglo\application\models\Entry::CLASS_NAME, $version, $result->getVersion());
                }
            }
            return $result;
        }
    }

    /**
     * Enter description here ...
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypes()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	type
			FROM " . models\Type::CLASS_NAME . " type");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getTypeCategories()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	typeCategory
			FROM " . models\TypeCategory::CLASS_NAME . " typeCategory");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getClasses()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	class
			FROM " . models\Clazz::CLASS_NAME . " class");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getClassCategories()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	classCategory
			FROM " . models\ClazzCategory::CLASS_NAME . " classCategory");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getSourceCategories()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	sourceCategory
			FROM " . models\SourceCategory::CLASS_NAME . " sourceCategory");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getDisciplines()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	discipline
			FROM " . models\Discipline::CLASS_NAME . " discipline");
        //$query->useResultCache(true, 43200, __METHOD__);
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
    public function getLanguages()
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	language
			FROM " . models\Language::CLASS_NAME . " language");
        //$query->useResultCache(true, 43200, __METHOD__);
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

    /**
     * Enter description here ...
     * @param $entries \Doctrine\Common\Collections\ArrayCollection
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMeanings($entries)
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
			SELECT 	entry, meanings, definitions
			FROM " . models\Entry::CLASS_NAME . " entry
			LEFT JOIN entry.meanings meanings
			LEFT JOIN meanings.definitions definitions
			WHERE entry.id IN (" . implode(', ', $entries) . ")");
        $query->setFetchMode(models\Entry::CLASS_NAME, "meanings", "EAGER");
        $query->setFetchMode(models\Meaning::CLASS_NAME, "definitions", "EAGER");
        //$query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();

        if (count($result) > 0) {
            if (!($result [0] instanceof models\Entry)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @param $foreigns \Doctrine\Common\Collections\ArrayCollection
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getForeigns($foreigns)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(models\Foreign::CLASS_NAME, 'frg');
        $rsm->addFieldResult('frg', 'foreign_id', 'id');
        $rsm->addFieldResult('frg', 'foreign_version', 'version');
        $rsm->addFieldResult('frg', 'foreign_name', 'foreign');
        $rsm->addJoinedEntityResult(models\Language::CLASS_NAME, 'lang', 'frg', 'language');
        $rsm->addFieldResult('lang', 'language_id', 'id');
        $rsm->addFieldResult('lang', 'language_version', 'version');
        $rsm->addFieldResult('lang', 'language_name', 'language');
        $sql = 'select * from `foreign` frg left join language lang on frg.foreign_language_id = lang.language_id ' .
            "where frg.foreign_name in ('" . implode("', '", array_map('addslashes', $foreigns)) . "')";
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        //$query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();

        if (count($result) > 0) {
            if (!($result [0] instanceof models\Foreign)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @param $entryIds \Doctrine\Common\Collections\ArrayCollection
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function getForeignFromEntryIds($entryIds)
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
        			SELECT 	foreign
        			FROM " . models\Foreign::CLASS_NAME . " foreign
        			LEFT JOIN foreign.equivalents equivalent
        			LEFT JOIN equivalent.entry entry
        			WHERE entry.id IN (" . implode(', ', $entryIds) . ")");
        //$query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();

        if (count($result) > 0) {
            if (!($result [0] instanceof models\Foreign)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * Enter description here ...
     * @param $entryIds \Doctrine\Common\Collections\ArrayCollection
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function getSourceFromEntryIds($entryIds)
    {
        /**@var $query \Doctrine\ORM\Query */
        $query = $this->entityManager->createQuery("
                    SELECT 	source
                    FROM " . models\Source::CLASS_NAME . " source
                    LEFT JOIN source.entry entry
                    WHERE entry.id IN (" . implode(', ', $entryIds) . ")");
        //$query->useResultCache(true, 43200, __METHOD__);
        $result = $query->getResult();

        if (count($result) > 0) {
            if (!($result [0] instanceof models\Source)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }

        return $result;
    }

    /**
     * @param \kateglo\application\models\Entry $entry
     * @return \kateglo\application\models\Entry
     */
    public function update(models\Entry $entry)
    {
        if ($entry->getId() !== null) {
            $updateEntry = $this->getById($entry->getId());
            $updateEntry->setEntry($entry->getEntry());
            $this->entityManager->persist($updateEntry);
            $this->entityManager->flush();
            return $entry;
        } else {
            throw \Exception('Cannot update without id.');
        }
    }

    /**
     * @param \kateglo\application\models\Entry $entry
     * @return \kateglo\application\models\Entry
     */
    public function insert(models\Entry $entry)
    {
        if ($entry->getEntry() !== null) {
            if ($entry->getMeanings()->count() === 0) {
                $entry->addMeaning(new models\Meaning());
            }
            $this->entityManager->persist($entry);
            $this->entityManager->flush();
            return $entry;
        } else {
            throw \Exception('Cannot create without entry.');
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        if (is_int($id)) {
            $entry = $this->entityManager->find(models\Entry::CLASS_NAME, $id);
            $result = clone($entry);
            $this->entityManager->remove($entry);
            $this->entityManager->flush();
            return $result;
        } else {
            throw \Exception('Cannot create without entry.');
        }
    }

    /**
     * @throws exceptions\DomainObjectNotFoundException|exceptions\DomainResultEmptyException
     * @return \kateglo\application\models\Entry
     */
    public function getWordOfTheDay()
    {
        $query = $this->entityManager->createQuery("
			SELECT 	wotd
			FROM " . models\WordOfTheDay::CLASS_NAME . " wotd
			ORDER BY wotd.id ");
        //$query->useResultCache(true, 43200, __METHOD__.':'.$entry);
        $query->setMaxResults(1);
        $result = $query->getResult();
        if (count($result) === 1) {
            if (!($result [0] instanceof models\WordOfTheDay)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }
        /** @var $wordOfTheDay \kateglo\application\models\WordOfTheDay */
        $wordOfTheDay = $result[0];
        return $wordOfTheDay->getEntry();
    }

    /**
     * @param \DateTime $date
     * @param $entryId
     * @return \kateglo\application\models\WordOfTheDay
     */
    public function insertWordOfTheDay(\DateTime $date, $entryId)
    {
        $entry = $this->getById($entryId);
        $wotd = new models\WordOfTheDay();
        $wotd->setDate($date);
        $wotd->setEntry($entry);
        $this->entityManager->persist($wotd);
        $this->entityManager->flush();
        return $wotd;
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function dateIsUsedWordOfTheDay(\DateTime $date)
    {
        $query = $this->entityManager->createQuery("
			SELECT wotd
			FROM " . models\WordOfTheDay::CLASS_NAME . " wotd
			WHERE wotd.date = '" . $date->format(\DateTime::ISO8601) . "' ");
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\WordOfTheDay)) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * @throws exceptions\DomainObjectNotFoundException|exceptions\DomainResultEmptyException
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWordOfTheDayList()
    {
        $query = $this->entityManager->createQuery("
			SELECT 	wotd
			FROM " . models\WordOfTheDay::CLASS_NAME . " wotd");
        //$query->useResultCache(true, 43200, __METHOD__.':'.$entry);
        $result = $query->getResult();
        if (count($result) > 0) {
            if (!($result [0] instanceof models\WordOfTheDay)) {
                throw new DomainObjectNotFoundException ();
            }
        } else {
            throw new DomainResultEmptyException ();
        }
        return $result;
    }
}

?>