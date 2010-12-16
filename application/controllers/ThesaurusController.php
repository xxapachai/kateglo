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
use kateglo\application\services\interfaces;
use kateglo\application\utilities;
use kateglo\application\utilities\collections;
use kateglo\application\configs;
use kateglo\application\faces;
use kateglo\application\daos;
use kateglo\application\services\exceptions;
/**
 *
 *
 * @uses Exception
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class ThesaurusController extends Zend_Controller_Action_Stubbles {
	
	/**
	 * 
	 * Enter description here ...
	 * @var kateglo\application\services\interfaces\Entry;
	 */
	private $entry;
	
	/**
	 * 
	 * Enter description here ...
	 * @var kateglo\application\faces\interfaces\Search;
	 */
	private $search;
	
	/**
	 * Enter description here ...
	 * @var kateglo\application\faces\interfaces\Pages;
	 */
	private $pages;
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\services\interfaces\Entry $entry
	 * 
	 * @Inject
	 */
	public function setEntry(interfaces\Entry $entry) {
		$this->entry = $entry;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\faces\interfaces\Search $entry
	 * 
	 * @Inject
	 */
	public function setSearch(faces\interfaces\Search $search) {
		$this->search = $search;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\faces\interfaces\Search $entry
	 * 
	 * @Inject
	 */
	public function setPages(faces\interfaces\Pages $pages) {
		$this->pages = $pages;
	}
	
	public function init() {
		$this->view->search = $this->search;
		$this->pages->setLimit ( (is_numeric ( $this->_request->getParam ( 'rows' ) ) ? intval ( $this->_request->getParam ( 'rows' ) ) : 10) );
		$this->pages->setOffset ( (is_numeric ( $this->_request->getParam ( 'start' ) ) ? intval ( $this->_request->getParam ( 'start' ) ) : 0) );
		$this->view->appPath = APPLICATION_PATH;
	}
	
	public function indexAction() {
		$this->view->formAction = '/thesaurus';
		$this->search ();
		$this->pages->setAmount ( $this->view->hits ['numFound'] );
		$this->view->pages = $this->pages;
	}
	
	public function jsonAction() {
		try {
			$this->view->search = $this->search;
			$this->search ();
			$this->view->json = json_encode ( $this->view->hits );
		} catch ( exceptions\EntryException $e ) {
			$this->getResponse ()->setHttpResponseCode ( 500 );
			$this->view->json = json_encode ( array ('error' => $e->getMessage () ) );
		}
	}
	
	private function search() {
		$this->view->hits = new collections\ArrayCollection ();
		if ($this->_request->isGet ()) {
			$searchText = $this->_request->getParam ( $this->view->search->getFieldName () );
			$this->view->search->setFieldValue ( $searchText );
			$this->view->hits = $this->entry->searchThesaurus ( $searchText,  $this->pages->getOffset (), $this->pages->getLimit (), array ('fl' => 'entry,synonym' ) );
		}
	}
}
?>