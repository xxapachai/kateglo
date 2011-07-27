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
use kateglo\application\controllers\exceptions\HTTPNotFoundException;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
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
	 * @var string $text
	 * @return void
	 * @Get
	 * @Path('/{entry}')
	 * @PathParam{text}(entry)
	 * @Produces('text/html')
	 */
	public function indexHtml($text) {
		$this->_helper->viewRenderer->setNoRender();
		$cacheId = __METHOD__ . '\\' . $text;

		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$this->view->search->setFieldValue($text);
				$this->view->formAction = '/cari';
				/** @var $entry \kateglo\application\models\Entry */
				$entry = $this->entry->getEntry($text);
				$this->view->entry = $entry;
				$this->content = $this->_helper->viewRenderer->view->render('entri/index.html');
			} catch (DomainResultEmptyException $e) {
				throw new HTTPNotFoundException('Entry Not Found.');
			}
		}

		$this->responseBuilder($cacheId);
		$this->getResponse()->appendBody($this->content);
	}

	/**
	 * @var string $text
	 * @return void
	 * @Get
	 * @Path('/{entry}')
	 * @PathParam{text}(entry)
	 * @Produces('application/json')
	 */
	public function indexJson($text) {
		$cacheId = __METHOD__ . '\\' . $text;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryAsArray($text);
				$this->content = $entry;
			} catch (DomainResultEmptyException $e) {
				throw new HTTPNotFoundException('Entry Not Found.');
			}
		}
		$this->responseBuilder($cacheId);
		$this->_helper->json($this->content);
	}

	/**
	 * @var int $id
	 * @return void
	 * @Get
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('text/html')
	 */
	public function entryByIdAsHtml($id) {
		$cacheId = __METHOD__ . '\\' . $id;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryByIdAsArray($id);
				$this->content = $entry;
			} catch (DomainResultEmptyException $e) {
				throw new HTTPNotFoundException('Entry Not Found.');
			}
		}
		if ($this->configs->cache->entry && !$this->cache->contains($cacheId)) {
			$eTag = empty($this->eTag) ? md5($cacheId . serialize($this->content)) : $this->eTag;
			$this->cache->save($cacheId, serialize(array('content' => $this->content, 'eTag' => $eTag)), 0);
		}
		$this->getResponse()->setHttpResponseCode(303);
		$this->getResponse()->setHeader('Location', '/entri/' . urlencode($this->content['entry']));
	}

	/**
	 * @var int $id
	 * @return void
	 * @Get
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('application/json')
	 */
	public function entryByIdAsJson($id) {
		$cacheId = __METHOD__ . '\\' . $id;
		if (!$this->evaluatePreCondition($cacheId)) {
			try {
				$entry = $this->entry->getEntryByIdAsArray($id);
				$this->content = $entry;
			} catch (DomainResultEmptyException $e) {
				throw new HTTPNotFoundException('Entry Not Found.');
			}
		}

		$this->responseBuilder($cacheId);
		$this->_helper->json($this->content);
	}

	/**
	 * @var string $requestEntry
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
				if ($this->configs->cache->entry) {
					$this->saveAllCache($entry);
				}
				$this->_helper->json($entry->toArray());
			} else {
				throw new HTTPBadRequestException('Property not found');
			}
		} else {
			throw new HTTPBadRequestException('Invalid JSON');
		}
	}

	/**
	 * @var string $requestEntry
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
				if ($this->configs->cache->entry) {
					$this->saveAllCache($entry);
				}
				$this->_helper->json($entry->toArray());
			} else {
				throw new HTTPBadRequestException('Property not found');
			}
		} else {
			throw new HTTPBadRequestException('Invalid JSON');
		}
	}

	/**
	 * @var int $id
	 * @return void
	 * @Delete
	 * @Path('/id/{entryId}')
	 * @PathParam{id}(entryId)
	 * @Produces('application/json')
	 */
	public function deleteEntry($id) {
		if ($id !== null && is_numeric($id)) {
			$entry = $this->entry->delete(intval($id));
			print_r($entry); die();
			if ($this->configs->cache->entry) {
				$this->deleteAllCache($entry);
			}
			$this->_helper->json(array());
		} else {
			throw new HTTPBadRequestException('Undefined Identity');
		}
	}

	/**
	 * @var string $requestEntry
	 * @return void
	 * @Post
	 * @Path('/id/{entryId}/arti/{artiId}/type')
	 * @PathParam{entryId}(entryId)
	 * @PathParam{meaningId}(entryId)
	 * @Produces('application/json')
	 * @Consumes('application/json')
	 * @ConsumeParam{requestEntry}
	 */
	public function updateType($entryId, $meaningId, $requestEntry) {
		if ($entryId !== null && is_numeric($entryId) && $meaningId !== null && is_numeric($meaningId)) {
			$typeJSONObj = json_decode($requestEntry);
			if ($typeJSONObj !== null) {
				if (property_exists($typeJSONObj, 'type') && is_array($typeJSONObj->type)) {
					foreach ($typeJSONObj->type as $value) {
						if (!is_numeric($value)) {
							throw new HTTPBadRequestException('Unidentified Identity');
						}
					}
				} else {
					throw new HTTPBadRequestException('Property not found');
				}
			} else {
				throw new HTTPBadRequestException('Invalid JSON');
			}
		} else {
			throw new HTTPBadRequestException('Unidentified Identity');
		}
	}

	/**
	 * @param kateglo\application\models\Entry $entry
	 * @return void
	 */
	private function saveAllCache(models\Entry $entry) {
		$entryAsArray = $entry->toArray();
		$serializeEntryAsArray = serialize($entryAsArray);
		$cacheId = __CLASS__ . '::entryByIdAsJson' . '\\' . $entry->getId();
		$eTag = md5($cacheId . $serializeEntryAsArray);
		$this->cache->save($cacheId, serialize(array('content' => $entryAsArray, 'eTag' => $eTag)), 0);

		$cacheId = __CLASS__ . '::entryByIdAsHtml' . '\\' . $entry->getId();
		$eTag = md5($cacheId . $serializeEntryAsArray);
		$this->cache->save($cacheId, serialize(array('content' => $entryAsArray, 'eTag' => $eTag)), 0);

		$cacheId = __CLASS__ . '::indexJson' . '\\' . $entry->getEntry();
		$eTag = md5($cacheId . $serializeEntryAsArray);
		$this->cache->save($cacheId, serialize(array('content' => $entryAsArray, 'eTag' => $eTag)), 0);

		$cacheId = __CLASS__ . '::indexHtml' . '\\' . $entry->getEntry();
		$this->view->entry = $entry;
		$content = $this->_helper->viewRenderer->view->render('entri/index.html');
		$eTag = md5($cacheId . serialize($content));
		$this->cache->save($cacheId, serialize(array('content' => $this->content, 'eTag' => $eTag)), 0);
	}

	/**
	 * @param kateglo\application\models\Entry $entry
	 * @return void
	 */
	private function deleteAllCache(models\Entry $entry) {
		print_r($entry); die();
		$cacheId = __CLASS__ . '::entryByIdAsJson' . '\\' . $entry->getId();
		$this->cache->delete($cacheId);

		$cacheId = __CLASS__ . '::entryByIdAsHtml' . '\\' . $entry->getId();
		$this->cache->delete($cacheId);

		$cacheId = __CLASS__ . '::indexJson' . '\\' . $entry->getEntry();
		$this->cache->delete($cacheId);

		$cacheId = __CLASS__ . '::indexHtml' . '\\' . $entry->getEntry();
		$this->cache->delete($cacheId);
	}
}

?>