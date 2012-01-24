<?php
namespace kateglo\application\daos;
/*
 *  $Id: Entry.php 466 2011-12-23 17:09:27Z arthur.purnama $
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
 * @since $LastChangedDate: 2011-12-23 18:09:27 +0100 (Fr, 23 Dez 2011) $
 * @version $LastChangedRevision: 466 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Type implements interfaces\Type
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
     * @param $id
     * @param null $version
     * @return object
     * @throws exceptions\DomainObjectNotFoundException
     */
    public function getById($id, $version = null)
    {
        if (is_int($version)) {
            $result = $this->entityManager->find(models\Type::CLASS_NAME, $id, LockMode::OPTIMISTIC, $version);
        } else {
            $result = $this->entityManager->find(models\Type::CLASS_NAME, $id);
        }
        if (!($result instanceof models\Type)) {
            throw new DomainObjectNotFoundException ();
        }

        return $result;
    }

    /**
     * @param \kateglo\application\models\Type $type
     * @return \kateglo\application\models\Type
     * @throws exceptions\DomainStateException
     */
    public function update(models\Type $type)
    {
        if ($this->entityManager->getUnitOfWork()->getEntityState($type) == UnitOfWork::STATE_MANAGED) {
            $this->entityManager->persist($type);
            $this->entityManager->flush();
            return $type;
        } else {
            throw new DomainStateException("Wrong State!");
        }
    }
}

?>