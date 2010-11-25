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
use kateglo\application\utilities;
use kateglo\application\configs;
/**
 * 
 * 
 * @package kateglo\application\services
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-11-10
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Search implements interfaces\Search {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * @var kateglo\application\utilities\interfaces\SearchEngine
	 */
	private $searchEngine;
	
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
	 * @param string $searchText
	 * @param int $offset
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function entry($searchText, $offset = 0, $limit = 10) {
		if ($this->searchEngine->getSolrService ()->ping ()) {
			
			$request = $this->searchEngine->getSolrService ()->search ( $searchText, $offset, $limit );
			
			if ($request->getHttpStatus () == 200) {
				
				for ($i = 0; $i < count($request->response->docs); $i++){
					/*@var $docs Apache_Solr_Document */
					$docs = $request->response->docs[$i];
					$newDocs['documentBoost'] = $docs->getBoost();
					$newDocs['fields'] = $docs->getFields();
					$newDocs['fieldBoosts'] = $docs->getFieldBoosts();
					$request->response->docs[$i] = $newDocs;
				}
				
				return (array)$request->response;
			} else {
				throw new exceptions\SearchException ( 'Status: ' . $request->getHttpStatus () . ' Message: ' . $request->getHttpStatusMessage () );
			}
		} else {
			throw new exceptions\SearchException ( 'Search Engine service is not responding !' );
		}
	
	}

}
?>