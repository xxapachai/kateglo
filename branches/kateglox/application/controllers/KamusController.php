<?php
/*
 *  $Id: KamusController.php 290 2011-03-13 22:48:04Z arthur.purnama $
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
use kateglo\application\services\interfaces\Pagination;
use kateglo\application\faces\interfaces\Search;
use kateglo\application\services\interfaces\Entry;
use kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException;
use Doctrine\Common\Cache\Cache;
/**
 *
 *
 *
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-13 23:48:04 +0100 (So, 13 Mrz 2011) $
 * @version $LastChangedRevision: 290 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class KamusController extends Zend_Controller_Action_Stubbles {

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
     * @var Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     *
     * Enter description here ...
     * @var Zend_Config
     */
    private $configs;

    /**
     *
     * Enter description here ...
     * @param Zend_Config $configs
     *
     * @Inject
     */
    public function setConfigs(\Zend_Config $configs) {
        $this->configs = $configs;
    }

    /**
     *
     * Enter description here ...
     * @param Doctrine\Common\Cache\Cache $cache
     *
     * @Inject
     */
    public function setCache(Cache $cache) {
        $this->cache = $cache;
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
     * @param kateglo\application\faces\interfaces\Search $entry
     *
     * @Inject
     */
    public function setSearch(Search $search) {
        $this->search = $search;
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\Pagination $pagination
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
        $this->limit = (is_numeric($this->_request->getParam('rows')) ? intval($this->_request->getParam('rows')) : 10);
        $this->offset = (is_numeric($this->_request->getParam('start')) ? intval($this->_request->getParam('start')) : 0);
        $this->view->appPath = APPLICATION_PATH;
        $this->view->formAction = '/kamus';
    }

    /**
     * @return void
     */
    public function indexAction() {
        $this->_helper->viewRenderer->setNoRender();
        $searchText = $this->getRequest()->getParam($this->view->search->getFieldName());
        $this->view->search->setFieldValue($searchText);
        try {
            if ($this->requestJson()) {
                $this->renderJson($searchText);
            } else {
                $this->renderHtml($searchText);
            }
        } catch (Apache_Solr_Exception $e) {
            $content = $this->_helper->viewRenderer->view->render('error/solr.html');
            $this->getResponse()->appendBody($content);
        }
    }

    /**
     * @throws HTTPMethodNotAllowedException
     * @return void
     */
    private function renderHtml($searchText) {
        if ($this->getRequest()->isGet()) {
            if ($this->configs->cache->entry) {
                if ($this->cache->contains(__CLASS__)) {
                    $cache = unserialize($this->cache->fetch(__CLASS__));
                    $content = $cache['content'];
                    $eTag = $cache['eTag'];
                } else {
                    $content = $this->html($searchText);
                    $eTag = md5(__CLASS__ . $content);
                    $this->cache->save(__CLASS__, serialize(array('content' => $content, 'eTag' => $eTag)), 0);
                }
            } else {
                $content = $this->html($searchText);
                $eTag = md5(__CLASS__ . $content);
            }

            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $eTag) {
                $this->getResponse()->setHttpResponseCode(304);
            } else {
                $this->getResponse()->setHeader('Etag', $eTag);
                $this->getResponse()->appendBody($content);
            }
        } else {
            //Block other request method
            throw new HTTPMethodNotAllowedException('Method not allowed');
        }
    }

    /**
     * @return string
     */
    private function html($searchText) {
        /*@var $hits kateglo\application\faces\Hit */
        $hits = $this->entry->searchEntry($searchText, $this->offset, $this->limit);
        $this->view->pagination = $this->pagination->create($hits->getCount(), $this->offset, $this->limit);
        $this->view->hits = $hits;
        return $this->_helper->viewRenderer->view->render($this->_helper->viewRenderer->getViewScript());
    }

    /**
     * @throws HTTPMethodNotAllowedException
     * @return void
     */
    private function renderJson($searchText) {
        if ($this->getRequest()->isGet()) {
            $hits = $this->entry->searchEntryAsArray($searchText, $this->offset, $this->limit);
            $pagination = $this->pagination->createAsArray($hits[Hit::COUNT], $this->offset, $this->limit);
            $this->_helper->json(array('hits' => $hits, 'pagination' => $pagination));
        } else {
            //Block other request method
            throw new HTTPMethodNotAllowedException('Method not allowed');
        }
    }
}

?>