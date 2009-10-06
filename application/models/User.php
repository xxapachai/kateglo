<?php
namespace kateglo\application\models;
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
 * @Entity
 * @Table(name="user")
 * 
 * @uses Exception
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since  
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class User {

	/**
	 * @Id
	 * @Column(type="integer", name="user_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * @Column(type="string", name="user_username", unique=true)
	 */
	private $username;
	
	/**
	 * 
	 * @Column(type="string", name="user_password")
	 */
	private $password;
	
	/**
	 * 
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
	 * @return the $username
	 */
	function getUsername() {
		return $this->username;
	}

	/**
	 * @return the $password
	 */
	function getPassword() {
		return $this->password;
	}

	/**
	 * @return the $lastLogin
	 */
	function getLastLogin() {
		return $this->lastLogin;
	}

	/**
	 * @param $id the $id to set
	 */
	function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param $username the $username to set
	 */
	function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @param $password the $password to set
	 */
	function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param $lastLogin the $lastLogin to set
	 */
	function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
	}

	
}

?>