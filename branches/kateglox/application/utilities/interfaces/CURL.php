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
interface CURL {
	
	const INTERFACE_NAME = __CLASS__;
	
	function run();
		
	/**
	 * @return the $useragent
	 */
	function getUseragent() ;
	
	/**
	 * @return the $url
	 */
	function getUrl() ;
	
	/**
	 * @return the $followlocation
	 */
	function getFollowlocation() ;
	
	/**
	 * @return the $timeout
	 */
	function getTimeout() ;
	
	/**
	 * @return the $maxRedirects
	 */
	function getMaxRedirects() ;
	
	/**
	 * @return the $cookieFileLocation
	 */
	function getCookieFileLocation() ;
	
	/**
	 * @return the $post
	 */
	function getPost() ;
	
	/**
	 * @return the $postFields
	 */
	function getPostFields() ;
	
	/**
	 * @return the $referer
	 */
	function getReferer() ;
	
	/**
	 * @return the $session
	 */
	function getSession() ;
	
	/**
	 * @return the $result
	 */
	function getResult() ;
	
	/**
	 * @return the $includeHeader
	 */
	function getIncludeHeader() ;
	
	/**
	 * @return the $noBody
	 */
	function getNoBody() ;
	
	/**
	 * @return the $status
	 */
	function getStatus() ;
	
	/**
	 * @return the $binaryTransfer
	 */
	function getBinaryTransfer() ;
	
	/**
	 * @return the $authentication
	 */
	function getAuthentication() ;
	
	/**
	 * @return the $authName
	 */
	function getAuthName() ;
	
	/**
	 * @return the $authPass
	 */
	function getAuthPass() ;
	
	/**
	 * @param string $useragent
	 */
	function setUseragent($useragent) ;
	
	/**
	 * @param string $url
	 */
	function setUrl($url) ;
	
	/**
	 * @param boolean $followlocation
	 */
	function setFollowlocation($followlocation) ;
	
	/**
	 * @param int $timeout
	 */
	function setTimeout($timeout) ;
	
	/**
	 * @param int $maxRedirects
	 */
	function setMaxRedirects($maxRedirects) ;
	
	/**
	 * @param string $cookieFileLocation
	 */
	function setCookieFileLocation($cookieFileLocation) ;
	
	/**
	 * @param boolean $post
	 */
	function setPost($post) ;
	
	/**
	 * @param string $postFields
	 */
	function setPostFields($postFields) ;
	
	/**
	 * @param string $referer
	 */
	function setReferer($referer) ;
	
	/**
	 * @param resource(curl) $session
	 */
	function setSession($session) ;
	
	/**
	 * @param string $result
	 */
	function setResult($result) ;
	
	/**
	 * @param boolean $includeHeader
	 */
	function setIncludeHeader($includeHeader) ;
	
	/**
	 * @param boolean $noBody
	 */
	function setNoBody($noBody) ;
	
	/**
	 * @param int $status
	 */
	function setStatus($status) ;
	
	/**
	 * @param boolean $binaryTransfer
	 */
	function setBinaryTransfer($binaryTransfer) ;
	
	/**
	 * @param boolean $authentication
	 */
	function setAuthentication($authentication) ;
	
	/**
	 * @param string $authName
	 */
	function setAuthName($authName) ;
	
	/**
	 * @param string $authPass
	 */
	function setAuthPass($authPass) ;

}

?>