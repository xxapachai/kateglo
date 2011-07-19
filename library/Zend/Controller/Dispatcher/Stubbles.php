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

/** Zend_Controller_Dispatcher_Abstract */
require_once 'Zend/Controller/Dispatcher/Standard.php';

use kateglo\application\utilities\Injector;
use kateglo\application\utilities\interfaces\MimeParser;
use kateglo\application\utilities\REST;
/**
 *
 *
 * @package kateglo\library\Zend\Controller\Dispatcher
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Zend_Controller_Dispatcher_Stubbles extends Zend_Controller_Dispatcher_Standard {

	/**
	 * @var \kateglo\application\utilities\interfaces\MimeParser;
	 */
	private $mimeParser;

	/**
	 *
	 * @param \kateglo\application\utilities\interfaces\MimeParser $mimeParse
	 * @return void
	 *
	 * @Inject
	 */
	public function setMimeParser(MimeParser $mimeParser) {
		$this->mimeParser = $mimeParser;
	}

	/**
	 * Dispatch to a controller/action
	 *
	 * By default, if a controller is not dispatchable, dispatch() will throw
	 * an exception. If you wish to use the default controller instead, set the
	 * param 'useDefaultControllerAlways' via {@link setParam()}.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @param Zend_Controller_Response_Abstract $response
	 * @return void
	 * @throws Zend_Controller_Dispatcher_Exception
	 */
	public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response) {
		$this->setResponse($response);

		/**
		 * Get controller class
		 */
		if (!$this->isDispatchable($request)) {
			$controller = $request->getControllerName();
			if (!$this->getParam('useDefaultControllerAlways') && !empty ($controller)) {
				require_once 'Zend/Controller/Dispatcher/Exception.php';
				throw new Zend_Controller_Dispatcher_Exception ('Invalid controller specified (' . $request->getControllerName() . ')');
			}

			$className = $this->getDefaultControllerClass($request);
		} else {
			$className = $this->getControllerClass($request);
			if (!$className) {
				$className = $this->getDefaultControllerClass($request);
			}
		}

		/**
		 * Load the controller class file
		 */
		$className = $this->loadClass($className);

		/**
		 * Instantiate controller with request, response, and invocation
		 * arguments; throw exception if it's not an action controller
		 */
		Injector::get()->bind('Zend_Controller_Action_Interface')->to($className);
		/** @var $controller Zend_Controller_Action_Stubbles */
		$controller = Injector::getInstance($className);
		$classObject = new stubReflectionClass($className);

		$controller->setRequest($request)->setResponse($response)->setInvokeArgs($this->getParams());
		$controller->setHelper(new Zend_Controller_Action_HelperBroker ($controller));
		$controller->init();
		if (!($controller instanceof Zend_Controller_Action_Interface) && !($controller instanceof Zend_Controller_Action)) {
			require_once 'Zend/Controller/Dispatcher/Exception.php';
			throw new Zend_Controller_Dispatcher_Exception ('Controller "' . $className . '" is not an instance of Zend_Controller_Action_Interface');
		}


		/**
		 * Retrieve the action name
		 */
		$rest = new REST($classObject, $request);
		$action = $rest->getAction();

		/**
		 * Dispatch the method call
		 */
		$request->setDispatched(true);

		// by default, buffer output
		$disableOb = $this->getParam('disableOutputBuffering');
		$obLevel = ob_get_level();
		if (empty ($disableOb)) {
			ob_start();
		}

		try {
			if (!empty($action)) {
				$controller->dispatch($action);
			}
		} catch (Exception $e) {
			// Clean output buffer on error
			$curObLevel = ob_get_level();
			if ($curObLevel > $obLevel) {
				do {
					ob_get_clean();
					$curObLevel = ob_get_level();
				} while ($curObLevel > $obLevel);
			}
			throw $e;
		}

		if (empty ($disableOb)) {
			$content = ob_get_clean();
			$response->appendBody($content);
		}

		// Destroy the page controller instance and reflection objects
		$controller = null;
	}

}

?>