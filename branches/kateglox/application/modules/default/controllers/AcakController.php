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
use kateglo\application\services\interfaces\Search;

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
class AcakController extends Zend_Controller_Action_Stubbles
{

    /**
     *
     * Enter description here ...
     * @var \kateglo\application\services\interfaces\Search;
     */
    private $search;

    /**
     *
     * Enter description here ...
     * @param \kateglo\application\services\interfaces\Search $search
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
    }

    /**
     * @return void
     * @Get
     * @Path('/entri')
     * @Produces('text/html, application/json')
     */
    public function entryHtml()
    {
        $this->_helper->viewRenderer->setNoRender();
        $hits = $this->search->randomEntry(1);
        /** @var $doc \kateglo\application\models\solr\Document */
        $doc = $hits->getDocuments()->get(0);
        $this->getResponse()->setHttpResponseCode(303);
        $this->getResponse()->setHeader('Location', '/entri/' . urlencode($doc->getEntry()));
    }

    /**
     * @return void
     * @Get
     * @Path('/salaheja')
     * @Produces('text/html, application/json')
     */
    public function misspelledHtml()
    {
        $this->_helper->viewRenderer->setNoRender();
        $hits = $this->search->randomMisspelled(1);
        /** @var $doc \kateglo\application\models\solr\Document */
        $doc = $hits->getDocuments()->get(0);
        $this->getResponse()->setHttpResponseCode(303);
        $this->getResponse()->setHeader('Location', '/entri/' . urlencode($doc->getEntry()));
    }

}

?>