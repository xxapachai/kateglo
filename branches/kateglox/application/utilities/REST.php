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
	 * @var stubReflectionClass
	 */
	private $classObject;

	/**
	 * @var Zend_Controller_Request_Http
	 */
	private $request;

	/**
	 * @param stubReflectionClass $classObject
	 * @param Zend_Controller_Request_Http $request
	 * @return string
	 */
	public function __construct(stubReflectionClass $classObject, Zend_Controller_Request_Http $request) {
		$this->request = $request;
		$this->classObject = $classObject;
	}

	public function getAction() {
		$actionMethod = $this->getRawActionMethod($request);
		$requestPath = array_slice(explode('/', $this->request->getPathInfo()), 2);
		$serverMethod = strtoupper($request->getMethod());

		if ($actionMethod === 'error') {
			return 'errorAction';
		}
		if ($actionMethod === '*' && $serverMethod === 'OPTIONS') {
			return;
		} else {
			$actionMethod = '/' . $actionMethod;
			$actionPaths = $this->getActionPaths($classObject, $actionMethod);
			if ($serverMethod === 'OPTIONS') {
				$this->generateOptions($actionPaths, $request);
				return;
			} else {
				$actionMethods = $this->getActionMethods($actionPaths, $request);
				$actions = $this->getActionProduces($actionMethods);
				if ($serverMethod === 'POST' || $serverMethod === 'PUT') {
					$actions = $this->getActionConsumes($actions);
				}
				return $this->decideMedia($actions, $request);
			}
		}
	}

	/**
	 * @throws Exception
	 * @param stubReflectionClass $classObject
	 * @param  $actionMethod
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionPaths(stubReflectionClass $classObject, $actionMethod) {
		$actions = $classObject->getMethods();
		$actionPaths = new Doctrine\Common\Collections\ArrayCollection();
		/** @var stubReflectionMethod $action */
		foreach ($actions as $action) {
			if ($action->hasAnnotation('Path')) {
				if ($action->getAnnotation('Path')->getValue() == $actionMethod) {
					$actionPaths->add($action);
				}
			}
		}

		if ($actionPaths->count() === 0) {
			throw new Exception('paths not found ');
		}
		return $actionPaths;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @param Zend_Controller_Request_Http $request
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionMethods(Doctrine\Common\Collections\ArrayCollection $actionPaths, Zend_Controller_Request_Http $request) {
		$actionMethods = new Doctrine\Common\Collections\ArrayCollection();
		$serverMethod = $request->getMethod();
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
			throw new kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException();
		}
		return $actionMethods;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionConsumes(Doctrine\Common\Collections\ArrayCollection $actionMethods) {
		$actionProduces = new Doctrine\Common\Collections\ArrayCollection();
		/** @var stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			if ($action->hasAnnotation('Consumes')) {
				$actionProduces->add($action);
			}
		}

		if ($actionProduces->count() === 0) {
			throw new kateglo\application\controllers\exceptions\HTTPNotAcceptableException();
		}
		return $actionProduces;
	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionMethods
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	protected function getActionProduces(Doctrine\Common\Collections\ArrayCollection $actionMethods) {
		$actionProduces = new Doctrine\Common\Collections\ArrayCollection();
		/** @var stubReflectionMethod $action */
		foreach ($actionMethods as $action) {
			if ($action->hasAnnotation('Produces')) {
				$actionProduces->add($action);
			}
		}

		if ($actionProduces->count() === 0) {
			throw new kateglo\application\controllers\exceptions\HTTPNotAcceptableException();
		}
		return $actionProduces;
	}

	/**
	 * @throws Exception|kateglo\application\controllers\exceptions\HTTPNotAcceptableException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionProduces
	 * @param Zend_Controller_Request_Http $request
	 * @return string
	 */
	protected function decideMedia(Doctrine\Common\Collections\ArrayCollection $actionProduces, Zend_Controller_Request_Http $request) {
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
		$requestMedia = $request->getServer('HTTP_ACCEPT');
		if (!empty($requestMedia)) {
			$mediaDecision = $this->mimeParser->bestMatch($supportedMediaAsArray, $requestMedia);
			if (!empty($mediaDecision)) {
				$action = $mediaActionSet[$mediaDecision];
				return $action->getName();
			} else {
				throw new kateglo\application\controllers\exceptions\HTTPNotAcceptableException();
			}
		} else {
			throw new kateglo\application\controllers\exceptions\HTTPNotAcceptableException();
		}

	}

	/**
	 * @throws kateglo\application\controllers\exceptions\HTTPMethodNotAllowedException
	 * @param Doctrine\Common\Collections\ArrayCollection $actionPaths
	 * @param Zend_Controller_Request_Http $request
	 * @return void
	 */
	protected function generateOptions(Doctrine\Common\Collections\ArrayCollection $actionPaths, Zend_Controller_Request_Http $request) {
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