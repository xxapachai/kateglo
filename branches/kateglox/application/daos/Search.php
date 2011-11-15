<?php
namespace kateglo\application\daos;
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
use kateglo\application\models\solr\Document;
use kateglo\application\models\solr\Equivalent;
use kateglo\application\models\solr\Facet;
use kateglo\application\models\solr\Hit;
use kateglo\application\models\solr\Spellcheck;
use kateglo\application\models\solr\Suggestion;
use kateglo\application\models\solr\Amount;
use kateglo\application\models\front;
use \Doctrine\Common\Collections\ArrayCollection;
/**
 *
 *
 * @package kateglo\application\daos
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Search implements interfaces\Search
{

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \Apache_Solr_Service
     */
    private $solr;

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
     * @return \kateglo\application\models\solr\Amount
     */
    public function getAmount() {

        $this->getSolr()->setCreateDocuments(false);
        $entry = json_decode($this->getSolr()->search('*', 0, 0, array())->getRawResponse());
        $thesaurus = json_decode($this->getSolr()->search('*', 0, 0, array('fq' => 'sinonim:*'))->getRawResponse());
        $proverb = json_decode($this->getSolr()->search('*', 0, 0, array('fq' => 'bentukPersis:Peribahasa'))->getRawResponse());
        $acronym = json_decode($this->getSolr()->search('*', 0, 0, array('fq' => 'bentukPersis:Akronim OR bentukPersis:Singkatan'))->getRawResponse());
        $equivalent = json_decode($this->getSolr()->search('*', 0, 0, array('fq' => 'asing:*', 'df' => 'entriAsing'))->getRawResponse());

        $amount = new Amount();
        $amount->setEntry($entry->response->numFound);
        $amount->setThesaurus($thesaurus->response->numFound);
        $amount->setProverb($proverb->response->numFound);
        $amount->setAcronym($acronym->response->numFound);
        $amount->setEquivalent($equivalent->response->numFound);

        return $amount;
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet $facet
     * @return kateglo\application\faces\Hit
     */
    public function entry($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        $params = $this->getDefaultParams($searchText);
        $params = $facet == null ? $params : $this->getFilterQuery($params, $facet);
        $searchText = (empty ($searchText)) ? '*' : $searchText;
        $this->getSolr()->setCreateDocuments(false);
        $request = $this->getSolr()->search($searchText, $pagination->getOffset(), $pagination->getLimit(), $params);
        return $this->convertResponse2Models(json_decode($request->getRawResponse()));
    }

    /**
     * @param $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function thesaurus($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        $params = $this->getDefaultParams($searchText);
        $params = $facet == null ? $params : $this->getFilterQuery($params, $facet);
        $searchText = (empty ($searchText)) ? '*' : $searchText;
        $filter = "sinonim:*";
        if (array_key_exists('fq', $params)) {
            $params['fq'] .= " " . $filter;
        } else {
            $params['fq'] = $filter;
        }
        $this->getSolr()->setCreateDocuments(false);
        $request = $this->getSolr()->search($searchText, $pagination->getOffset(), $pagination->getLimit(), $params);
        return $this->convertResponse2Models(json_decode($request->getRawResponse()));
    }

    /**
     * @param $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function equivalent($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        $params = $this->getDefaultParams($searchText);
        $params = $facet == null ? $params : $this->getFilterQuery($params, $facet);
        $searchText = (empty ($searchText)) ? '*' : $searchText;
        $params['df'] = 'entriAsing';
        $filter = "asing:*";
        if (array_key_exists('fq', $params)) {
            $params['fq'] .= " " . $filter;
        } else {
            $params['fq'] = $filter;
        }
        $this->getSolr()->setCreateDocuments(false);
        $request = $this->getSolr()->search($searchText, $pagination->getOffset(), $pagination->getLimit(), $params);
        return $this->convertResponse2Models(json_decode($request->getRawResponse()));
    }

    /**
     * @param $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function proverb($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        $params = $this->getDefaultParams($searchText);
        $params = $facet == null ? $params : $this->getFilterQuery($params, $facet);
        $searchText = (empty ($searchText)) ? '*' : $searchText;
        $filter = "bentukPersis:Peribahasa";
        if (array_key_exists('fq', $params)) {
            $params['fq'] .= " " . $filter;
        } else {
            $params['fq'] = $filter;
        }
        $this->getSolr()->setCreateDocuments(false);
        $request = $this->getSolr()->search($searchText, $pagination->getOffset(), $pagination->getLimit(), $params);
        return $this->convertResponse2Models(json_decode($request->getRawResponse()));
    }

    /**
     * @param $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function acronym($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        $params = $this->getDefaultParams($searchText);
        $params = $facet == null ? $params : $this->getFilterQuery($params, $facet);
        $searchText = (empty ($searchText)) ? '*' : $searchText;
        $filter = "bentukPersis:Akronim OR bentukPersis:Singkatan";
        if (array_key_exists('fq', $params)) {
            $params['fq'] .= " " . $filter;
        } else {
            $params['fq'] = $filter;
        }
        $this->getSolr()->setCreateDocuments(false);
        $request = $this->getSolr()->search($searchText, $pagination->getOffset(), $pagination->getLimit(), $params);
        return $this->convertResponse2Models(json_decode($request->getRawResponse()));
    }

    /**
     * @param array $params
     * @param \kateglo\application\models\front\Facet $facet
     * @return
     */
    private function getFilterQuery($params, front\Facet $facet) {
        $filterQueryArray = array();
        if ($facet->getTypeValue() != "") {
            $filterQueryArray[] = 'bentukPersis:"' . $facet->getTypeValue() . '"';
        }
        if ($facet->getClassValue() != "") {
            $filterQueryArray[] = 'kelasPersis:"' . $facet->getClassValue() . '"';
        }
        if ($facet->getSourceValue() != "") {
            $filterQueryArray[] = 'sumberPersis:"' . $facet->getSourceValue() . '"';
        }
        if ($facet->getDisciplineValue() != "") {
            $filterQueryArray[] = 'disiplinPersis:"' . $facet->getDisciplineValue() . '"';
        }
        $params['fq'] = implode(' ', $filterQueryArray);
        return $params;
    }

    /**
     * @param string $searchText
     * @param array $params
     * @return array
     */
    private function getDefaultParams($searchText, $params = array()) {
        $params['q.op'] = 'AND';
        $params['spellcheck'] = 'true';
        $params['spellcheck.count'] = 10;
        $params['spellcheck.collate'] = 'true';
        $params['spellcheck.maxCollationTries'] = 1000;
        $params['spellcheck.extendedResults'] = 'true';
        $params['mlt'] = 'true';
        $params['mlt.fl'] = 'entri,sinonim,relasi,ejaan,antonim,salahEja';
        $params['mlt.mindf'] = 1;
        $params['mlt.mintf'] = 1;
        $params['mlt.count'] = 10;
        $params['facet'] = 'true';
        $params['facet.field'] = array('bentukPersis', 'kategoriBentukPersis', 'kelasPersis', 'kategoriKelasPersis', 'kategoriSumberPersis', 'disiplinPersis', 'disiplinPadananPersis');
        $params['spellcheck.q'] = $searchText;
        return $params;
    }

    /**
     * Enter description here ...
     * @param object $response
     * @return kateglo\application\faces\Hit
     */
    private function convertResponse2Models($response) {
        $hit = new Hit ();
        $hit->setTime($response->responseHeader->QTime);
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
        $hit->getFacet()->setClazz(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->kelasPersis))));
        $hit->getFacet()->setClazzCategory(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->kategoriKelasPersis))));
        $hit->getFacet()->setType(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->bentukPersis))));
        $hit->getFacet()->setTypeCategory(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->kategoriBentukPersis))));
        $hit->getFacet()->setDiscipline(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->disiplinPersis))));
        $hit->getFacet()->setSource(new ArrayCollection($this->convertFacets(get_object_vars($response->facet_counts->facet_fields->kategoriSumberPersis))));

        if (isset($response->spellcheck)) {
            $hit->setSpellcheck(new Spellcheck());
            $spellcheck = get_object_vars($response->spellcheck->suggestions);
            if (array_key_exists('correctlySpelled', $spellcheck)) {
                $hit->getSpellcheck()->setCorrectlySpelled($spellcheck['correctlySpelled']);
                unset($spellcheck['correctlySpelled']);
            }
            if (array_key_exists('collation', $spellcheck)) {
                $hit->getSpellcheck()->setCollation($spellcheck['collation']);
                unset($spellcheck['collation']);
            }
            $suggestions = new ArrayCollection();
            foreach ($spellcheck as $item) {
                foreach ($item->suggestion as $suggestion) {
                    $suggestions->add(new Suggestion($suggestion->word, $suggestion->freq));
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
        $document->setId($fields->id);
        $document->setEntry($fields->entri);
        $document->setAntonyms($this->convert2Array($fields, 'antonim'));
        $document->setDisciplines($this->convert2Array($fields, 'disiplin'));
        $document->setSamples($this->convert2Array($fields, 'contoh'));
        $document->setDefinitions($this->convert2Array($fields, 'definisi'));
        $document->setClasses($this->convert2Array($fields, 'kelas'));
        $document->setClassCategories($this->convert2Array($fields, 'kategoriKelas'));
        $document->setMisspelleds($this->convert2Array($fields, 'salahEja'));
        $document->setRelations($this->convert2Array($fields, 'relasi'));
        $document->setSynonyms($this->convert2Array($fields, 'sinonim'));
        $document->setSpelled(property_exists($fields, 'ejaan') ? $fields->ejaan : '');
        $document->setSyllabels($this->convert2Array($fields, 'silabel'));
        $document->setTypes($this->convert2Array($fields, 'bentuk'));
        $document->setTypeCategories($this->convert2Array($fields, 'kategoriBentuk'));
        $document->setSource($this->convert2Array($fields, 'sumber'));
        $document->setSourceCategories($this->convert2Array($fields, 'kategoriSumber'));
        $document->setLanguages($this->convert2Array($fields, 'bahasa'));
        $document->setEquivalentDisciplines($this->convert2Array($fields, 'disiplinPadanan'));
        $document->setForeigns($this->convert2Array($fields, 'asing'));
        $document->setEquivalents($this->jsonConvertToEquivalent($this->convert2Array($fields, 'padanan')));

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
                $equivalent->setForeign($decode->foreign);
                $equivalent->setLanguage($decode->language);
                $disciplines = new ArrayCollection ();
                foreach ($decode->discipline as $discipline) {
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