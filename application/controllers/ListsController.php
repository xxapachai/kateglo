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
use kateglo\application\helpers;
use kateglo\application\faces;
use kateglo\application\daos;
/**
 *
 *
 * @package kateglo\application\controllers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since  2009-10-14
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class ListsController extends Zend_Controller_Action
{

	public function indexAction(){
		/*@var $request Zend_Controller_Request_Http */
		$request = $this->getRequest();
		$searchFaces = new faces\Search();
		$this->view->search = $searchFaces;
		if($request->isPost()){
			$lists = utilities\Injector::getInstance(interfaces\Lists::INTERFACE_NAME);
			$amount = utilities\Injector::getInstance(interfaces\Amount::INTERFACE_NAME);
			$filters = array();
			if(array_key_exists('filter', $_POST)){
				if(is_array($_POST['filter'])){
					$filters = $_POST['filter'];
				}
			}
			echo "{\"totalCount\": \"".$amount->lemma()."\",\"data\":".json_encode($lists->listLemma($_POST['start'], $_POST['limit'], $filters, $_POST['sort'], $_POST['dir']))."}"; die();
		}else{
			if($request->isGet()){
				if(array_key_exists('lists', $_GET)){
					$lists = utilities\Injector::getInstance(interfaces\Lists::INTERFACE_NAME); 
					echo "{\"type\": ".json_encode($lists->listType()).", \"lexical\": ".json_encode($lists->listLexical())."}"; die();
				}
			}else{
				header('location: '.$request->getBaseUrl());
			}
		}
	}
}
?>