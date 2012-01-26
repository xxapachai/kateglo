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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\daos\exceptions\DomainStateException;
use kateglo\application\models;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\UnitOfWork;

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
class Meaning implements interfaces\Meaning
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
     * @param int $id
     * @param int $version
     * @return \kateglo\application\models\Meaning
     */
    public function getById($id, $version = null)
    {
        if (is_int($version)) {
            $result = $this->entityManager->find(models\Meaning::CLASS_NAME, $id, LockMode::OPTIMISTIC, $version);
        } else {
            $result = $this->entityManager->find(models\Meaning::CLASS_NAME, $id);
        }
        if (!($result instanceof models\Meaning)) {
            throw new DomainObjectNotFoundException ();
        }

        return $result;
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSynonymExclusives($meaningId, array $entryIds)
    {
        $meaning = $this->getById($meaningId);
        $synonymIds = array();
        /** @var $synonym \kateglo\application\models\Synonym */
        foreach ($meaning->getSynonyms() as $synonym) {
            $synonymIds[] = $synonym->getSynonym()->getId();
        }
        $dql = "SELECT  meaning
                FROM " . models\Meaning::CLASS_NAME . " meaning
                    LEFT JOIN meaning.entry entry
                    LEFT JOIN meaning.definitions definitions
                WHERE entry.id IN (" . implode(', ', $entryIds) . ") ";
        if (count($synonymIds) > 0) {
            $dql .= " AND meaning.id NOT IN (" . implode(', ', $synonymIds) . ") ";
        }
        $query = $this->entityManager->createQuery($dql);
        $query->setFetchMode(models\Meaning::CLASS_NAME, "entry", "EAGER");
        $query->setFetchMode(models\Meaning::CLASS_NAME, "definitions", "EAGER");
        $result = $query->getResult();

        return new ArrayCollection($result);
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAntonymExclusives($meaningId, array $entryIds)
    {
        $meaning = $this->getById($meaningId);
        $antonymIds = array();
        /** @var $antonym \kateglo\application\models\Antonym */
        foreach ($meaning->getAntonyms() as $antonym) {
            $antonymIds[] = $antonym->getAntonym()->getId();
        }
        $dql = "SELECT  meaning
                FROM " . models\Meaning::CLASS_NAME . " meaning
                    LEFT JOIN meaning.entry entry
                    LEFT JOIN meaning.definitions definitions
                WHERE entry.id IN (" . implode(', ', $entryIds) . ") ";
        if (count($antonymIds) > 0) {
            $dql .= " AND meaning.id NOT IN (" . implode(', ', $antonymIds) . ") ";
        }
        $query = $this->entityManager->createQuery($dql);
        $query->setFetchMode(models\Meaning::CLASS_NAME, "entry", "EAGER");
        $query->setFetchMode(models\Meaning::CLASS_NAME, "definitions", "EAGER");
        $result = $query->getResult();

        return new ArrayCollection($result);
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRelationExclusives($meaningId, array $entryIds)
    {
        $meaning = $this->getById($meaningId);
        $relationIds = array();
        /** @var $relation \kateglo\application\models\Relation */
        foreach ($meaning->getRelations() as $relation) {
            $relationIds[] = $synonym->getSynonym()->getId();
        }
        $dql = "SELECT  meaning
                FROM " . models\Meaning::CLASS_NAME . " meaning
                    LEFT JOIN meaning.entry entry
                    LEFT JOIN meaning.definitions definitions
                WHERE entry.id IN (" . implode(', ', $entryIds) . ") ";
        if (count($relationIds) > 0) {
            $dql .= " AND meaning.id NOT IN (" . implode(', ', $relationIds) . ") ";
        }
        $query = $this->entityManager->createQuery($dql);
        $query->setFetchMode(models\Meaning::CLASS_NAME, "entry", "EAGER");
        $query->setFetchMode(models\Meaning::CLASS_NAME, "definitions", "EAGER");
        $result = $query->getResult();

        return new ArrayCollection($result);
    }

    public function update(models\Meaning $meaning)
    {
        if ($this->entityManager->getUnitOfWork()->getEntityState($meaning) == UnitOfWork::STATE_MANAGED) {
            $this->entityManager->persist($meaning);
            $this->entityManager->flush();
            return $meaning;
        } else {
            throw new DomainStateException("Wrong State!");
        }
    }
}

?>