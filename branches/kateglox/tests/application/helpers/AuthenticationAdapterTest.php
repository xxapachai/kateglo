<?php
namespace kateglo\tests\application\helpers;
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
use kateglo\application\helpers;
use kateglo\application\utilities;
/**
 *
 *
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class AuthenticationAdapterTest extends \PHPUnit_Framework_TestCase{

	const CLASS_NAME = __CLASS__;

	/**
	 *
	 * @return void
	 */
	protected function setUp(){

	}

	/**
	 *
	 * @return void
	 */
	protected function tearDown(){
		utilities\DataAccess::clearEntityManager();
	}

	/**
	 *
	 * @return void
	 */
	public function testAuthenticateUserNotFound(){
		$auth = new helpers\AuthenticationAdapter('Undefined', 'Undefined');
		/*@var $result \Zend_Auth_Result */
		$result = $auth->authenticate();
		if($result instanceof \Zend_Auth_Result){
			$this->assertEquals(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
			$this->assertFalse($result->isValid());
		}else{
			$this->fail('Variable is not an Object');
		}

	}

	/**
	 *
	 * @return void
	 */
	public function testAuthenticateWrongPassword(){
		$auth = new helpers\AuthenticationAdapter('arthur@purnama.de', 'Undefined');
		/*@var $result \Zend_Auth_Result */
		$result = $auth->authenticate();
		if($result instanceof \Zend_Auth_Result){
			$this->assertEquals(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
			$this->assertFalse($result->isValid());
		}else{
			$this->fail('Variable is not an Object');
		}
	}

	/**
	 *
	 * @return void
	 */
	public function testAuthenticate(){
		$auth = new helpers\AuthenticationAdapter('arthur@purnama.de', 'arthur');
		/*@var $result \Zend_Auth_Result */
		$result = $auth->authenticate();
		if($result instanceof \Zend_Auth_Result){
			$this->assertEquals(\Zend_Auth_Result::SUCCESS, $result->getCode());
			$this->assertTrue($result->isValid());
			/*@var $identity kateglo\application\models\User */
			$identity = $result->getIdentity();
			$this->assertEquals('arthur@purnama.de', $identity->getUsername());
			$this->assertEquals(md5('arthur'), $identity->getPassword());
		}else{
			$this->fail('Variable is not an Object');
		}
	}
}
?>