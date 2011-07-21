<?php
namespace kateglo\application\utilities\interfaces;
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
use kateglo\application\utilities\interfaces\MimeParser;
/**
 *
 *
 * @package kateglo\application\utilities\interfaces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
interface REST {

	const INTERFACE_NAME = __CLASS__;

	/**
	 * @param \stubReflectionClass $classObject
	 * @return void
	 */
	function setClassObject(\stubReflectionClass $classObject);

	/**
	 * @return \stubReflectionClass
	 */
	function getClassObject();

	/**
	 * @param \Zend_Controller_Request_Http $request
	 * @return void
	 */
	function setRequest(\Zend_Controller_Request_Http $request);

	/**
	 * @return \Zend_Controller_Request_Http
	 */
	function getRequest();

	/**
	 * @param \Zend_Controller_Dispatcher_Stubbles $dispatcher
	 * @return void
	 */
	function setDispatcher(\Zend_Controller_Dispatcher_Stubbles $dispatcher) ;

	/**
	 * @return \Zend_Controller_Dispatcher_Stubbles
	 */
	function getDispatcher() ;
	/**
	 *
	 * @param \kateglo\application\utilities\interfaces\MimeParser $mimeParse
	 * @return void
	 */
	function setMimeParser(MimeParser $mimeParser);


	/**
	 * @throws \Exception
	 * @return array|string
	 */
	function getAction();

}

?>