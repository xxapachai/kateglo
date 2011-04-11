<?php
/*
 *  $Id: Stubbles.php 266 2010-12-16 21:01:27Z arthur.purnama $
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

/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Action.php';

/**
 *  
 * 
 * @package kateglo\library\Zend\Controller\Action
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2010-12-16 22:01:27 +0100 (Do, 16 Dez 2010) $
 * @version $LastChangedRevision: 266 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
abstract class Zend_Controller_Action_Stubbles extends Zend_Controller_Action {
	
	/**
	 * Class constructor
	 *
	 * The request and response objects should be registered with the
	 * controller, as should be any additional optional arguments; these will be
	 * available via {@link getRequest()}, {@link getResponse()}, and
	 * {@link getInvokeArgs()}, respectively.
	 *
	 * When overriding the constructor, please consider this usage as a best
	 * practice and ensure that each is registered appropriately; the easiest
	 * way to do so is to simply call parent::__construct($request, $response,
	 * $invokeArgs).
	 *
	 * After the request, response, and invokeArgs are set, the
	 * {@link $_helper helper broker} is initialized.
	 *
	 * Finally, {@link init()} is called as the final action of
	 * instantiation, and may be safely overridden to perform initialization
	 * tasks; as a general rule, override {@link init()} instead of the
	 * constructor to customize an action controller's instantiation.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @param Zend_Controller_Response_Abstract $response
	 * @param array $invokeArgs Any additional invocation arguments
	 * @return void
	 */
	public function __construct(Zend_Controller_Request_Abstract $request = null, Zend_Controller_Response_Abstract $response = null, array $invokeArgs = array()) {
	}
	
	/**
	 * Set invocation arguments
	 *
	 * @param array $args
	 * @return Zend_Controller_Action
	 */
	public function setInvokeArgs(array $args = array()) {
		$this->_invokeArgs = $args;
		return $this;
	}
	
	/**
	 * Set invocation helper
	 *
	 * @param Zend_Controller_Action_HelperBroker $helper
	 * @return Zend_Controller_Action
	 */
	public function setHelper(Zend_Controller_Action_HelperBroker $helper) {
		$this->_helper = $helper;
		return $this;
	}

}

?>