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
class Entry implements interfaces\Entry
{

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Entry
     */
    private $entry;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Search
     */
    private $search;

    /**
     *
     * @params \kateglo\application\daos\interfaces\Entry $entry
     * @return void
     *
     * @Inject
     */
    public function setEntry(daos\interfaces\Entry $entry)
    {
        $this->entry = $entry;
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
     *
     * @param int $entry
     * @return \kateglo\application\models\Entry
     */
    public function getEntryById($id)
    {
        if (!is_numeric($id)) {
            throw new IllegalTypeException('Entry Id: "' . $id . '" is Not Numeric');
        }
        $result = $this->entry->getById($id);
        return $result;
    }

    /**
     *
     * @param string $entry
     * @return \kateglo\application\models\Entry
     */
    public function getEntry($entry)
    {
        $result = $this->entry->getByEntry($entry);
        return $result;
    }

    /**
     * @param \kateglo\application\models\Entry $entry
     * @return \kateglo\application\models\Entry
     */
    public function update(models\Entry $entry)
    {
        $this->search->update($entry);
        $entry = $this->entry->update($entry);
        return $entry;
    }

    /**
     * @param \kateglo\application\models\Entry $entry
     * @return \kateglo\application\models\Entry
     */
    public function insert(models\Entry $entry)
    {
        $entry = $this->entry->insert($entry);
        $this->search->insert($entry);
        return $entry;
    }

    /**
     * @param int $id
     * @return \kateglo\application\models\Entry
     */
    public function delete($id)
    {
        $entry = $this->entry->delete($id);
        $this->search->delete($id);
        return $entry;
    }

    /**
     * @return \kateglo\application\models\Entry
     */
    public function wordOfTheDay()
    {
        return $this->entry->getWordOfTheDay();
    }

    /**
     * @return array
     */
    public function wordOfTheDayList()
    {
        $arrayResult = array();
        $wotdList = $this->entry->getWordOfTheDayList();
        /** @var $wotd \kateglo\application\models\WordOfTheDay */
        foreach ($wotdList as $wotd) {
            $arrayResult[] = $wotd->toArray();
        }
        return $arrayResult;
    }

    /**
     * @param $jsonObj
     * @return \kateglo\application\models\WordOfTheDay
     */
    public function insertWordOfTheDay($jsonObj)
    {
        return $this->entry->insertWordOfTheDay(new \DateTime($jsonObj->date), $jsonObj->id);
    }

    /**
     * @param $date
     * @return bool
     */
    public function dateIsUsedWordOfTheDay($date)
    {
        return $this->entry->dateIsUsedWordOfTheDay(new \DateTime($date));
    }

}

?>