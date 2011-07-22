<?php
namespace kateglo\application\utilities;
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
use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException;
use kateglo\application\controllers\exceptions\HTTPNotAcceptableException;
use kateglo\application\controllers\exceptions\HTTPUnsupportedMediaTypeException;
use kateglo\application\controllers\exceptions\HTTPNotFoundException;
/**
 *
 *
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class REST implements interfaces\REST {

	public static $CLASS_NAME = __CLASS__;

	/**
	 * @var \stubReflectionClass
	 */
	private $classObject;

	/**
	 * @var \Zend_Controller_Request_Http
	 */
	private $request;

	/**
	 * @var \Zend_Controller_Dispatcher_Stubbles
	 */
	private $dispatcher;

	/**
	 * @var \kateglo\application\utilities\interfaces\MimeParser;
	 */
	private $mimeParser;

	/**
	 * @var array
	 */
	private $requestPath;

	/**
	 * @var array
	 */
	private $actionPath;

	/**
	 * @param \stubReflectionClass $classObject
	 * @return void
	 */
	public function setClassObject(\stubReflectionClass $classObject) {
		$this->classObject = $classObject;
	}

	/**
	 * @return \stubReflectionClass
	 */
	public function getClassObject() {
		return $this->classObject;
	}

	/**
	 * @param \Zend_Controller_Request_Http $request
	 * @return void
	 */
	public function setRequest(\Zend_Controller_Request_Http $request) {
		$this->request = $request;
	}

	/**
	 * @return \Zend_Controller_Request_Http
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param \Zend_Controller_Dispatcher_Stubbles $dispatcher
	 * @return void
	 */
	public function setDispatcher(\Zend_Controller_Dispatcher_Stubbles $dispatcher) {
		$this->dispatcher = $dispatcher;
	}

	/**
	 * @return \Zend_Controller_Dispatcher_Stubbles
	 */
	public function getDispatcher() {
		return $this->dispatcher;
	}

	/**
	 *
	 * @param \kateglo\application\utilities\interfaces\MimeParser $mimeParse
	 * @return void
	 *
	 * @Inject
	 */
	public function setMimeParser(interfaces\MimeParser $mimeParser) {
		$this->mimeParser = $mimeParser;
	}


	/**
	 * @throws \Exception
	 * @return array|string
	 */
	public function getAction() {
		$actionMethod = $this->request->getActionName();
		$requestPath = array_map('urlencode', array_slice(explode('/', $this->request->getPathInfo()), 1));
		$serverMethod = strtoupper($this->request->getMethod());

		if ($actionMethod === 'error') {
			return array('action' => 'errorAction', 'args' => array());
		}

		if ($requestPath[0] == '' || $requestPath[0] == $this->request->getControllerName()) {
			$requestPath = array_slice($requestPath, 1);
		}

		if (count($requestPath) > 0 && trim($requestPath[max(array_keys($requestPath))]) == '') {
			array_pop($requestPath);
			if (count($requestPath) > 0 && trim($requestPath[max(array_keys($requestPath))]) == '') {
				throw new HTTPNotFoundException('Ambigous URI');
			}
		}

		$lastResource = count($requestPath) > 0 ? trim($requestPath[max(array_keys($requestPath))]) : '';
		if ($lastResource === '*' && $serverMethod === 'OPTIONS') {
			return array('action' => null, 'args' => array());
		} else {
			$actionUri = '/' . implode('/', array_map('urldecode', $requestPath));
			$actionPaths = $this->getActionPaths($actionUri);
			if ($serverMethod === 'OPTIONS') {
				$this->generateOptions($actionPaths);
				return array('action' => null, 'args' => array());
			} else {
				$actionMethods = $this->getActionMethods($actionPaths);
				if ($serverMethod === 'POST' || $serverMethod === 'PUT') {
					$actionConsumes = $this->getActionConsumes($actionMethods);
					$actions = $this->getActionProduces($actionConsumes);
				} else {
					$actions = $this->getActionProduces($actionMethods);
				}
				if ($serverMethod === 'POST' || $serverMethod === 'PUT') {
					$actions = $this->decideConsumesMedia($actions);
				}
				$action = $this->decideProducesMedia($actions);

				$args = $this->createArguments($action);
				return array('action' => $action->getName(), 'args' => $args);
			}
		}
	}

	/**
	 * @throws \Exception
	 * @param string $actionUri
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	private function getActionPaths($actionUri) {
		$actionUriArray = array_map('urlencode', array_slice(explode('/', $actionUri), 1));
		$this->requestPath = $actionUriArray;
		$actions = $this->classObject->getMethods();
		$actionPaths = array();
		$countActionUri = count($actionUriArray);
		/** @var \stubReflectionMethod $action */
		foreach ($actions as $action) {
			if ($action->hasAnnotation('Path')) {
				$actionPath = array_map('urlencode', array_slice(explode('/', $action->getAnnotation('Path')->getValue()), 1));
				if (count($actionPath) === $countActionUri) {
					$actionArray['action'] = $action;
					$actionArray['path'] = $actionPath;
					$actionPaths[] = $actionArray;
				}
			}
		}

		$getActions = array();
		for ($i = 0; $i < $countActionUri; $i++) {
			foreach ($actionPaths as $path) {
				if ($path['path'][$i] == $actionUriArray[$i]) {
					$getActions[] = $path;
				}
			}
			if (count($getActions) == 0) {
				foreach ($actionPaths as $path) {
					if (strpos($path['path'][$i], urlencode('{')) === 0) {
						$getActions[] = $path;
					}
				}
			}
			$actionPaths = $getActions;
			$getActions = array();
		}
		$getActions = array();
		foreach ($actionPaths as $path) {
			$getActions[] = $path['action'];
		}
		$actionPaths = $getActions;
		if (count($actionPaths) === 0) {
			throw new HTTPNotFoundException('paths not found ');
		}
		return new ArrayCollection($actionPaths);
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @return void
	 */
	private function generateOptions(ArrayCollection $actionPaths) {
		$methodArray = array('GET', 'POST', 'PUT', 'DELETE');
		$allowArray = array();
		foreach ($methodArray as $method) {
			/** @var \stubReflectionMethod $action */
			foreach ($actionPaths as $action) {
				if ($action->hasAnnotation(ucfirst(strtolower($method)))) {
					!in_array($method, $allowArray) ? $allowArray[] = $method : null;
				}
			}
		}
		if (in_array('GET', $allowArray)) {
			$allowArray[] = 'HEAD';
		}
		$allowArray[] = 'OPTIONS';
		$this->dispatcher->getResponse()->setHeader('Allow', implode(', ', $allowArray));
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	private function getActionMethods(ArrayCollection $actionPaths) {
		$actionMethods = new ArrayCollection();
		$serverMethod = strtoupper($this->request->getMethod());
		if (strtoupper($serverMethod) === 'HEAD') {
			$serverMethod = 'GET';
		}
		/** @var \stubReflectionMethod $action */
		foreach ($actionPaths as $action) {
			if ($action->hasAnnotation(ucfirst(strtolower($serverMethod)))) {
				$actionMethods->add($action);
			}
		}

		if ($actionMethods->count() === 0) {
			throw new HTTPMethodNotAllowedException();
		}
		return $actionMethods;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPUnsupportedMediaTypeException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	private function getActionConsumes(ArrayCollection $actionMethods) {
		$actionConsumes = new ArrayCollection();
		/** @var \stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			if ($action->hasAnnotation('Consumes')) {
				$actionConsumes->add($action);
			}
		}

		if ($actionConsumes->count() === 0) {
			throw new HTTPUnsupportedMediaTypeException();
		}
		return $actionConsumes;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	private function getActionProduces(ArrayCollection $actionMethods) {
		$actionProduces = new ArrayCollection();
		/** @var \stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			if ($action->hasAnnotation('Produces')) {
				$actionProduces->add($action);
			}
		}

		if ($actionProduces->count() === 0) {
			throw new HTTPNotAcceptableException();
		}
		return $actionProduces;
	}

	/**
	 * @throws Exception|kateglo\application\controllers\exceptions\HTTPUnsupportedMediaTypeException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return string
	 */
	private function decideConsumesMedia(ArrayCollection $actionMethods) {
		$supportedMediaAsArray = array();
		$mediaActionSet = array();

		/** @var \stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			$getRawConsumes = $action->getAnnotation('Consumes')->getValue();
			if (!empty($getRawConsumes)) {
				$getConsumesAsArray = explode(',', $getRawConsumes);
				$supportedMediaAsArray = array_merge($supportedMediaAsArray, $getConsumesAsArray);
				foreach ($getConsumesAsArray as $consume) {
					if (!array_key_exists($consume, $mediaActionSet)) {
						$mediaActionSet[$consume][] = $action;
					} else {
						$mediaActionSet[$consume] = array();
						$mediaActionSet[$consume][] = $action;
					}
				}
			}
		}

		$requestMedia = $this->request->getServer('CONTENT_TYPE');
		if (!empty($requestMedia)) {
			$mediaDecision = $this->mimeParser->bestMatch($supportedMediaAsArray, $requestMedia);
			if (!empty($mediaDecision)) {
				$actionArray = $mediaActionSet[$mediaDecision];
				return new ArrayCollection($actionArray);
			} else {
				throw new HTTPUnsupportedMediaTypeException();
			}
		} else {
			throw new HTTPUnsupportedMediaTypeException();
		}

	}

	/**
	 * @throws Exception|kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return \stubReflectionMethod
	 */
	private function decideProducesMedia(ArrayCollection $actionMethods) {
		$supportedMediaAsArray = array();
		$mediaActionSet = array();

		/** @var \stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			$getRawProduces = $action->getAnnotation('Produces')->getValue();
			if (!empty($getRawProduces)) {
				$getProducesAsArray = explode(',', $getRawProduces);
				$supportedMediaAsArray = array_merge($supportedMediaAsArray, $getProducesAsArray);
				foreach ($getProducesAsArray as $produce) {
					if (!array_key_exists($produce, $mediaActionSet)) {
						$mediaActionSet[$produce] = $action;
					} else {
						throw new \Exception('media already served');
					}
				}
			}
		}

		$requestMedia = $this->request->getServer('HTTP_ACCEPT');
		if (!empty($requestMedia)) {
			$mediaDecision = $this->mimeParser->bestMatch($supportedMediaAsArray, $requestMedia);
			if (!empty($mediaDecision)) {
				$action = $mediaActionSet[$mediaDecision];
				return $action;
			} else {
				throw new HTTPNotAcceptableException();
			}
		} else {
			throw new HTTPNotAcceptableException();
		}

	}

	/**
	 * @param \stubReflectionMethod $method
	 * @return array
	 */
	private function createArguments(\stubReflectionMethod $method) {
		$serverMethod = strtoupper($this->request->getMethod());
		$actionPath = array_map('urlencode', array_slice(explode('/', $method->getAnnotation('Path')->getValue()), 1));
		$args = array();

		$methodParameters = $method->getParameters();
		/** @var \stubReflectionParameter $parameter */
		foreach ($methodParameters as $parameter) {
			$args[$parameter->getName()] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;

			if ($serverMethod === 'POST' || $serverMethod === 'PUT') {
				if ($parameter->hasAnnotation('ConsumeParam')) {
					$args[$parameter->getName()] = $this->request->getRawBody();
					continue;
				}
			}

			if ($parameter->hasAnnotation('PathParam')) {

				for ($i = 0; $i < count($actionPath); $i++) {
					if (strpos($actionPath[$i], urlencode('{')) === 0) {
						$pathName = preg_replace('/[{}]/', '', urldecode($actionPath[$i]));
						if ($pathName === $parameter->getAnnotation('PathParam')->getValue()) {
							$args[$parameter->getName()] = urldecode($this->requestPath[$i]);
						}
					}
				}
			}
		}

		return $args;
	}

}

?>