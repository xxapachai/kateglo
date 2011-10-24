<?php
namespace kateglo\application\services\interfaces;
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
use kateglo\application\models;
/**
 *
 *
 * @package kateglo\application\services\interfaces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
interface Entry {

	const INTERFACE_NAME = __CLASS__;

	/**
	 *
	 * @param int $entry
	 * @return \kateglo\application\models\Entry
	 */
	function getEntryById($id);

	/**
	 *
	 * @param id $entry
	 * @return string
	 */
	function getEntryByIdAsArray($id);

	/**
	 *
	 * @param string $entry
	 * @return kateglo\application\models\Entry
	 */
	function getEntry($entry);

	/**
	 *
	 * @param string $entry
	 * @return string
	 */
	function getEntryAsArray($entry);

	/**
	 *
	 * @param int $limit
	 * @return \kateglo\application\faces\Hit
	 */
	function randomMisspelled($limit = 5);

	/**
	 *
	 * @param int $limit
	 * @return \kateglo\application\faces\Hit
	 */
	function randomEntry($limit = 10);

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return string
	 */
	function searchEntryAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
	 */
	function searchEntry($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
	 */
	function searchEntryAsDisMax($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
	 */
	function searchEntryAsDisMaxJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit|array
	 */
	function searchDictionary($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit|array
	 */
	function searchDictionaryAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
	 */
	function searchThesaurus($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	function searchThesaurusAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit|array
	 */
	function searchProverb($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit|array
	 */
	function searchProverbAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hits
	 */
	function searchAcronym($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	function searchAcronymAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hits
	 */
	function searchEquivalent($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	function searchEquivalentAsJSON($searchText, $offset = 0, $limit = 10, $params = array());

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	function update(models\Entry $entry);

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	function insert(models\Entry $entry);

	/**
	 * @param int $id
	 * @return \kateglo\application\models\Entry
	 */
	function delete($id);

	/**
	 * @return \kateglo\application\models\Entry
	 */
	public function wordOfTheDay();

	/**
	 * @return array
	 */
	function wordOfTheDayList();

	/**
	 * @param $jsonObj
	 * @return \kateglo\application\models\WordOfTheDay
	 */
	function insertWordOfTheDay($jsonObj);

	/**
	 * @param $date
	 * @return bool
	 */
	function dateIsUsedWordOfTheDay($date);
}

?>