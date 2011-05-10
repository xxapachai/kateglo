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
     * @Path('/')
     * @Produces('text/html')
     */
    public function indexHtml() {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __CLASS__ . '\\' . 'html'.date('d.m.Y');

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $this->view->formAction = '/kamus';
                $this->view->amount = $this->entry->getTotalCount();
                $this->view->entry = $this->wordOfTheDay();
                $this->content = $this->_helper->viewRenderer->view->render($this->_helper->viewRenderer->getViewScript());
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
     * @Path('/')
     * @Produces('application/json')
     */
    public function indexJson() {

        try {
            $this->content = array('entry' => $this->entry->randomEntry(), 'misspelled' => $this->entry->randomMisspelled());
        } catch (Apache_Solr_Exception $e) {
            $this->content = array('error' => 'query error');
        }

        $this->_helper->json($this->content);
    }

    private function wordOfTheDay() {
        $word[9] = 'kamus';
        $word[10] = 'tesaurus';
        $word[11] = 'padanan';
        $word[12] = 'terjemahan';
        $word[13] = 'peribahasa';
        $word[14] = 'singkatan';
        $word[15] = 'akronim';
        $word[16] = 'aplikasi';
        $word[17] = 'beranda';

        $entry = !empty($word[intval(date("j"))]) ? $word[intval(date("j"))] : 'kamus';
        $result = $this->entry->getEntry($entry);
        $return['entry'] = $result->getEntry();
        $return['definitions'] = array();
        foreach($result->getMeanings() as $meaning){
            foreach($meaning->getDefinitions() as $definition){
                $return['definitions'][] = $definition->getDefinition();
                if(count($return['definitions']) === 4) break;
            }
            if(count($return['definitions']) === 4) break;
        }
        return $return;
    }

}

?>