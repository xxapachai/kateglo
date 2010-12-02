<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Dispatcher
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Standard.php 22038 2010-04-28 18:54:22Z matthew $
 */

/** Zend_Controller_Dispatcher_Abstract */
require_once 'Zend/Controller/Dispatcher/Standard.php';

use kateglo\application\utilities;

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Dispatcher
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Controller_Dispatcher_Stubbles extends Zend_Controller_Dispatcher_Standard {
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
		$this->setResponse ( $response );
		
		/**
		 * Get controller class
		 */
		if (! $this->isDispatchable ( $request )) {
			$controller = $request->getControllerName ();
			if (! $this->getParam ( 'useDefaultControllerAlways' ) && ! empty ( $controller )) {
				require_once 'Zend/Controller/Dispatcher/Exception.php';
				throw new Zend_Controller_Dispatcher_Exception ( 'Invalid controller specified (' . $request->getControllerName () . ')' );
			}
			
			$className = $this->getDefaultControllerClass ( $request );
		} else {
			$className = $this->getControllerClass ( $request );
			if (! $className) {
				$className = $this->getDefaultControllerClass ( $request );
			}
		}
		
		/**
		 * Load the controller class file
		 */
		$className = $this->loadClass ( $className );
		
		/**
		 * Instantiate controller with request, response, and invocation
		 * arguments; throw exception if it's not an action controller
		 */
		utilities\Injector::get ()->bind ( 'Zend_Controller_Action_Interface' )->to ( $className );
		/*@var $controller Zend_Controller_Action_Stubbles */
		$controller = utilities\Injector::getInstance ( $className );
		$controller->setRequest ( $request )->setResponse ( $response )->setInvokeArgs ( $this->getParams () );
		$controller->setHelper ( new Zend_Controller_Action_HelperBroker ( $controller ) );		
		$controller->init ();
		if (! ($controller instanceof Zend_Controller_Action_Interface) && ! ($controller instanceof Zend_Controller_Action)) {
			require_once 'Zend/Controller/Dispatcher/Exception.php';
			throw new Zend_Controller_Dispatcher_Exception ( 'Controller "' . $className . '" is not an instance of Zend_Controller_Action_Interface' );
		}

		/**
		 * Retrieve the action name
		 */
		$action = $this->getActionMethod ( $request );
		
		/**
		 * Dispatch the method call
		 */
		$request->setDispatched ( true );
		
		// by default, buffer output
		$disableOb = $this->getParam ( 'disableOutputBuffering' );
		$obLevel = ob_get_level ();
		if (empty ( $disableOb )) {
			ob_start ();
		}
		
		try {
			$controller->dispatch ( $action );
		} catch ( Exception $e ) {
			// Clean output buffer on error
			$curObLevel = ob_get_level ();
			if ($curObLevel > $obLevel) {
				do {
					ob_get_clean ();
					$curObLevel = ob_get_level ();
				} while ( $curObLevel > $obLevel );
			}
			throw $e;
		}
		
		if (empty ( $disableOb )) {
			$content = ob_get_clean ();
			$response->appendBody ( $content );
		}
		
		// Destroy the page controller instance and reflection objects
		$controller = null;
	}
}
?>