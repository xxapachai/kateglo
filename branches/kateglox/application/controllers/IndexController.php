<?php
/*
 *  $Id: IndexController.php 268 2011-01-16 14:39:03Z arthur.purnama $
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
use kateglo\application\faces\interfaces\Search;
use kateglo\application\services\interfaces\Entry;
use kateglo\application\daos\User;
use kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException;
use Doctrine\Common\Cache\Cache;
/**
 *
 *
 * @package kateglo\application\controllers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-01-16 15:39:03 +0100 (So, 16 Jan 2011) $
 * @version $LastChangedRevision: 268 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class IndexController extends Zend_Controller_Action_Stubbles {

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
     * @throws Exception
     * @return void
     */
    public function indexAction() {
        if ($this->requestJson()) {
            $this->renderJson();
        } else {
            if ($this->getRequest()->isGet()) {
                $this->_helper->viewRenderer->setNoRender();
                if ($this->configs->cache->entry) {
                    if ($this->cache->contains(__CLASS__)) {
                        $cache = unserialize($this->cache->fetch(__CLASS__));
                        $content = $cache['content'];
                        $eTag = $cache['eTag'];
                    } else {
                        $content = $this->renderHtml();
                        $eTag = md5(__CLASS__ . $content);
                        $this->cache->save(__CLASS__, serialize(array('content' => $content, 'eTag' => $eTag)), 0);
                    }
                } else {
                    $content = $this->renderHtml();
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

    }

    /**
     * @param  string $text
     * @return string
     */
    private function renderHtml() {
        $this->view->appPath = APPLICATION_PATH;
        $this->view->search = $this->search;
        $this->view->formAction = '/kamus';
        return $this->_helper->viewRenderer->view->render($this->_helper->viewRenderer->getViewScript());
    }

    /**
     * @param  string $text
     * @return string
     */
    private function renderJson() {
        if ($this->getRequest()->isGet()) {
            try {
                $this->_helper->json(array('amount' => $this->entry->getTotalCount(), 'entry' => $this->entry->randomEntry(), 'misspelled' => $this->entry->randomMisspelled()));
            } catch (EntryException $e) {
                $this->getResponse()->setHttpResponseCode(500);
                $this->_helper->json(array('error' => $e->getMessage()));
            }
        } else {
            $this->getResponse()->setHttpResponseCode(405);
            $this->_helper->json(array('error' => 'Method not allowed'));
        }
    }
}

?>