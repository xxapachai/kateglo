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
use kateglo\application\faces\Hit;
use kateglo\application\services\exceptions\EntryException;
use kateglo\application\services\interfaces\Pagination;
use kateglo\application\faces\interfaces\Search;
use kateglo\application\services\interfaces\Entry;
/**
 *
 *
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class TesaurusController extends Zend_Controller_Action_Stubbles {
	
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
	 * @var kateglo\application\services\interfaces\Pagination;
	 */
	private $pagination;
	
	/**
	 * Enter description here ...
	 * @var int
	 */
	private $limit;
	
	/**
	 * Enter description here ...
	 * @var int
	 */
	private $offset;
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\services\interfaces\Entry $entry
	 * 
	 * @Inject
	 */
	public function setEntry(Entry $entry) {
		$this->entry = $entry;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\faces\interfaces\Search $search
	 * 
	 * @Inject
	 */
	public function setSearch(Search $search) {
		$this->search = $search;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param kateglo\application\services\interfaces\Pagination $entry
	 * 
	 * @Inject
	 */
	public function setPages(Pagination $pagination) {
		$this->pagination = $pagination;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		$this->view->search = $this->search;
		$this->limit = (is_numeric ( $this->_request->getParam ( 'rows' ) ) ? intval ( $this->_request->getParam ( 'rows' ) ) : 10);
		$this->offset = (is_numeric ( $this->_request->getParam ( 'start' ) ) ? intval ( $this->_request->getParam ( 'start' ) ) : 0);
		$this->view->appPath = APPLICATION_PATH;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return void
	 */
	public function indexAction() {
		if ($this->_request->isGet ()) {
			$this->view->formAction = '/tesaurus';
			$searchText = $this->_request->getParam ( $this->view->search->getFieldName () );
			$this->view->search->setFieldValue ( $searchText );
			/*@var $hits kateglo\application\faces\Hit */
			$hits = $this->entry->searchThesaurus ( $searchText, $this->offset, $this->limit );
			$this->view->pagination = $this->pagination->create ( $hits->getCount (), $this->offset, $this->limit );
			$this->view->hits = $hits;
		} else {
			//Block other request type?
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return void
	 */
	public function jsonAction() {
		try {
			$searchText = $this->_request->getParam ( $this->view->search->getFieldName () );
			$this->view->search->setFieldValue ( $searchText );
			$hits = $this->entry->searchThesaurusAsArray ( $searchText, $this->offset, $this->limit );
			$pagination = $this->pagination->createAsArray ( $hits[Hit::COUNT], $this->offset, $this->limit );
			$this->_helper->json(array ('hits' => $hits, 'pagination' => $pagination ));
		} catch ( EntryException $e ) {
			$this->getResponse ()->setHttpResponseCode ( 500 );
			$this->view->json = json_encode ( array ('error' => $e->getMessage () ) );
		}
	}
}
?>