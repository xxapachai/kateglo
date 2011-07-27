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

/**
 * @see Zend_Controller_Front
 */
require_once 'Zend/Controller/Action.php';

use Doctrine\Common\Cache\Cache;
/**
 *
 *
 * @package kateglo\library\Zend\Controller\Action
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
abstract class Zend_Controller_Action_Stubbles extends Zend_Controller_Action {

	/**
	 *
	 * Enter description here ...
	 * @var \Doctrine\Common\Cache\Cache
	 */
	protected $cache;

	/**
	 *
	 * Enter description here ...
	 * @var \Zend_Config
	 */
	protected $configs;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $eTag;

	/**
	 *
	 * Enter description here ...
	 * @param \Zend_Config $configs
	 *
	 * @Inject
	 */
	public function setConfigs(\Zend_Config $configs) {
		$this->configs = $configs;
	}

	/**
	 * @return \Zend_Config
	 */
	public function getConfigs() {
		return $this->configs;
	}

	/**
	 *
	 * Enter description here ...
	 * @param Doctrine\Common\Cache\Cache $cache
	 *
	 * @Inject
	 */
	public function setCache(Cache $cache) {
		$this->cache = $cache;
	}

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
	 * @return void
	 */
	public function init() {
		$this->view->appPath = APPLICATION_PATH;
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

	/**
	 * Dispatch the requested action
	 *
	 * @param array $action Method name of action and arguments
	 * @return void
	 */
	public function dispatch($action) {
		// Notify helpers of action preDispatch state
		$this->_helper->notifyPreDispatch();
		/** @var \ReflectionMethod $actionMethod */
		$actionMethod = $action['action'];
		$this->preDispatch();
		if ($this->getRequest()->isDispatched()) {
			if (null === $this->_classMethods) {
				$this->_classMethods = get_class_methods($this);
			}

			// preDispatch() didn't change the action, so we can continue
			if ($this->getInvokeArg('useCaseSensitiveActions') || in_array($actionMethod->getName(), $this->_classMethods)) {
				if ($this->getInvokeArg('useCaseSensitiveActions')) {
					trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');
				}
				$actionMethod->invokeArgs($this, $action['args']);
			} else {
				$this->__call($actionMethod->getName(), $action['args']);
			}
			$this->postDispatch();
		}

		// whats actually important here is that this action controller is
		// shutting down, regardless of dispatching; notify the helpers of this
		// state
		$this->_helper->notifyPostDispatch();
	}

	/**
	 * @throws HTTPMethodNotAllowedException
	 * @return void
	 */
	protected function responseBuilder($cacheId) {
		if ($this->configs->cache->entry && !$this->cache->contains($cacheId)) {
			$this->eTag = md5($cacheId . serialize($this->content));
			$this->cache->save($cacheId, serialize(array('content' => $this->content, 'eTag' => $this->eTag)), 3600);
		}

		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $this->eTag) {
			$this->getResponse()->setHttpResponseCode(304);
		} else {
			$this->getResponse()->setHeader('Etag', $this->eTag);
		}
	}

	/**
	 * @param  string $cacheId
	 * @return bool
	 */
	protected function evaluatePreCondition($cacheId) {
		if ($this->configs->cache->entry) {
			if ($this->cache->contains($cacheId)) {
				$cache = unserialize($this->cache->fetch($cacheId));
				$this->content = $cache['content'];
				$this->eTag = $cache['eTag'];
				return true;
			}
		}
		return false;
	}

}

?>