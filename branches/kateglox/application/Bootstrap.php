<?php
/*
 *  $Id: Bootstrap.php 293 2011-03-15 10:53:09Z arthur.purnama $
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
 * @since $LastChangedDate: 2011-03-15 11:53:09 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 293 $
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
		/*@var $front Zend_Controller_Front */
		$front = $this->getResource ( 'FrontController' );
		
		$dispatcher = new Zend_Controller_Dispatcher_Stubbles ();
		$dispatcher->setControllerDirectory ( Injector::getInstance ( 'Zend_Config' )->resources->frontController->controllerDirectory );
		$front->setDispatcher ( $dispatcher );
		
		$router = $front->getRouter ();
		$route = new Zend_Controller_Router_Route ( 'entry/:text', array ('controller' => 'entry', 'text' => '' ) );
		
		$router->addRoute ( 'kateglo', $route );
		$default = $front->getDefaultModule ();
		if (null === $front->getControllerDirectory ( $default )) {
			throw new Zend_Application_Bootstrap_Exception ( 'No default controller directory registered with front controller' );
		}
		
		$front->setParam ( 'bootstrap', $this );
		
		$talActionHelper = new Zend_Controller_Action_Helper_ViewRenderer ();
		$talActionHelper->setView ( new Zend_View_PhpTal () );
		$talActionHelper->setViewSuffix ( 'html' );
		Zend_Controller_Action_HelperBroker::getStack ()->offsetSet ( - 80, $talActionHelper );
		
		$front->dispatch ();
	}

}

?>