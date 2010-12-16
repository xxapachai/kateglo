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
use kateglo\application\daos;
use kateglo\application\utilities;
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
	 * @var kateglo\application\daos\interfaces\Entry
	 */
	private $entry;
	
	/**
	 * 
	 * @var kateglo\application\utilities\interfaces\SearchEngine
	 */
	private $searchEngine;
	
	/**
	 *
	 * @params kateglo\application\daos\interfaces\Entry $entry
	 * @return void
	 * 
	 * @Inject
	 */
	public function setEntry(daos\interfaces\Entry $entry) {
		$this->entry = $entry;
	}
	
	/**
	 *
	 * @params kateglo\application\utilities\interfaces\SearchEngine $searchEngine
	 * @return void
	 * 
	 * @Inject
	 */
	public function setSearchEngine(utilities\interfaces\SearchEngine $searchEngine) {
		$this->searchEngine = $searchEngine;
	}
	
	/**
	 * 
	 * @param string $entry
	 * @return kateglo\application\models\Entry
	 */
	public function getEntry($entry) {
		$result = $this->entry->getByEntry ( $entry );
		
		return $result;
	}
	
	/**
	 * 
	 * @return int
	 */
	public function getTotalCount() {
		$result = $this->entry->getTotalCount ();
		
		return $result;
	}
	
	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function randomMisspelled($limit = 5) {
		$request = $this->searchEngine->getSolrService ()->search ( 'spelled:*', 0, $limit, array ('sort' => 'random_' . rand ( 1, 100000 ) . ' asc' ) );
		if ($request->getHttpStatus () == 200) {
			
			for($i = 0; $i < count ( $request->response->docs ); $i ++) {
				/*@var $docs Apache_Solr_Document */
				$docs = $request->response->docs [$i];
				$newDocs ['documentBoost'] = $docs->getBoost ();
				$newDocs ['fields'] = $docs->getFields ();
				$newDocs ['fieldBoosts'] = $docs->getFieldBoosts ();
				$request->response->docs [$i] = $newDocs;
			}
			
			return ( array ) $request->response;
		} else {
			throw new exceptions\EntryException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
		}
	}
	
	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function randomEntry($limit = 10) {
		$request = $this->searchEngine->getSolrService ()->search ( 'entry:*', 0, $limit, array ('sort' => 'random_' . rand ( 1, 100000 ) . ' asc' ) );
		if ($request->getHttpStatus () == 200) {
			
			for($i = 0; $i < count ( $request->response->docs ); $i ++) {
				/*@var $docs Apache_Solr_Document */
				$docs = $request->response->docs [$i];
				$newDocs ['documentBoost'] = $docs->getBoost ();
				$newDocs ['fields'] = $docs->getFields ();
				$newDocs ['fieldBoosts'] = $docs->getFieldBoosts ();
				$request->response->docs [$i] = $newDocs;
			}
			
			return ( array ) $request->response;
		} else {
			throw new exceptions\EntryException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
		}
	}
	
	/**
	 * 
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function searchEntry($searchText, $offset = 0, $limit = 10, $params = array()) {
		
		try {
			$searchText = (empty ( $searchText )) ? '*' : $searchText;
			$request = $this->searchEngine->getSolrService ()->search ( $searchText, $offset, $limit, $params );
			for($i = 0; $i < count ( $request->response->docs ); $i ++) {
				/*@var $docs Apache_Solr_Document */
				$docs = $request->response->docs [$i];
				$newDocs ['documentBoost'] = $docs->getBoost ();
				$newDocs ['fields'] = $docs->getFields ();
				$newDocs ['fieldBoosts'] = $docs->getFieldBoosts ();
				$request->response->docs [$i] = $newDocs;
			}
			
			return ( array ) $request->response;
		} catch (\Apache_Solr_Exception $e ) {
			throw new exceptions\EntryException ( $e->getMessage () );
		}
	
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 */
	public function searchThesaurus($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		return $this->searchEntry ( '(' . $searchText . ' AND synonym:*)', $offset, $limit, $params );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 */
	public function searchProverb($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		return $this->searchEntry ( '(' . $searchText . ' AND typeExact:Perihbahasa)', $offset, $limit, $params );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 */
	public function searchAcronym($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		return $this->searchEntry ( '(' . $searchText . ' AND (typeExact:Akronim or typeExact:Singkatan ) )', $offset, $limit, $params );
	}
}
?>