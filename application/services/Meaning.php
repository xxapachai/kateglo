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

use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\services\exceptions\IllegalTypeException;
use kateglo\application\models;
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
class Meaning implements interfaces\Meaning
{

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Meaning
     */
    private $meaning;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Type
     */
    private $type;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Search
     */
    private $search;

    /**
     *
     * @params \kateglo\application\daos\interfaces\Meaning $meaning
     * @return void
     *
     * @Inject
     */
    public function setMeaning(daos\interfaces\Meaning $meaning)
    {
        $this->meaning = $meaning;
    }

    /**
     *
     * @params \kateglo\application\daos\interfaces\Type $type
     * @return void
     *
     * @Inject
     */
    public function setType(daos\interfaces\Type $type)
    {
        $this->type = $type;
    }

    /**
     *
     * @params \kateglo\application\daos\interfaces\Search $search
     * @return void
     *
     * @Inject
     */
    public function setSearch(daos\interfaces\Search $search)
    {
        $this->search = $search;
    }


    /**
     * @param $id
     * @param null $version
     * @return \kateglo\application\models\Meaning
     * @throws exceptions\IllegalTypeException
     */
    public function getById($id, $version = null)
    {
        if (!is_numeric($id)) {
            throw new IllegalTypeException('Meaning Id: "' . $id . '" is Not Numeric');
        }
        if (!is_null($version) && !is_numeric($version)) {
            throw new IllegalTypeException('Meaning Version: "' . $version . '" is Not Numeric');
        }
        $result = $this->entry->getById($id, $version);
        return $result;
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSynonymExclusives($meaningId, array $entryIds)
    {
        return $this->meaning->getSynonymExclusives($meaningId, $entryIds);
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAntonymExclusives($meaningId, array $entryIds)
    {
        return $this->meaning->getAntonymExclusives($meaningId, $entryIds);
    }

    /**
     * @param int $meaningId
     * @param array $entryIds
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRelationExclusives($meaningId, array $entryIds)
    {
        return $this->meaning->getRelationExclusives($meaningId, $entryIds);
    }

    /**
     * @param $id
     * @param $version
     * @param array $types
     * @return \kateglo\application\models\Meaning
     */
    public function updateTypes($id, $version, array $types)
    {
        if (!is_numeric($id)) {
            throw new IllegalTypeException('Meaning Id: "' . $id . '" is Not Numeric');
        }
        if (!is_numeric($version)) {
            throw new IllegalTypeException('Meaning Version: "' . $version . '" is Not Numeric');
        }

        $meaning = $this->meaning->getById($id, $version);
        /** @var $type \kateglo\application\models\Type */
        foreach ($meaning->getTypes() as $type) {
            if (in_array($type->getId(), $types)) {
                foreach (array_keys($types, $type->getId()) as $key) {
                    unset($types[$key]);
                }
            } else {
                $meaning->removeType($type);
            }
        }
        foreach ($types as $typeId) {
            $type = $this->type->getById($typeId);
            $meaning->addType($type);
        }
        $this->meaning->update($meaning);
        $this->search->update($meaning->getEntry());
        return $meaning;
    }

}

?>