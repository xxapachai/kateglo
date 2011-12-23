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
use kateglo\application\models;

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
class CPanel_CariController extends Zend_Controller_Action_Stubbles
{

    /**
     *
     * Enter description here ...
     * @var \kateglo\application\services\interfaces\Search;
     */
    private $search;

    /**
     * Enter description here ...
     * @var \kateglo\application\services\interfaces\Pagination;
     */
    private $pagination;

    /**
     *
     * Enter description here ...
     * @param \kateglo\application\faces\interfaces\Search $entry
     *
     * @Inject
     */
    public function setSearch(interfaces\Search $search)
    {
        $this->search = $search;
    }

    /**
     *
     * Enter description here ...
     * @param \kateglo\application\services\interfaces\Pagination $pagination
     *
     * @Inject
     */
    public function setPagination(interfaces\Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return void
     * @Get
     * @Path('/entri')
     * @Produces('application/json')
     */
    public function entri()
    {
        $search = new models\front\Search();
        $pagination = new models\front\Pagination();
        $searchText = urldecode($this->getRequest()->getParam($search->getFieldName()));
        $pagination->setLimit((is_numeric($this->_request->getParam('limit'))
            ? intval($this->_request->getParam('limit'))
            : 10));
        $pagination->setOffset((is_numeric($this->_request->getParam('start'))
            ? intval($this->_request->getParam('start'))
            : 0));
        try {
            $cacheId = __METHOD__ . '\\' . $searchText . '\\' . $pagination->getOffset() . '\\' . $pagination->getLimit();
            $hits = $this->search->entry($searchText, $pagination);
            $result = array();
            $documents = $hits->getDocuments();
            /** @var $document \kateglo\application\models\solr\Document */
            foreach ($documents as $document) {
                $array = array();
                $array['id'] = $document->getId();
                $array['entry'] = $document->getEntry();
                $result[] = $array;
            }
            $this->content = array('total' => $hits->getCount(), 'hits' => $result);
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
     * @Path('/sumber')
     * @Produces('application/json')
     */
    public function sumber()
    {
            $search = new models\front\Search();
            $pagination = new models\front\Pagination();
            $searchText = urldecode($this->getRequest()->getParam($search->getFieldName()));
            $pagination->setLimit((is_numeric($this->_request->getParam('limit'))
                ? intval($this->_request->getParam('limit'))
                : 10));
            $pagination->setOffset((is_numeric($this->_request->getParam('start'))
                ? intval($this->_request->getParam('start'))
                : 0));
            try {
                $cacheId = __METHOD__ . '\\' . $searchText . '\\' . $pagination->getOffset() . '\\' . $pagination->getLimit();
                $sources = $this->search->source($searchText, $pagination);
                $result = array();
                /** @var $source \kateglo\application\models\Source */
                foreach ($sources as $source) {
                    $array = array();
                    $array['id'] = $source->getId();
                    $array['entry'] = $source->getClean();
                    $result[] = $array;
                }
                $this->content = array('total' => count($sources), 'hits' => $result);
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
     * @Path('/padanan')
     * @Produces('application/json')
     */
    public function padanan()
    {
        $search = new models\front\Search();
        $pagination = new models\front\Pagination();
        $searchText = urldecode($this->getRequest()->getParam($search->getFieldName()));
        $pagination->setLimit((is_numeric($this->_request->getParam('limit'))
            ? intval($this->_request->getParam('limit'))
            : 10));
        $pagination->setOffset((is_numeric($this->_request->getParam('start'))
            ? intval($this->_request->getParam('start'))
            : 0));
        try {
            $cacheId = __METHOD__ . '\\' . $searchText . '\\' . $pagination->getOffset() . '\\' . $pagination->getLimit();
            $foreigns = $this->search->foreign($searchText, $pagination);
            $result = array();
            /** @var $foreign \kateglo\application\models\Foreign */
            foreach ($foreigns as $foreign) {
                $array = array();
                $array['id'] = $foreign->getId();
                $array['entry'] = $foreign->getForeign();
                $result[] = $array;
            }
            $this->content = array('total' => count($foreigns), 'hits' => $result);
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
     * @Path('/arti')
     * @Produces('application/json')
     */
    public function artiJson()
    {
        $searchText = $this->getRequest()->getParam($this->view->search->getFieldName());
        try {
            $cacheId = __METHOD__ . '\\' . $searchText;
            if (!$this->evaluatePreCondition($cacheId)) {
                /*@var $hits array */
                $hits = $this->cpanel->searchMeaningAsJSON($searchText, $this->offset, $this->limit);
                $this->content = $hits;
            }
            $this->responseBuilder($cacheId);
        } catch (Apache_Solr_Exception $e) {
            $this->getResponse()->setHttpResponseCode(400);
            $this->content = array('error' => 'query error');
        }
        $this->_helper->json($this->content);
    }
}

?>