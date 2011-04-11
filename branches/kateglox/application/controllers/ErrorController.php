<?php
/*
 *  $Id: ErrorController.php 293 2011-03-15 10:53:09Z arthur.purnama $
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
/**
 * 
 * 
 * @package kateglo\application\controllers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-15 11:53:09 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 293 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class ErrorController extends Zend_Controller_Action_Stubbles {
	/**
	 * 
	 * Enter description here ...
	 * @var Zend_Log;
	 */
	private $log;
	
	/**
	 * 
	 * Enter description here ...
	 * @var kateglo\application\faces\interfaces\Search;
	 */
	private $search;
	
	/**
	 * 
	 * Enter description here ...
	 * @param Zend_Log $log
	 * 
	 * @Inject
	 */
	public function setEntry(\Zend_Log $log) {
		$this->log = $log;
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
	
	public function errorAction() {
		$errors = $this->_getParam ( 'error_handler' );
		
		switch ($errors->type) {
			case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
			case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
				
				// 404 error -- controller or action not found
				$this->getResponse ()->setHttpResponseCode ( 404 );
				$this->view->message = 'Page not found';
				break;
			default :
				// application error 
				$this->getResponse ()->setHttpResponseCode ( 500 );
				$this->view->message = 'Application error';
				break;
		}
		//catch anything in log files
		$this->log->log ( $errors->exception, \Zend_Log::ERR );
		$this->view->exception = $errors->exception;
		$this->view->request = $errors->request;
		
		$this->view->appPath = APPLICATION_PATH;
		$this->view->search = $this->search;
		$this->view->formAction = '/kamus';
	}

}
?>