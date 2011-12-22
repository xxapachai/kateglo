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
class WordOfTheDay implements interfaces\WordOfTheDay
{

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
    public function setEntry(daos\interfaces\Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return \kateglo\application\models\Entry
     */
    public function getToday()
    {
        return $this->entry->getWordOfTheDay();
    }

    /**
     * @return array
     */
    public function getList()
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
    public function insert($jsonObj)
    {
        return $this->entry->insertWordOfTheDay(new \DateTime($jsonObj->date), $jsonObj->id);
    }

    /**
     * @param $date
     * @return bool
     */
    public function dateIsUsed($date)
    {
        return $this->entry->dateIsUsedWordOfTheDay(new \DateTime($date));
    }

}

?>