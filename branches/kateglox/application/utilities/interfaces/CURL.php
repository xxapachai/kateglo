<?php
namespace kateglo\application\utilities\interfaces;
/*
 *  $Id: CURL.php 293 2011-03-15 10:53:09Z arthur.purnama $
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
 * @package kateglo\application\utilities\interfaces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-15 11:53:09 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 293 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
interface CURL {
	
	const INTERFACE_NAME = __CLASS__;
		
	/**
	 * @return the $useragent
	 */
	function getUseragent();
	
	/**
	 * @param string $useragent
	 */
	function setUseragent($useragent);
	
	/**
	 * @return the $url
	 */
	function getUrl();
	
	/**
	 * @param string $url
	 */
	function setUrl($url);
	
	/**
	 * @return the $followlocation
	 */
	function getFollowlocation();
	
	/**
	 * @param boolean $followlocation
	 */
	function setFollowlocation($followlocation);
	
	/**
	 * @return the $timeout
	 */
	function getTimeout();
	
	/**
	 * @param int $timeout
	 */
	function setTimeout($timeout);
	
	/**
	 * @return the $maxRedirects
	 */
	function getMaxRedirects();
	
	/**
	 * @param int $maxRedirects
	 */
	function setMaxRedirects($maxRedirects);
	
	/**
	 * @return the $cookieFileLocation
	 */
	function getCookieFileLocation();
	
	/**
	 * @param string $cookieFileLocation
	 */
	function setCookieFileLocation($cookieFileLocation);
	
	/**
	 * @return the $post
	 */
	function getPost();
	
	/**
	 * @param boolean $post
	 */
	function setPost($post);
	
	/**
	 * @return the $postFields
	 */
	function getPostFields();
	
	/**
	 * @param string $postFields
	 */
	function setPostFields($postFields);
	
	/**
	 * @return the $referer
	 */
	function getReferer();
	
	/**
	 * @param string $referer
	 */
	function setReferer($referer);
	
	/**
	 * @return the $session
	 */
	function getSession();
	
	/**
	 * @param resource(curl) $session
	 */
	function setSession($session);
	
	/**
	 * @return the $result
	 */
	function getResult();
	
	/**
	 * @param string $result
	 */
	function setResult($result);
	
	/**
	 * @return the $includeHeader
	 */
	function getIncludeHeader();
	
	/**
	 * @param boolean $includeHeader
	 */
	function setIncludeHeader($includeHeader);
	
	/**
	 * @return the $noBody
	 */
	function getNoBody();
	
	/**
	 * @param boolean $noBody
	 */
	function setNoBody($noBody);
	
	/**
	 * @return the $status
	 */
	function getStatus();
	
	/**
	 * @param int $status
	 */
	function setStatus($status);
	
	/**
	 * @return the $binaryTransfer
	 */
	function getBinaryTransfer();
	
	/**
	 * @param boolean $binaryTransfer
	 */
	function setBinaryTransfer($binaryTransfer);
	
	/**
	 * @return the $proxy
	 */
	function getProxy();
	
	/**
	 * @param string $proxy
	 */
	function setProxy($proxy);
	
	/**
	 * @return the $proxyUserPwd
	 */
	function getProxyUserPwd();
	
	/**
	 * @param string $proxyUserPwd
	 */
	function setProxyUserPwd($proxyUserPwd);
	
	/**
	 * @return the $authentication
	 */
	function getAuthentication();
	
	/**
	 * @param boolean $authentication
	 */
	function setAuthentication($authentication);
	
	/**
	 * @return the $authName
	 */
	function getAuthName();
	
	/**
	 * @param string $authName
	 */
	function setAuthName($authName);
	
	/**
	 * @return the $authPass
	 */
	function getAuthPass();
	
	/**
	 * @param string $authPass
	 */
	function setAuthPass($authPass);
		
	/**
	 * 
	 * Enter description here ...
	 */
	function run();

}

?>