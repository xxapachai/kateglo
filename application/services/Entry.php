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
class Entry implements interfaces\Entry {

	public static $CLASS_NAME = __CLASS__;

	/**
	 *
	 * @var \kateglo\application\daos\interfaces\Entry
	 */
	private $entry;

	/**
	 *
	 * @var \Apache_Solr_Service
	 */
	private $solr;

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
	 *
	 * @return \Apache_Solr_Service
	 */
	public function getSolr() {
		if ($this->solr->ping(4)) {
			return $this->solr;
		} else {
			throw new exceptions\SolrException ();
		}
	}

	/**
	 *
	 * @param \Apache_Solr_Service $solr
	 * @return void
	 *
	 * @Inject
	 */
	public function setSolr(\Apache_Solr_Service $solr = null) {
		$this->solr = $solr;
	}

	/**
	 *
	 * @param int $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function getEntryById($id) {
		if (!is_numeric($id)) {
			throw new IllegalTypeException('Entry Id: "' . $id . '" is Not Numeric');
		}
		$result = $this->entry->getById($id);
		return $result;
	}

	/**
	 *
	 * @param id $entry
	 * @return string
	 */
	public function getEntryByIdAsArray($id) {
		if (!is_numeric($id)) {
			throw new IllegalTypeException('Entry Id: "' . $id . '" is Not Numeric');
		}
		$result = $this->entry->getById($id);
		return $result->toArray();
	}

	/**
	 *
	 * @param string $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function getEntry($entry) {
		$result = $this->entry->getByEntry($entry);
		return $result;
	}

	/**
	 *
	 * @param string $entry
	 * @return string
	 */
	public function getEntryAsArray($entry) {
		$result = $this->entry->getByEntry($entry);
		return $result->toArray();
	}

	/**
	 *
	 * @param int $limit
	 * @return \kateglo\application\faces\Hit
	 */
	public function randomMisspelled($limit = 6) {
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search('ejaan:*', 0, $limit, array('fl' => 'entri, ejaan', 'sort' => 'random_' . rand(1, 100000) . ' asc'));
		if ($request->getHttpStatus() == 200) {
			return $request->response;
		} else {
			throw new exceptions\EntryException ('Status: ' . $request->getHttpStatus() . ' Message: ' . $request->getHttpStatusMessage());
		}
	}

	/**
	 *
	 * @param int $limit
	 * @return \kateglo\application\faces\Hit
	 */
	public function randomEntry($limit = 5) {
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search('entri:* AND definisi:* ', 0, $limit, array('fl' => 'entri, definisi', 'sort' => 'random_' . rand(1, 100000) . ' asc'));
		if ($request->getHttpStatus() == 200) {
			return $request->response;
		} else {
			throw new exceptions\EntryException ('Status: ' . $request->getHttpStatus() . ' Message: ' . $request->getHttpStatusMessage());
		}
	}

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit
	 */
	public function searchEntryAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		return json_decode($request->getRawResponse());
	}

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit
	 */
	public function searchEntry($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		return $this->convertResponse2Faces(json_decode($request->getRawResponse()));
	}

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit
	 */
	public function searchEntryAsDisMax($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$params['qf'] = array_key_exists('qf', $params) ? $params['qf']
				: 'entri definisi contoh sumber disiplin sinonim antonim kelas kategoriKelas salahEja relasi ejaan silabel bentuk kategoriBentuk kategoriSumber bahasa disiplinPadanan';
		$params['defType'] = 'dismax';
		$params['q.alt'] = array_key_exists('q.alt', $params) ? $params['q.alt'] : 'entri:*';
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		return $this->convertResponse2Faces(json_decode($request->getRawResponse()));
	}

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit
	 */
	public function searchEntryAsDisMaxJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$params['qf'] = array_key_exists('qf', $params) ? $params['qf']
				: 'entri definisi contoh sumber disiplin sinonim antonim kelas kategoriKelas salahEja relasi ejaan silabel bentuk kategoriBentuk kategoriSumber bahasa disiplinPadanan';
		$params['defType'] = 'dismax';
		$params['q.alt'] = array_key_exists('q.alt', $params) ? $params['q.alt'] : 'entri:*';
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		return json_decode($request->getRawResponse());
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit|
	 */
	public function searchDictionary($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= "entri:*";
		} else {
			$params['fq'] = "entri:*";
		}
		$params['df'] = "konten";
		return $this->searchEntry($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchDictionaryAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		$params['fq'] = "entri:*";
		$params['df'] = "konten";
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit
	 */
	public function searchThesaurus($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, sinonim, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] = $params['fq'] . " sinonim:*";
		} else {
			$params['fq'] = "sinonim:*";
		}
		return $this->searchEntry($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchThesaurusAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, sinonim, id';
		$params['fq'] = "sinonim:*";
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hit|
	 */
	public function searchProverb($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= "bentukPersis:Peribahasa";
		} else {
			$params['fq'] = "bentukPersis:Peribahasa";
		}
		return $this->searchEntry($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchProverbAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		$params['fq'] = "bentukPersis:Peribahasa";
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hits
	 */
	public function searchAcronym($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= " bentukPersis:Akronim OR bentukPersis:Singkatan";
		} else {
			$params['fq'] = "bentukPersis:Akronim OR bentukPersis:Singkatan";
		}
		return $this->searchEntry($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchAcronymAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$params['fl'] = 'entri, definisi, id';
		$params['fq'] = "bentukPersis:Akronim OR bentukPersis:Singkatan";
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \kateglo\application\faces\Hits
	 */
	public function searchEquivalent($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params['fl'] = 'entri, padanan, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] = $params['fq'] . " asing:*";
		} else {
			$params['fq'] = "asing:*";
		}
		$params['df'] = 'entriAsing';
		return $this->searchEntry($searchText, $offset, $limit, $params);
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchEquivalentAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params['fl'] = 'entri, padanan, id';
		$params['fq'] = "asing:*";
		$params['df'] = 'entriAsing';
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function update(models\Entry $entry) {
		$docs = $this->searchDocumentById($entry->getId());
		$docs->setField('entri', $entry->getEntry());
		$entry = $this->entry->update($entry);
		$this->solr->addDocument($docs);
		$this->solr->commit();
		return $entry;
	}

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function insert(models\Entry $entry) {
		$docs = new \Apache_Solr_Document();
		$entry = $this->entry->insert($entry);
		$docs->setField('entri', $entry->getEntry());
		$docs->setField('id', $entry->getId());
		$this->solr->addDocument($docs);
		$this->solr->commit();
		return $entry;
	}

	/**
	 * @param int $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function delete($id) {
		$entry = $this->entry->delete($id);
		$this->solr->deleteById($id);
		$this->solr->commit();
		return $entry;
	}

	/**
	 * @return \kateglo\application\models\Entry
	 */
	public function wordOfTheDay(){
		return $this->entry->getWordOfTheDay();
	}

	/**
	 * @return array
	 */
	public function wordOfTheDayList(){
		$arrayResult = array();
		$wotdList = $this->entry->getWordOfTheDayList();
		/** @var $wotd \kateglo\application\models\WordOfTheDay */
		foreach($wotdList as $wotd){
			$arrayResult[] = $wotd->toArray();
		}
		return $arrayResult;
	}

	/**
	 * @param $jsonObj
	 * @return \kateglo\application\models\WordOfTheDay
	 */
	public function insertWordOfTheDay($jsonObj){
		return $this->entry->insertWordOfTheDay(new \DateTime($jsonObj->date), $jsonObj->id);
	}

	/**
	 * @param $date
	 * @return bool
	 */
	public function dateIsUsedWordOfTheDay($date){
		return $this->entry->dateIsUsedWordOfTheDay(new \DateTime($date));
	}

	/**
	 * @param int $id
	 * @return \Apache_Solr_Document
	 */
	private function searchDocumentById($id) {
		$params['fl'] = 'id, entri, antonim, disiplin, contoh, definisi, kelas, kategoriKelas, salahEja, relasi, sinonim, ejaan, silabel, bentuk, kategoriBentuk, sumber, kategoriSumber, bahasa, disiplinPadanan, asing, padanan';
		$params['df'] = 'id';
		$request = $this->getSolr()->search($id, 0, 2, $params);
		/** @var \Apache_Solr_Document $docs */
		$docs = $request->response->docs[0];
		return $docs;
	}

}

?>