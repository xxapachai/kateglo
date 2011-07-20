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
	 * @param stubReflectionClass $classObject
	 * @param Zend_Controller_Request_Http $request
	 * @return string
	 */
	public function __construct(\stubReflectionClass $classObject, \Zend_Controller_Request_Http $request) {
		$this->request = $request;
		$this->classObject = $classObject;
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
				throw new \Exception('Ambigous URI');
			}
		}

		$lastResource = count($requestPath) > 0 ? trim($requestPath[max(array_keys($requestPath))]) : '';
		if ($lastResource === '*' && $serverMethod === 'OPTIONS') {
			return;
		} else {
			$actionUri = '/' . implode('/', $requestPath);
			$actionPaths = $this->getActionPaths($actionUri);
			if ($serverMethod === 'OPTIONS') {
				$this->generateOptions($actionPaths);
				return;
			} else {
				$actionMethods = $this->getActionMethods($actionPaths);
				$actions = $this->getActionProduces($actionMethods);
				if ($serverMethod === 'POST' || $serverMethod === 'PUT') {
					$actions = $this->getActionConsumes($actions);
				}
				return $this->decideMedia($actions);
			}
		}
	}

	/**
	 * @throws \Exception
	 * @param string $actionUri
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionPaths($actionUri) {
		$actionUriArray = array_map('urlencode', array_slice(explode('/', $actionUri), 1));
		
		$actions = $this->classObject->getMethods();
		$actionPaths = array();
		$countActionUri = count($actionUriArray);
		/** @var stubReflectionMethod $action */
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
		foreach($actionPaths as $path){
			$getActions[] = $path['action'];
		}
		$actionPaths = $getActions;
		if (count($actionPaths) === 0) {
			throw new Exception('paths not found ');
		}
		return new ArrayCollection($actionPaths);
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionMethods(ArrayCollection $actionPaths) {
		$actionMethods = new ArrayCollection();
		$serverMethod = $$this->request->getMethod();
		if (strtoupper($serverMethod) === 'HEAD') {
			$serverMethod = 'GET';
		}
		/** @var stubReflectionMethod $action */
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
	 * @throws kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionConsumes(ArrayCollection $actionMethods) {
		$actionProduces = new ArrayCollection();
		/** @var stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			if ($action->hasAnnotation('Consumes')) {
				$actionProduces->add($action);
			}
		}

		if ($actionProduces->count() === 0) {
			throw new HTTPNotAcceptableException();
		}
		return $actionProduces;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param \Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionProduces(ArrayCollection $actionMethods) {
		$actionProduces = new ArrayCollection();
		/** @var stubReflectionMethod $action */
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
	 * @throws Exception|kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionProduces
	 * @param Zend_Controller_Request_Http $request
	 * @return string
	 */
	protected function decideMedia(ArrayCollection $actionProduces) {
		$supportedMediaAsArray = array();
		$mediaActionSet = array();
		/** @var stubReflectionMethod $action */
		foreach ($actionProduces as $action) {
			$getRawProduces = $action->getAnnotation('Produces')->getValue();
			if (!empty($getRawProduces)) {
				$getProducesAsArray = explode(',', $getRawProduces);
				$supportedMediaAsArray = array_merge($supportedMediaAsArray, $getProducesAsArray);
				foreach ($getProducesAsArray as $produce) {
					if (!array_key_exists($produce, $mediaActionSet)) {
						$mediaActionSet[$produce] = $action;
					} else {
						throw new Exception('media already served');
					}
				}
			}
		}
		$requestMedia = $this->request->getServer('HTTP_ACCEPT');
		if (!empty($requestMedia)) {
			$mediaDecision = $this->mimeParser->bestMatch($supportedMediaAsArray, $requestMedia);
			if (!empty($mediaDecision)) {
				$action = $mediaActionSet[$mediaDecision];
				return $action->getName();
			} else {
				throw new HTTPNotAcceptableException();
			}
		} else {
			throw new HTTPNotAcceptableException();
		}

	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @param Zend_Controller_Request_Http $request
	 * @return void
	 */
	protected function generateOptions(ArrayCollection $actionPaths) {
		$methodArray = array('GET', 'POST', 'PUT', 'DELETE');
		$allowArray = array();
		foreach ($methodArray as $method) {
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
		$this->getResponse()->setHeader('Allow', implode(', ', $allowArray));
	}

}

?>