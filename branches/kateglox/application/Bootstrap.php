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
require_once 'Zend/Controller/Dispatcher/Stubbles.php';

require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';

use kateglo\application\utilities\Injector;
/**
 *
 *
 * @package kateglo\application
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	 * Run the application
	 *
	 * Checks to see that we have a default controller directory. If not, an
	 * exception is thrown.
	 *
	 * If so, it registers the bootstrap with the 'bootstrap' parameter of
	 * the front controller, and dispatches the front controller.
	 *
	 * @return void
	 * @throws Zend_Application_Bootstrap_Exception
	 */
	public function run() {
		/** @var $front \Zend_Controller_Front */
		$front = $this->getResource('FrontController');
        /** @var  $dispatcher \Zend_Controller_Dispatcher_Stubbles */
	    $dispatcher = Injector::getInstance('Zend_Controller_Dispatcher_Interface');
		//$dispatcher->setControllerDirectory(Injector::getInstance('Zend_Config')->resources->frontController->controllerDirectory, 'default');
		$front->setDispatcher($dispatcher);
		$front->addModuleDirectory(Injector::getInstance('Zend_Config')->resources->frontController->moduleDirectory);
		$default = $front->getDefaultModule();
		if (null === $front->getControllerDirectory($default)) {
			throw new Zend_Application_Bootstrap_Exception ('No default controller directory registered with front controller');
		}

		$front->setParam('bootstrap', $this);

		$talActionHelper = new Zend_Controller_Action_Helper_ViewRenderer ();
		$talActionHelper->setView(new Zend_View_PhpTal ());
		$talActionHelper->setViewSuffix('html');
		Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-80, $talActionHelper);

		$response = $front->dispatch();
        if ($front->returnResponse()) {
            return $response;
        }
	}

}

?>