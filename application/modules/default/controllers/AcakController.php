<?php
/*
 *  $Id: KamusController.php 417 2011-07-21 22:01:14Z arthur.purnama $
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
use kateglo\application\services\interfaces\Entry;
use kateglo\application\services\interfaces\CPanel;
use kateglo\application\services\interfaces\StaticData;
use kateglo\application\controllers\exceptions\HTTPNotFoundException;
use kateglo\application\faces\Hit;
/**
 *
 *
 *
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-07-22 00:01:14 +0200 (Fr, 22 Jul 2011) $
 * @version $LastChangedRevision: 417 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class AcakController extends Zend_Controller_Action_Stubbles {

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\services\interfaces\Entry;
	 */
	private $entry;

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\services\interfaces\CPanel;
	 */
	private $cpanel;

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\services\interfaces\StaticData;
	 */
	private $staticData;

	/**
	 *
	 * Enter description here ...
	 * @var \kateglo\application\faces\interfaces\Search;
	 */
	private $search;

	/**
	 * Enter description here ...
	 * @var \kateglo\application\services\interfaces\Pagination;
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
	 * @param \kateglo\application\services\interfaces\Entry $entry
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
	 * @param \kateglo\application\faces\interfaces\Search $entry
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
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/entri')
	 * @Produces('text/html, application/json')
	 */
	public function entryHtml() {
		$this->_helper->viewRenderer->setNoRender();
		/** @var $hits \kateglo\application\faces\Hit */
		$hits = $this->entry->randomEntry(1);
		$this->getResponse()->setHttpResponseCode(303);
		$this->getResponse()->setHeader('Location', '/entri/' . urlencode($hits->docs[0]->entry));
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/salaheja')
	 * @Produces('text/html, application/json')
	 */
	public function misspelledHtml() {
		$this->_helper->viewRenderer->setNoRender();
		/** @var $hits \kateglo\application\faces\Hit */
		$hits = $this->entry->randomMisspelled(1);
		$this->getResponse()->setHttpResponseCode(303);
		$this->getResponse()->setHeader('Location', '/entri/' . urlencode($hits->docs[0]->entry));
	}

}

?>