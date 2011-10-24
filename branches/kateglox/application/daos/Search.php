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
class Search implements interfaces\Search {

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
	 * @param string $searchText
	 * @param array $params
	 * @return array
	 */
	private function getDefaultParams($searchText, $params = array()) {
		if (!array_key_exists('fl', $params)) $params['fl'] = 'entri, definisi, id';
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

}

?>