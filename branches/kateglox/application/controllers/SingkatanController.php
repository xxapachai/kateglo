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
use kateglo\application\services\interfaces\Pagination;
use kateglo\application\faces\interfaces\Search;
use kateglo\application\services\interfaces\Entry;
use kateglo\application\services\interfaces\StaticData;
use kateglo\application\faces\Hit;
/**
 *
 *
 *
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class SingkatanController extends Zend_Controller_Action_Stubbles {

	/**
	 *
	 * Enter description here ...
	 * @var kateglo\application\services\interfaces\Entry;
	 */
	private $entry;

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\services\interfaces\StaticData;
	 */
	private $staticData;

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
	 * @param kateglo\application\services\interfaces\Entry $entry
	 *
	 * @Inject
	 */
	public function setStaticData(StaticData $staticData) {
		$this->staticData = $staticData;
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
	 * @param \kateglo\application\services\interfaces\Pagination $pagination
	 *
	 * @Inject
	 */
	public function setPagination(Pagination $pagination) {
		$this->pagination = $pagination;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		parent::init();
		$this->view->search = $this->search;
		$this->limit = (is_numeric($this->_request->getParam('rows')) ? intval($this->_request->getParam('rows')) : 10);
		$this->offset = (is_numeric($this->_request->getParam('start')) ? intval($this->_request->getParam('start'))
				: 0);
		$this->view->formAction = '/singkatan';
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/')
	 * @Produces('text/html')
	 */
	public function indexHtml() {
		$this->_helper->viewRenderer->setNoRender();
		$searchText = $this->getRequest()->getParam($this->search->getFieldName());
		$filterText = $this->getRequest()->getParam($this->search->getFilterName());
		try {
			$cacheId = __CLASS__ . '\\' . 'html' . '\\' . $searchText . '\\' . $this->offset . '\\' . $this->limit;
			if (!$this->evaluatePreCondition($cacheId)) {
				$this->search->setFilterUri($filterText);
				$this->view->search->setFieldValue($searchText);
				$this->search->createFilters();
				/** @var $hits kateglo\application\faces\Hit */
				$hits = $this->entry->searchAcronym($searchText, $this->offset, $this->limit, array('fq' => $this->search->getFilterQuery()));
				$this->view->pagination = $this->pagination->create($hits->getCount(), $this->offset);
				$this->view->hits = $hits;
				$this->content = $this->_helper->viewRenderer->view->render($this->_helper->viewRenderer->getViewScript());
			}
			$this->responseBuilder($cacheId);
		} catch (Apache_Solr_Exception $e) {
			$this->getResponse()->setHttpResponseCode(400);
			$this->content = $this->_helper->viewRenderer->view->render('error/solr.html');
		}
		$this->getResponse()->appendBody($this->content);
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/')
	 * @Produces('application/json')
	 */
	public function indexJson() {
		$searchText = $this->getRequest()->getParam($this->view->search->getFieldName());
		try {
			$cacheId = __CLASS__ . '\\' . 'json' . '\\' . $searchText . '\\' . $this->offset . '\\' . $this->limit;
			if (!$this->evaluatePreCondition($cacheId)) {
				/*@var $hits kateglo\application\faces\Hit */
				$hits = $this->entry->searchAcronymAsJSON($searchText, $this->offset, $this->limit);
				$pagination = $this->pagination->createAsArray($hits->response->{Hit::COUNT}, $this->offset, $this->limit);
				$this->content = array('hits' => $hits, 'pagination' => $pagination);
			}
			$this->responseBuilder($cacheId);
		} catch (Apache_Solr_Exception $e) {
			$this->getResponse()->setHttpResponseCode(400);
			$this->content = array('error' => 'query error');
		}
		$this->_helper->json($this->content);
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/detail')
	 * @Produces('text/html')
	 */
	public function detailHtml() {
		$this->_helper->viewRenderer->setNoRender();
		$cacheId = __CLASS__ . '\\' . 'detailHtml';

		if (!$this->evaluatePreCondition($cacheId)) {
			$this->view->staticData = $this->staticData->getStaticData();
			$this->content = $this->_helper->viewRenderer->view->render('cari/detail.html');
		}

		$this->responseBuilder($cacheId);
		$this->getResponse()->appendBody($this->content);
	}
}

?>