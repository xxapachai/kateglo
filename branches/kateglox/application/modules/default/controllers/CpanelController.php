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
use kateglo\application\services\interfaces\StaticData;
use kateglo\application\services\interfaces\CPanel;
use kateglo\application\services\interfaces\Entry;
use kateglo\application\faces\interfaces\Search;
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
class CpanelController extends Zend_Controller_Action_Stubbles {

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\faces\interfaces\Search;
	 */
	private $search;

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
	 * @var \kateglo\application\services\interfaces\CPanel
	 */
	private $cpanel;

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
	 * @param kateglo\application\services\interfaces\StaticData $staticData
	 *
	 * @Inject
	 */
	public function setStaticData(StaticData $staticData) {
		$this->staticData = $staticData;
	}

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
	 * @param kateglo\application\services\interfaces\CPanel $cpanel
	 *
	 * @Inject
	 */
	public function setCPanel(CPanel $cpanel) {
		$this->cpanel = $cpanel;
	}

	/**
	 *
	 * Enter description here ...
	 * @param \kateglo\application\faces\interfaces\Search $entry
	 *
	 * @Inject
	 */
	public function setSearch(Search $search) {
		$this->search = $search;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		parent::init();
		$this->view->search = $this->search;
		$this->limit = (is_numeric($this->_request->getParam('limit')) ? intval($this->_request->getParam('limit'))
				: 10);
		$this->offset = (is_numeric($this->_request->getParam('start')) ? intval($this->_request->getParam('start'))
				: 0);
		$this->view->formAction = '/cpanel';
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/')
	 * @Produces('text/html')
	 */
	public function indexHtml() {

	}

	/**
	 * @return void
	 * @Get
	 * @Path('/static')
	 * @Produces('application/json')
	 */
	public function staticJson() {
		try {
			$cacheId = __METHOD__;
			if (!$this->evaluatePreCondition($cacheId)) {
				$this->content = $this->staticData->getStaticDataAsArray();
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
	 * @Path('/entri')
	 * @Produces('application/json')
	 */
	public function entryJson() {
		$searchText = $this->getRequest()->getParam($this->view->search->getFieldName());
		try {

			/*@var $hits array */
			$hits = $this->cpanel->searchEntryAsJSON($searchText, $this->offset, $this->limit);
			$this->content = $hits;

		} catch (Apache_Solr_Exception $e) {
			$this->getResponse()->setHttpResponseCode(400);
			$this->content = array('error' => 'query error');
		}
		$this->_helper->json($this->content);
	}

	/**
	 * @return void
	 * @Post
	 * @Path('/entri')
	 * @Produces('application/json')
	 * @Consumes('application/x-www-form-urlencoded')
	 */
	public function createWordOfTheDay() {
		$input = json_decode(json_encode($_POST));
		$dateIsUsed = $this->entry->dateIsUsedWordOfTheDay($input->date);
		if (!$dateIsUsed) {
			$wotd = $this->entry->insertWordOfTheDay($input);
			$input->id = $wotd->getId();
			$input->date = $wotd->getDate();
			$this->_helper->json($input);
		}else{
			$message = array();
			$message['success'] = false;
			$errors['date'] = 'Tanggal telah terpakai';
			$message['errors'] = $errors;
			$this->_helper->json($message);
		}
	}
}

?>