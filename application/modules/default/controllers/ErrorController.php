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

use kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException;
use kateglo\application\controllers\exceptions\HTTPNotAcceptableException;
use kateglo\application\controllers\exceptions\HTTPNotFoundException;
use kateglo\application\controllers\exceptions\HTTPUnsupportedMediaTypeException;
use kateglo\application\controllers\exceptions\HTTPBadRequestException;
use kateglo\application\models\front\Search;
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
	 * @param Zend_Log $log
	 *
	 * @Inject
	 */
	public function setEntry(\Zend_Log $log) {
		$this->log = $log;
	}

	/**
	 * @return void
	 */
	public function errorAction() {
		$errors = $this->_getParam('error_handler');
		switch ($errors->type) {
			case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
			case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :

				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
				break;
			default :
				// application error
				$this->otherException($errors->exception);
				break;
		}
		//catch anything in log files
		$this->log->log($errors->exception, \Zend_Log::ERR);
		$this->view->exception = $errors->exception;
		$this->view->request = $errors->request;

		$this->view->appPath = APPLICATION_PATH.'/modules';
		$this->view->search = new Search();
		$this->view->search->setFormAction('/kamus');
	}

	private function otherException(Exception $exception) {
		if ($exception instanceof HTTPMethodNotAllowedException) {
			$this->getResponse()->setHttpResponseCode(405);
			$this->view->message = 'Method not allowed.';
		} elseif ($exception instanceof HTTPNotAcceptableException) {
			$this->getResponse()->setHttpResponseCode(406);
			$this->view->message = 'Not Acceptable.';
		} elseif ($exception instanceof HTTPNotFoundException) {
			$this->getResponse()->setHttpResponseCode(404);
			$this->view->message = 'Not Found.';
		} elseif ($exception instanceof HTTPBadRequestException) {
			$this->getResponse()->setHttpResponseCode(400);
			$this->view->message = 'Bad Request.';
		} elseif ($exception instanceof HTTPUnsupportedMediaTypeException) {
			$this->getResponse()->setHttpResponseCode(415);
			$this->view->message = 'Unsupported Media Type.';
		} else {
			$this->getResponse()->setHttpResponseCode(500);
			$this->view->message = 'Application error';
		}
	}

}

?>