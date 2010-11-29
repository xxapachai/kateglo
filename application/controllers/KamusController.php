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
use kateglo\application\utilities;
use kateglo\application\utilities\collections;
use kateglo\application\configs;
use kateglo\application\faces;
use kateglo\application\daos;
/**
 *
 *
 * @uses Exception
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-11-09
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class KamusController extends Zend_Controller_Action {
	
	public function indexAction() {
		$this->view->appPath = APPLICATION_PATH;
		$this->view->formAction = '/kamus';
		$this->search ();
	}
	
	public function jsonAction() {
		$this->search ();
		$this->view->json = json_encode ( $this->view->hits );
	}
	
	private function search() {
		/*@var $request Zend_Controller_Request_Http */
		$request = $this->getRequest ();
		$this->view->hits = new collections\ArrayCollection ();
		if ($request->isGet ()) {
			$search = new faces\Search ();
			$this->view->search = $search;
			$searchText = $request->getParam ( $search->getFieldName () );
			$search->setFieldValue ( $searchText );
			$limit = (is_numeric($request->getParam('rows')) ? intval($request->getParam('rows')) : 10);
			$offset = (is_numeric($request->getParam('start')) ? intval($request->getParam('start')) : 0);
			/*@var $lucene kateglo\application\services\Entry */
			$lucene = utilities\Injector::getInstance ( interfaces\Entry::INTERFACE_NAME );
			$this->view->hits = $lucene->searchEntry ( $searchText, $offset, $limit );
		}
	}
}
?>