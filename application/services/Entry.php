<?php
namespace kateglo\application\services;
/*
 *  $Id: Entry.php 296 2011-03-15 12:27:05Z arthur.purnama $
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
use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\daos;
/**
 * 
 * 
 * @package kateglo\application\services
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-15 13:27:05 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 296 $
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
	 * @var Apache_Solr_Service
	 */
	private $service = null;
	
	/**
	 *
	 * @return Apache_Solr_Service
	 */
	public function getSolr() {
		if ($this->solr->ping ()) {
			return $this->solr;
		} else {
			throw new exceptions\SolrException ();
		}
	}
	
	/**
	 *
	 * @param Apache_Solr_Service $service
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
		$request = $this->getSolr()->search ( 'entry:*', 0, 1 );
		if ($request->getHttpStatus () == 200) {
			return $request->response->numFound;
		} else {
			throw new exceptions\EntryException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
		}
	}
	
	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\faces\Hit
	 */
	public function randomMisspelled($limit = 5) {
		$request = $this->getSolr()->search ( 'spelled:*', 0, $limit, array ('sort' => 'random_' . rand ( 1, 100000 ) . ' asc' ) );
		if ($request->getHttpStatus () == 200) {
			return $this->convertResponse2Faces ( $request->response );
		} else {
			throw new exceptions\EntryException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
		}
	}
	
	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\faces\Hit
	 */
	public function randomEntry($limit = 10) {
		$request = $this->getSolr()->search ( 'entry:*', 0, $limit, array ('fl' => 'entry, id', 'sort' => 'random_' . rand ( 1, 100000 ) . ' asc' ) );
		if ($request->getHttpStatus () == 200) {
			return $this->convertResponse2Faces ( $request->response );
		} else {
			throw new exceptions\EntryException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
		}
	}
	
	/**
	 * 
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit
	 */
	public function searchEntry($searchText, $offset = 0, $limit = 10, $params = array()) {
		try {
            $params['spellcheck'] = 'true';
            $params['spellcheck.count'] = 10;
            $params['mlt'] = 'true';
            $params['mlt.fl'] = 'entry,synonym,relation,spelled,antonym,misspelled';
            $params['mlt.mindf'] = 1;
            $params['mlt.mintf'] = 1;
            $params['mlt.count'] = 10;
            $params['facet'] = 'true';
            $params['facet.field=type&facet.field=typeCategory&facet.field=class&facet.field'] = 'classCategory';
			$searchText = (empty ( $searchText )) ? '*' : $searchText;
			$request = $this->getSolr()->search ( $searchText, $offset, $limit, $params );
			return $this->convertResponse2Faces ( $request->response );
		} catch ( \Apache_Solr_Exception $e ) {
			throw new exceptions\EntryException ( $e->getMessage () );
		}
	}
	
	/**
	 * 
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return array
	 */
	public function searchEntryAsArray($searchText, $offset = 0, $limit = 10, $params = array()) {
		try {
            $params['spellcheck'] = 'true';
            $params['spellcheck.count'] = 10;
            $params['mlt'] = 'true';
            $params['mlt.fl'] = 'entry,synonym,relation,spelled,antonym,misspelled';
            $params['mlt.mindf'] = 1;
            $params['mlt.mintf'] = 1;
            $params['mlt.count'] = 10;
            $params['facet'] = 'true';
            $params['facet.field=typeExact&facet.field=typeCategoryExact&facet.field=classExact&facet.field'] = 'classCategoryExact';
            $searchText = (empty ( $searchText )) ? '*' : $searchText;
            $request = $this->getSolr()->search ( $searchText, $offset, $limit, $params );
            return $this->convertResponse2Array ( $request->response )->toArray ();
        } catch ( \Apache_Solr_Exception $e ) {
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
	 * @return kateglo\application\faces\Hit
	 */
	public function searchThesaurus($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, synonym, id';
		$params['fq'] = "synonym:*";
		return $this->searchEntry ( $searchText, $offset, $limit, $params );
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
	public function searchThesaurusAsArray($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, synonym, id';
		$params['fq'] = "synonym:*";
		return $this->searchEntryAsArray ( $searchText, $offset, $limit, $params );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hit|
	 */
	public function searchProverb($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, definition, id';	
		$params['fq'] = "typeExact:Peribahasa";
		return $this->searchEntry ( $searchText, $offset, $limit, $params );
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
	public function searchProverbAsArray($searchText, $asArray = false, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, definition, id';	
		$params['fq'] = "typeExact:Peribahasa";
		return $this->searchEntryAsArray ( $searchText, $offset, $limit, $params );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hits
	 */
	public function searchAcronym($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, definition, id';	
		$params['fq'] = "typeExact:Akronim or typeExact:Singkatan";
		return $this->searchEntry ( $searchText, $offset, $limit, $params );
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
	public function searchAcronymAsArray($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;	
		$params['fl'] = 'entry, definition, id';	
		$params['fq'] = "typeExact:Akronim or typeExact:Singkatan";
		return $this->searchEntryAsArray ( $searchText, $offset, $limit, $params );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 * @return kateglo\application\faces\Hits
	 */
	public function searchEquivalent($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, equivalent, id';
		$params['fq'] = "foreign:*";
		$params['qf'] = "entry foreign";
		return $this->searchEntry ( $searchText, $offset, $limit, $params );
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
	public function searchEquivalentAsArray($searchText, $offset = 0, $limit = 10, $params = array()) {
		$searchText = (empty ( $searchText )) ? '*' : $searchText;
		$params['fl'] = 'entry, equivalent, id';
		$params['fq'] = "foreign:*";
		$params['qf'] = "entry foreign";
		return $this->searchEntryAsArray ( $searchText, $offset, $limit, $params );
	}
	
	/**
	 * Enter description here ...
	 * @param object $response
	 * @return kateglo\application\faces\Hit
	 */
	private function convertResponse2Faces($response) {
		$hit = new Hit ();
		$hit->setCount ( $response->numFound );
		$hit->setStart ( $response->start );
		$hit->setDocuments ( new ArrayCollection () );
		for($i = 0; $i < count ( $response->docs ); $i ++) {
			/*@var $doc Apache_Solr_Document */
			$doc = $response->docs [$i];
			$fields = $doc->getFields ();
			
			$document = new Document ();
			$document->setId ( $fields [Document::ID] );
			$document->setEntry ( $fields [Document::ENTRY] );
			$document->setAntonyms ( $this->convert2Array ( $fields, Document::ANTONYM ) );
			$document->setDisciplines ( $this->convert2Array ( $fields, Document::DISCIPLINE ) );
			$document->setSamples ( $this->convert2Array ( $fields, Document::SAMPLE ) );
			$document->setDefinitions ( $this->convert2Array ( $fields, Document::DEFINITION ) );
			$document->setClasses ( $this->convert2Array ( $fields, Document::CLAZZ ) );
			$document->setClassCategories ( $this->convert2Array ( $fields, Document::CLAZZ_CATEGORY ) );
			$document->setMisspelleds ( $this->convert2Array ( $fields, Document::MISSPELLED ) );
			$document->setRelations ( $this->convert2Array ( $fields, Document::RELATION ) );
			$document->setSynonyms ( $this->convert2Array ( $fields, Document::SYNONYM ) );
			$document->setSpelled ( array_key_exists ( Document::SPELLED, $fields ) ? $fields [Document::SPELLED] : '' );
			$document->setSyllabels ( $this->convert2Array ( $fields, Document::SYLLABEL ) );
			$document->setTypes ( $this->convert2Array ( $fields, Document::TYPE ) );
			$document->setTypeCategories ( $this->convert2Array ( $fields, Document::TYPE_CATEGORY ) );
			$document->setSource ( $this->convert2Array ( $fields, Document::SOURCE ) );
			$document->setSourceCategories ( $this->convert2Array ( $fields, Document::SOURCE_CATEGORY ) );
			$document->setLanguages ( $this->convert2Array ( $fields, Document::LANGUAGE ) );
			$document->setEquivalentDisciplines ( $this->convert2Array ( $fields, Document::EQUIVALENT_DISCIPLINE ) );
			$document->setForeigns ( $this->convert2Array ( $fields, Document::FOREIGN ) );
			$document->setEquivalents ( $this->jsonConvertToEquivalent ( $this->convert2Array ( $fields, Document::EQUIVALENT ) ) );
			
			$hit->getDocuments ()->add ( $document );
		}
		
		return $hit;
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
			foreach ( $array as $json ) {
				$decode = json_decode ( $json  );
				$equivalent = new Equivalent ();
				$equivalent->setForeign ( $decode->{Equivalent::FOREIGN} );
				$equivalent->setLanguage ( $decode->{Equivalent::LANGUAGE} );
				$disciplines = new ArrayCollection ();
				foreach ( $decode->{Equivalent::DISCIPLINE} as $discipline ) {
					$disciplines->add ( $discipline );
				}
				$equivalent->setDisciplines ( $disciplines );
				$newArray->add ( $equivalent );
			}
			return $newArray;
		} else {
			return null;
		}
	}
	
	/**
	 * Enter description here ...
	 * @param object $response
	 * @return array
	 */
	private function convertResponse2Array($response) {
		$hit = new ArrayCollection ();
		$hit->set ( Hit::COUNT, $response->numFound );
		$hit->set ( Hit::START, $response->start );
		$hit->set ( Hit::DOCUMENTS, new ArrayCollection () );
		for($i = 0; $i < count ( $response->docs ); $i ++) {
			/*@var $doc Apache_Solr_Document */
			$doc = $response->docs [$i];
			$hit->get ( Hit::DOCUMENTS )->add ( $doc->getFields () );
		}
		$hit->set ( Hit::DOCUMENTS, $hit->get ( Hit::DOCUMENTS )->toArray () );
		return $hit;
	}
	
	/**
	 * Enter description here ...
	 * @param array $source
	 * @param string $key
	 * @return Doctrine\Common\Collections\ArrayCollection|NULL
	 */
	private function convert2Array($source, $key) {
		if (array_key_exists ( $key, $source )) {
			$array = new ArrayCollection ();
			if (! is_array ( $source [$key] )) {
				$array->add ( $source [$key] );
			} else {
				foreach ( $source [$key] as $item ) {
					$array->add ( $item );
				}
			}
			return $array;
		} else {
			return null;
		}
	}
}
?>