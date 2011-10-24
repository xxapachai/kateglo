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
use kateglo\application\faces\interfaces\Search;
use kateglo\application\services\interfaces\Entry;
use kateglo\application\daos\User;
use kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException;
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
class IndexController extends Zend_Controller_Action_Stubbles
{

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
    public function setEntry(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\faces\interfaces\Search $entry
     *
     * @Inject
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;
    }

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        parent::init();
        $this->view->search = $this->search;
    }

    /**
     * @return void
     * @Get
     * @Path('/')
     * @Produces('text/html')
     */
    public function indexHtml()
    {
        if ($this->getRequest()->getParam('query') == '') {
            $this->_helper->viewRenderer->setNoRender();
            $cacheId = __METHOD__;

            if (!$this->evaluatePreCondition($cacheId)) {
                try {
                    $this->view->formAction = '/kamus';
                    $entry = $this->entry->searchEntryAsJSON('', 0, 0);
                    $thesaurus = $this->entry->searchThesaurusAsJSON('', 0, 0);
                    $equivalent = $this->entry->searchEquivalentAsJSON('', 0, 0);
                    $proverb = $this->entry->searchProverbAsJSON('', 0, 0);
                    $acronym = $this->entry->searchAcronymAsJSON('', 0, 0);
                    $this->view->amount =  array('entry' => $entry->response->numFound, 'thesaurus' => $thesaurus->response->numFound, 'equivalent' => $equivalent->response->numFound, 'proverb' => $proverb->response->numFound, 'acronym' => $acronym->response->numFound);
                    $this->content = $this->_helper->viewRenderer->view->render($this->_helper->viewRenderer->getViewScript());
                } catch (Apache_Solr_Exception $e) {
                    $this->content = $this->_helper->viewRenderer->view->render('error/solr.html');
                }
            }

            $this->responseBuilder($cacheId);
            $this->getResponse()->appendBody($this->content);
        } else {
            $this->getResponse()->setHttpResponseCode(303);
            $this->getResponse()->setHeader('location', '/kamus?query=' . $this->getRequest()->getParam('query'));
        }
    }

}

?>