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
	 * @return int
	 */
	public function getTotalCount() {
		$entry = $this->searchEntryAsJSON('', 0, 0);
		$thesaurus = $this->searchThesaurusAsJSON('', 0, 0);
		$equivalent = $this->searchEquivalentAsJSON('', 0, 0);
		$proverb = $this->searchProverbAsJSON('', 0, 0);
		$acronym = $this->searchAcronymAsJSON('', 0, 0);
		return array('entry' => $entry->response->numFound, 'thesaurus' => $thesaurus->response->numFound, 'equivalent' => $equivalent->response->numFound, 'proverb' => $proverb->response->numFound, 'acronym' => $acronym->response->numFound);
		if ($request->getHttpStatus() == 200) {
			return $request->response->numFound;
		} else {
			throw new exceptions\EntryException ('Status: ' . $request->getHttpStatus() . ' Message: ' . $request->getHttpStatusMessage());
		}
	}

	/**
	 *
	 * @param int $limit
	 * @return \kateglo\application\faces\Hit
	 */
	public function randomMisspelled($limit = 6) {
		$this->getSolr()->setCreateDocuments(false);
		$request = $this->getSolr()->search('spelled:*', 0, $limit, array('fl' => 'entry, spelled', 'sort' => 'random_' . rand(1, 100000) . ' asc'));
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
		$request = $this->getSolr()->search('entry:* AND definition:* AND type:"Kata dasar mandiri"', 0, $limit, array('fl' => 'entry, definition', 'sort' => 'random_' . rand(1, 100000) . ' asc'));
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
				: 'entry definition sample source discipline synonym antonym class classCategory misspelled relation spelled syllabel type typeCategory sourceCategory language equivalentDiscipline equivalent';
		$params['defType'] = 'dismax';
		$params['q.alt'] = array_key_exists('q.alt', $params) ? $params['q.alt'] : 'entry:*';
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
				: 'entry definition sample source discipline synonym antonym class classCategory misspelled relation spelled syllabel type typeCategory sourceCategory language equivalentDiscipline equivalent';
		$params['defType'] = 'dismax';
		$params['q.alt'] = array_key_exists('q.alt', $params) ? $params['q.alt'] : 'entry:*';
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
		$params['fl'] = 'entry, definition, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= "entry:*";
		} else {
			$params['fq'] = "entry:*";
		}
		$params['df'] = "content";
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
		$params['fl'] = 'entry, definition, id';
		$params['fq'] = "entry:*";
		$params['df'] = "content";
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
		$params['fl'] = 'entry, synonym, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] = $params['fq'] . " synonym:*";
		} else {
			$params['fq'] = "synonym:*";
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
		$params['fl'] = 'entry, synonym, id';
		$params['fq'] = "synonym:*";
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
		$params['fl'] = 'entry, definition, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= "typeExact:Peribahasa";
		} else {
			$params['fq'] = "typeExact:Peribahasa";
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
		$params['fl'] = 'entry, definition, id';
		$params['fq'] = "typeExact:Peribahasa";
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
		$params['fl'] = 'entry, definition, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] .= " typeExact:Akronim OR typeExact:Singkatan";
		} else {
			$params['fq'] = "typeExact:Akronim OR typeExact:Singkatan";
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
		$params['fl'] = 'entry, definition, id';
		$params['fq'] = "typeExact:Akronim OR typeExact:Singkatan";
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
		$params['fl'] = 'entry, equivalent, id';
		if (array_key_exists('fq', $params)) {
			$params['fq'] = $params['fq'] . " foreign:*";
		} else {
			$params['fq'] = "foreign:*";
		}
		$params['df'] = 'entryForeign';
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
		$params['fl'] = 'entry, equivalent, id';
		$params['fq'] = "foreign:*";
		$params['df'] = 'entryForeign';
		return $this->searchEntryAsJSON($searchText, $offset, $limit, $params);
	}

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	public function update(models\Entry $entry) {
		$docs = $this->searchDocumentById($entry->getId());
		$docs->setField('entry', $entry->getEntry());
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
		$docs->setField('entry', $entry->getEntry());
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
	 * @param int $id
	 * @return \Apache_Solr_Document
	 */
	private function searchDocumentById($id) {
		$params['fl'] = 'id, entry, antonym, discipline, sample, definition, class, classCategory, misspelled, relation, synonym, spelled, syllabel, type, typeCategory, source, sourceCategory, language, equivalentDiscipline, foreign, equivalent';
		$params['df'] = 'id';
		$request = $this->getSolr()->search($id, 0, 2, $params);
		/** @var \Apache_Solr_Document $docs */
		$docs = $request->response->docs[0];
		return $docs;
	}

	/**
	 * @param string $searchText
	 * @param array $params
	 * @return array
	 */
	private function getDefaultParams($searchText, $params = array()) {
		if (!array_key_exists('fl', $params)) $params['fl'] = 'entry, definition, id';
		$params['q.op'] = 'AND';
		$params['spellcheck'] = 'true';
		$params['spellcheck.count'] = 10;
		$params['spellcheck.collate'] = 'true';
		$params['spellcheck.maxCollationTries'] = 1000;
		$params['spellcheck.extendedResults'] = 'true';
		$params['mlt'] = 'true';
		$params['mlt.fl'] = 'entry,synonym,relation,spelled,antonym,misspelled';
		$params['mlt.mindf'] = 1;
		$params['mlt.mintf'] = 1;
		$params['mlt.count'] = 10;
		$params['facet'] = 'true';
		$params['facet.field'] = array('typeExact', 'typeCategoryExact', 'classExact', 'classCategoryExact', 'sourceCategoryExact', 'disciplineExact', 'equivalentDisciplineExact');
		$params['spellcheck.q'] = $searchText;
		return $params;
	}

	/**
	 * Enter description here ...
	 * @param object $response
	 * @return kateglo\application\faces\Hit
	 */
	private function convertResponse2Faces($response) {
		$hit = new Hit ();
		$hit->setTime($response->responseHeader->{Hit::TIME});
		$hit->setCount($response->response->numFound);
		$hit->setStart($response->response->start);
		$hit->setDocuments(new ArrayCollection ());
		for ($i = 0; $i < count($response->response->docs); $i++) {

			$doc = $response->response->docs [$i];
			$document = $this->createDocuments($doc);

			$moreLikeThis = new Hit();
			$moreLikeThis->setCount($response->moreLikeThis->{$document->getId()}->numFound);
			$moreLikeThis->setStart($response->moreLikeThis->{$document->getId()}->start);
			$moreLikeThis->setDocuments(new ArrayCollection ());
			for ($j = 0; $j < count($response->moreLikeThis->{$document->getId()}->docs); $j++) {

				$mltDoc = $response->moreLikeThis->{$document->getId()}->docs [$j];
				$mltDocument = $this->createDocuments($doc);
				$moreLikeThis->getDocuments()->add($mltDocument);
			}
			$document->setMoreLikeThis($moreLikeThis);
			$hit->getDocuments()->add($document);
		}
		$hit->setFacet(new Facet());
		$hit->getFacet()->setClazz(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::CLAZZ}))));
		$hit->getFacet()->setClazzCategory(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::CLAZZ_CATEGORY}))));
		$hit->getFacet()->setType(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::TYPE}))));
		$hit->getFacet()->setTypeCategory(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::TYPE_CATEGORY}))));
		$hit->getFacet()->setDiscipline(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::DISCIPLINE}))));
		$hit->getFacet()->setSource(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->{Facet::SOURCE}))));

		if (isset($response->spellcheck)) {
			$hit->setSpellcheck(new Spellcheck());
			$spellcheck = get_object_vars($response->spellcheck->{Spellcheck::SUGGESTIONS});
			if (array_key_exists(Spellcheck::CORRECTLY_SPELLED, $spellcheck)) {
				$hit->getSpellcheck()->setCorrectlySpelled($spellcheck[Spellcheck::CORRECTLY_SPELLED]);
				unset($spellcheck[Spellcheck::CORRECTLY_SPELLED]);
			}
			if (array_key_exists(Spellcheck::COLLATION, $spellcheck)) {
				$hit->getSpellcheck()->setCollation($spellcheck[Spellcheck::COLLATION]);
				unset($spellcheck[Spellcheck::COLLATION]);
			}
			$suggestions = new ArrayCollection();
			foreach ($spellcheck as $item) {
				foreach ($item->{Spellcheck::SUGGESTION} as $suggestion) {
					$suggestions->add(new Suggestion($suggestion->{Suggestion::WORD}, $suggestion->{Suggestion::FREQUENCY}));
				}
			}
			$hit->getSpellcheck()->setSuggestions($suggestions);
		}
		return $hit;
	}

	/**
	 *
	 * @param array $fields
	 * @return array
	 */
	private function convertFacets($facets) {
		$newFacets = $facets;
		foreach ($facets as $key => $value) {
			if ($value == 0) {
				unset($newFacets[$key]);
			}
		}
		return $newFacets;
	}

	/**
	 * @param  $fields
	 * @return \kateglo\application\faces\Document
	 */
	private function createDocuments($fields) {
		$document = new Document ();
		$document->setId($fields->{Document::ID});
		$document->setEntry($fields->{Document::ENTRY});
		$document->setAntonyms($this->convert2Array($fields, Document::ANTONYM));
		$document->setDisciplines($this->convert2Array($fields, Document::DISCIPLINE));
		$document->setSamples($this->convert2Array($fields, Document::SAMPLE));
		$document->setDefinitions($this->convert2Array($fields, Document::DEFINITION));
		$document->setClasses($this->convert2Array($fields, Document::CLAZZ));
		$document->setClassCategories($this->convert2Array($fields, Document::CLAZZ_CATEGORY));
		$document->setMisspelleds($this->convert2Array($fields, Document::MISSPELLED));
		$document->setRelations($this->convert2Array($fields, Document::RELATION));
		$document->setSynonyms($this->convert2Array($fields, Document::SYNONYM));
		$document->setSpelled(property_exists($fields, Document::SPELLED) ? $fields->{Document::SPELLED} : '');
		$document->setSyllabels($this->convert2Array($fields, Document::SYLLABEL));
		$document->setTypes($this->convert2Array($fields, Document::TYPE));
		$document->setTypeCategories($this->convert2Array($fields, Document::TYPE_CATEGORY));
		$document->setSource($this->convert2Array($fields, Document::SOURCE));
		$document->setSourceCategories($this->convert2Array($fields, Document::SOURCE_CATEGORY));
		$document->setLanguages($this->convert2Array($fields, Document::LANGUAGE));
		$document->setEquivalentDisciplines($this->convert2Array($fields, Document::EQUIVALENT_DISCIPLINE));
		$document->setForeigns($this->convert2Array($fields, Document::FOREIGN));
		$document->setEquivalents($this->jsonConvertToEquivalent($this->convert2Array($fields, Document::EQUIVALENT)));

		return $document;
	}

	/**
	 *
	 * Enter description here ...
	 * @param Doctrine\Common\Collections\ArrayCollection $array
	 * @return Doctrine\Common\Collections\ArrayCollection|NULL
	 */
	private function jsonConvertToEquivalent($array) {
		if ($array instanceof ArrayCollection) {
			$newArray = new ArrayCollection ();
			foreach ($array as $json) {
				$decode = json_decode($json);
				$equivalent = new Equivalent ();
				$equivalent->setForeign($decode->{Equivalent::FOREIGN});
				$equivalent->setLanguage($decode->{Equivalent::LANGUAGE});
				$disciplines = new ArrayCollection ();
				foreach ($decode->{Equivalent::DISCIPLINE} as $discipline) {
					$disciplines->add($discipline);
				}
				$equivalent->setDisciplines($disciplines);
				$newArray->add($equivalent);
			}
			return $newArray;
		} else {
			return null;
		}
	}

	/**
	 * Enter description here ...
	 * @param array $source
	 * @param string $key
	 * @return Doctrine\Common\Collections\ArrayCollection|NULL
	 */
	private function convert2Array($source, $key) {
		if (property_exists($source, $key)) {
			$array = new ArrayCollection ();
			if (!is_array($source->{$key})) {
				$array->add($source->{$key});
			} else {
				foreach ($source->{$key} as $item) {
					$array->add($item);
				}
			}
			return $array;
		} else {
			return null;
		}
	}
}

?>