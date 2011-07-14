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

use kateglo\application\faces\Equivalent;
use kateglo\application\faces\Hit;
use kateglo\application\faces\Document;
use kateglo\application\faces\Facet;
use kateglo\application\faces\Spellcheck;
use kateglo\application\faces\Suggestion;
use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\daos;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
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
class CPanel implements interfaces\CPanel {

	public static $CLASS_NAME = __CLASS__;

	/**
	 *
	 * @var \kateglo\application\daos\interfaces\Entry
	 */
	private $entry;

	/**
	 *
	 * @var Apache_Solr_Service
	 */
	private $solr;

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
	 * @return \Apache_Solr_Service
	 */
	public function getSolr($try = 0) {
		if ($this->solr->ping()) {
			return $this->solr;
		} else {
			if ($try < 20) {
				return $this->getSolr($try++);
			} else {
				throw new exceptions\SolrException ();
			}
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
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
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
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function searchMeaningAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		$entries = array();
		$decode = json_decode($request->getRawResponse());
		$response = $decode->response->docs;
		foreach ($response as $value) {
			$entries[] = $value->id;
		}
		$result = array();
		if (count($entries) > 0) {
			try {
				$meanings = $this->entry->getMeanings($entries);
				foreach ($entries as $entryId) {
					/** @var $meaning \kateglo\application\models\Meaning */
					foreach ($meanings as $meaning) {
						if ($meaning->getEntry()->getId() == $entryId) {
							$array = array();
							$array['id'] = $meaning->getId();
							$array['entryId'] = $meaning->getEntry()->getId();
							$array['entry'] = $meaning->getEntry()->getEntry();
							$definitions = $meaning->getDefinitions();
							$array['definition'] = $definitions->first()->getDefinition();
							$array['definitions'] = array();
							/** @var $definition \kateglo\application\models\Definition */
							foreach ($definitions as $definition) {
								$array['definitions'][] = $definition->getDefinition();
							}
							$result[] = $array;
						}
					}
				}
			} catch (DomainResultEmptyException $e) {
				return array();
			}
		}
		return $result;
	}

	/**
	 *
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function searchForeignAsJSON($searchText, $offset = 0, $limit = 10, $params = array()) {
		$params = $this->getDefaultParams($searchText, $params);
		$params['fl'] = 'foreign';
		$params['fq'] = "foreign:*";
		$params['df'] = "foreign";
		$searchText = (empty ($searchText)) ? '*' : $searchText;
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search($searchText, $offset, $limit, $params);
		$foreigns = array();
		$decode = json_decode($request->getRawResponse());
		$response = $decode->response->docs;
		foreach ($response as $foreignArray) {
			foreach ($foreignArray->foreign as $value) {
				$foreigns[] = addslashes($value);
			}
		}
		$result = array();
		if (count($foreigns) > 0) {
			try {
				$foreignObjs = $this->entry->getForeigns($foreigns);
				foreach ($foreigns as $foreignName) {
					/** @var $foreign \kateglo\application\models\Foreign */
					foreach ($foreignObjs as $foreign) {
						if ($foreign->getForeign() == $foreignName) {
							$result[] = $foreign->toArray();
						}
					}
				}
			} catch (DomainResultEmptyException $e) {
				return array();
			}
		}
		return $result;
	}

	/**
	 * @param string $searchText
	 * @param array $params
	 * @return array
	 */
	private function getDefaultParams($searchText, $params = array()) {
		if (!array_key_exists('fl', $params)) $params['fl'] = 'entry, id';
		$params['q.op'] = 'AND';
		return $params;
	}

}

?>