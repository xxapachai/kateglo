<?php
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
use kateglo\application\utilities\collections;
use kateglo\application\configs;
use kateglo\application\faces;
use kateglo\application\services;
use kateglo\application\domains;
/**
 *
 *
 * @uses Exception
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-11-09
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class SearchController extends Zend_Controller_Action {

	const CLASS_NAME = __CLASS__;

	public function indexAction() {
		/*@var $request Zend_Controller_Request_Http */
		$request = $this->getRequest();
		$search = new faces\Search();
		$this->view->search = $search;
		$this->view->hits = new collections\ArrayCollection();
		if($request->isGet()){
			$searchText = $request->getParam($search->getFieldName());
			$contextText = $request->getParam($search->getRadioName());
			$search->setFieldValue($searchText);
			$search->setCheckedRadio($contextText);
			if($searchText !== '' || $searchText !== null){
				$hits = null;
				$lucene = new services\Lucene();
				if($contextText == $search->getLemmaRadioValue()){					
					$this->view->hits = $lucene->lemma($searchText);
				}else if($contextText == $search->getGlossaryRadioValue()){
					$this->view->hits = $lucene->glossary($searchText);	
				}else{
					header('location: '.$request->getBaseUrl());
				}
				//$this->view->hits = new collections\ArrayCollection(iterator_to_array(new LimitIterator($hits->getIterator(), (2 - 1) * 10, 10))); 
			}
		}
		
	}
}
?>