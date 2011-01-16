<?php
namespace kateglo\tests\application\services;
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

use kateglo\application\models;

use kateglo\application\utilities;

use kateglo\application\services;
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
class AuthenticationTest extends \PHPUnit_Framework_TestCase {

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
		\Zend_Auth::getInstance()->clearIdentity();
		
	}

	/**
	 *
	 * @return void
	 */
	public function testNotHasIdentity(){
		$auth = new services\Authentication();
		$this->assertFalse($auth->hasIdentity());
	}
	
	/**
	 *
	 * @return void
	 */
	public function testNotGetIdentity(){
		$auth = new services\Authentication();
		$this->assertNull($auth->getIdentity());
	}

	/**
	 *
	 * @return void
	 */
	public function testHasIdentity(){
		$auth = new services\Authentication();
		$auth->authenticate('arthur@purnama.de', 'arthur');
		$this->assertTrue($auth->hasIdentity());
	}

	/**
	 *
	 * @return void
	 */
	public function testGetIdentity(){
		$auth = new services\Authentication();
		$auth->authenticate('arthur@purnama.de', 'arthur');
		$this->assertTrue($auth->getIdentity() instanceof models\User);
		/*@var $identity kateglo\application\models\User */
		$identity = $auth->getIdentity();
		$this->assertEquals('arthur@purnama.de', $identity->getUsername());
		$this->assertEquals(md5('arthur'), $identity->getPassword());		
	}

	/**
	 *
	 * @return void
	 */
	public function testClearIdentity(){
		$auth = new services\Authentication();
		$auth->authenticate('arthur@purnama.de', 'arthur');
		$this->assertTrue($auth->getIdentity() instanceof models\User);
		$auth->clearIdentity();
		$this->assertFalse($auth->hasIdentity());
		$this->assertNull($auth->getIdentity());
		$this->assertFalse($auth->getIdentity() instanceof models\User);
	}

	/**
     * @expectedException kateglo\application\services\exceptions\AuthenticationException
     * @return void
     */
	public function testAuthenticateNotSucceeded(){
		$auth = new services\Authentication();
		$auth->authenticate('arthur@purnama.de', 'Undefined');
	}
	
	/**
	 *
	 * @return void
	 */
	public function testAuthenticate(){
		$auth = new services\Authentication();
		$auth->authenticate('arthur@purnama.de', 'arthur');
		$this->assertTrue($auth->getIdentity() instanceof models\User);
		$this->assertTrue($auth->hasIdentity());
	}
}
?>