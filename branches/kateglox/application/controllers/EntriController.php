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
use kateglo\application\helpers\RouteParameter;
use kateglo\application\faces\interfaces\Search;
use kateglo\application\controllers\exceptions\HTTPBadRequestException;
use kateglo\application\services;
use kateglo\application\models;
/**
 *
 *
 * @package kateglo\application\controllers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class EntriController extends Zend_Controller_Action_Stubbles {

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
	 *
	 * Enter description here ...
	 * @param kateglo\application\services\interfaces\Entry $entry
	 *
	 * @Inject
	 */
	public function setEntry(services\interfaces\Entry $entry) {
		$this->entry = $entry;
	}

	/**
	 *
	 * Enter description here ...
	 * @param kateglo\application\faces\interfaces\Search $entry
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
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/{entry}')
	 * @PathParam{text}(entry)
	 * @Produces('text/html')
	 */
	public function indexHtml($text) {
		$this->_helper->viewRenderer->setNoRender();
		$cacheId = __METHOD__ . '\\' . 'html' . '\\' . $text;

		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$this->view->search->setFieldValue($text);
				$this->view->formAction = '/cari';
				/** @var $entry \kateglo\application\models\Entry */
				$entry = $this->entry->getEntry($text);
				$this->view->entry = $entry;
				$cacheId .= $entry->getVersion();
				$this->content = $this->_helper->viewRenderer->view->render('entri/index.html');
			} catch (Apache_Solr_Exception $e) {
				$this->content = $this->_helper->viewRenderer->view->render('error/solr.html');
			}
		}

		$this->responseBuilder($cacheId);
		$this->getResponse()->appendBody($this->content);
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/{entry}')
	 * @PathParam{text}(entry)
	 * @Produces('application/json')
	 */
	public function indexJson($text) {
		$cacheId = __METHOD__ . '\\' . 'json' . '\\' . $text;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryAsArray($text);
				$this->content = $entry;
				$cacheId .= $entry['version'];
			} catch (Apache_Solr_Exception $e) {
				$this->content = array('error' => 'query error');
			}
		}
		$this->responseBuilder($cacheId);
		$this->_helper->json($this->content);
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('text/html')
	 */
	public function entryByIdAsHtml($id) {
		$cacheId = __METHOD__ . '\\' . 'json' . '\\' . $id;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryByIdAsArray($id);
				$this->content = $entry;
				$cacheId .= $entry['version'];
			} catch (Apache_Solr_Exception $e) {
				$this->content = array('error' => 'query error');
			}
		}
		$eTag = empty($this->eTag) ? md5($cacheId . serialize($this->content)) : $this->eTag;
		if ($this->configs->cache->entry) {
			$this->cache->save($cacheId, serialize(array('content' => $this->content, 'eTag' => $eTag)), 3600);
		}
		$this->getResponse()->setHttpResponseCode(303);
		$this->getResponse()->setHeader('Location', '/entri/' . urlencode($entry['entry']));
	}

	/**
	 * @return void
	 * @Get
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('application/json')
	 */
	public function entryByIdAsJson($id) {
		$cacheId = __METHOD__ . '\\' . 'json' . '\\' . $id;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryByIdAsArray($id);
				$this->content = $entry;
				$cacheId .= $entry['version'];
			} catch (Apache_Solr_Exception $e) {
				$this->content = array('error' => 'query error');
			}
		}

		$this->responseBuilder($cacheId);
		$this->_helper->json($this->content);
	}

	/**
	 * @return void
	 * @Post
	 * @Path('/')
	 * @Produces('application/json')
	 * @Consumes('application/json')
	 * @ConsumeParam{requestEntry}
	 */
	public function updateEntry($requestEntry) {
		$entryObj = json_decode($requestEntry);
		if ($entryObj !== null) {
			if (property_exists($entryObj, 'id') && property_exists($entryObj, 'version') &&
				property_exists($entryObj, 'entry')
			) {
				$entry = new models\Entry();
				$entry->setEntry($entryObj->entry);
				$entry->setVersion($entryObj->version);
				$entry->setId($entryObj->id);
				$entry = $this->entry->update($entry);
				$this->_helper->json($entry->toArray());
			} else {
				throw new HTTPBadRequestException('Property not found');
			}
		} else {
			throw new HTTPBadRequestException('Invalid JSON');
		}
	}

	/**
	 * @return void
	 * @Put
	 * @Path('/')
	 * @Produces('application/json')
	 * @Consumes('application/json')
	 * @ConsumeParam{requestEntry}
	 */
	public function insertEntry($requestEntry) {
		$entryObj = json_decode($requestEntry);
		if ($entryObj !== null) {
			if (property_exists($entryObj, 'entry')) {
				$entry = new models\Entry();
				$entry->setEntry($entryObj->entry);
				$entry = $this->entry->insert($entry);
				$this->_helper->json($entry->toArray());
			} else {
				throw new HTTPBadRequestException('Property not found');
			}
		} else {
			throw new HTTPBadRequestException('Invalid JSON');
		}
	}

	/**
	 * @return void
	 * @Delete
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('application/json')
	 */
	public function deleteEntry($id) {
		if ($id !== null && is_numeric($id)) {
				$this->entry->delete(intval($id));
				$this->_helper->json(array());
		} else {
			throw new HTTPBadRequestException('Invalid JSON');
		}
	}
}

?>