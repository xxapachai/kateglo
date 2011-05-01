<?php
namespace kateglo\application\helpers;
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
 * and is licensed under the GPL2. For more information, see
 * <http://code.google.com/p/kateglo/>.
 */
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\utilities\DataAccess;
use kateglo\application\daos\User;
/**
 *
 *
 * @package kateglo\application\helpers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class AuthenticationAdapter implements \Zend_Auth_Adapter_Interface {

	/**
	 * @var string
	 */
	private $username;

	/**
	 *
	 * @var string
	 */
	private $password;

	/**
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username, $password){
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 *
	 * @param string $username
	 */
	public function setUsername($username){
		$this->username = $username;
	}

	/**
	 *
	 * @return string
	 */
	public function getUsername(){
		return $this->username;
	}

	/**
	 *
	 * @param string $password
	 */
	public function setPassword($password){
		$this->password = $password;
	}

	/**
	 *
	 * @return string
	 */
	public function getPassword(){
		return $this->password;
	}

	/**
	 * Authenticate the given username and password.
	 * if the user Authenticated, then set the last login date to current.
	 *
	 * @return \Zend_Auth_Result
	 */
	public function authenticate(){
		$result = null;
		
		try{
			/*@var $userObj kateglo\application\models\User */
			$userObj = User::getByUsername($this->username);
			if($userObj->getPassword() == md5($this->password)){
				$userObj->setLastLogin(new \DateTime());
				//getEntityManager()->persist($userObj);
				$result = new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $userObj, array("Authentication success!"));
			}else{
				$result = new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null, array("Authentication failed!"));
			}
		}catch (DomainObjectNotFoundException $e){
			$result = new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, array("Authentication failed!"));
		}catch(DomainResultEmptyException $e){
			$result = new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, array("Authentication failed!"));
		}

		return $result;
	}
}
?>