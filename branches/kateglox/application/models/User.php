<?php
namespace kateglo\application\models;
/*
 *  $Id: User.php 286 2011-03-06 10:56:42Z arthur.purnama $
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
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-06 11:56:42 +0100 (So, 06 Mrz 2011) $
 * @version $LastChangedRevision: 286 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 * 
 * @Entity
 * @Table(name="user")
 */
class User {

	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="user_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="user_version") 
	 */
	private $version;
	
	/**
	 * @var string
	 * @Column(type="string", name="user_username", unique=true, length=255)
	 */
	private $username;
	
	/**
	 * @var string
	 * @Column(type="string", name="user_password", length=255)
	 */
	private $password;
	
	/**
	 * @var DateTime
	 * @Column(type="datetime", name="user_last_login")
	 */
	private $lastLogin;
	
	/**
	 * @return the $id
	 */
	function getId() {
		return $this->id;
	}

	/**
	 * @return the $version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param int $version
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	function getUsername() {
		return $this->username;
	}

	/**
	 * @return string
	 */
	function getPassword() {
		return $this->password;
	}

	/**
	 * @return DateTime
	 */
	function getLastLogin() {
		return $this->lastLogin;
	}

	/**
	 * @param int $id
	 */
	function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param string $username the $username to set
	 */
	function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @param string $password the $password to set
	 */
	function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param DateTime $lastLogin the $lastLogin to set
	 */
	function setLastLogin(\DateTime $lastLogin) {
		$this->lastLogin = $lastLogin;
	}

	
}

?>