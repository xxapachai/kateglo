<?php
namespace kateglo\application\utilities;
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
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-15 11:53:09 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 293 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 */
class CURL implements interfaces\CURL {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * Enter description here ...
	 * @var Zend_Config
	 */
	private $configs;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $useragent;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $url;
	
	/**
	 * 
	 * Enter description here ...
	 * @var boolean
	 */
	protected $followlocation;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 */
	protected $timeout;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 */
	protected $maxRedirects;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $cookieFileLocation;
	
	/**
	 * 
	 * HTTP Method POST
	 * @var boolean
	 */
	protected $post;
	
	/**
	 * 
	 * POST fields
	 * @var string
	 */
	protected $postFields;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $referer;
	
	/**
	 * 
	 * Enter description here ...
	 * @var resource(curl)
	 */
	protected $session;
	
	/**
	 * 
	 * cURL Result
	 * @var string
	 */
	protected $result;
	
	/**
	 * 
	 * Enter description here ...
	 * @var boolean
	 */
	protected $includeHeader;
	
	/**
	 * 
	 * Enter description here ...
	 * @var boolean
	 */
	protected $noBody;
	
	/**
	 * 
	 * HTTP Status Code
	 * @var int
	 */
	protected $status;
	
	/**
	 * 
	 * Enter description here ...
	 * @var boolean
	 */
	protected $binaryTransfer;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $proxy;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $proxyUserPwd;
	
	/**
	 * 
	 * Enter description here ...
	 * @var boolean
	 */
	protected $authentication;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $authName;
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $authPass;
	
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct() {
		$this->authentication = false;
		$this->followlocation = true;
		$this->timeout = 30;
		$this->maxRedirects = 4;
		$this->binaryTransfer = false;
		$this->includeHeader = false;
		$this->noBody = false;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param Zend_Config $configs
	 * 
	 * @Inject
	 */
	public function setConfigs(\Zend_Config $configs) {
		$this->configs = $configs;
	}
	
	/**
	 * @return the $useragent
	 */
	public function getUseragent() {
		return $this->useragent;
	}

	/**
	 * @param string $useragent
	 */
	public function setUseragent($useragent) {
		$this->useragent = $useragent;
	}

	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return the $followlocation
	 */
	public function getFollowlocation() {
		return $this->followlocation;
	}

	/**
	 * @param boolean $followlocation
	 */
	public function setFollowlocation($followlocation) {
		$this->followlocation = $followlocation;
	}

	/**
	 * @return the $timeout
	 */
	public function getTimeout() {
		return $this->timeout;
	}

	/**
	 * @param int $timeout
	 */
	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	/**
	 * @return the $maxRedirects
	 */
	public function getMaxRedirects() {
		return $this->maxRedirects;
	}

	/**
	 * @param int $maxRedirects
	 */
	public function setMaxRedirects($maxRedirects) {
		$this->maxRedirects = $maxRedirects;
	}

	/**
	 * @return the $cookieFileLocation
	 */
	public function getCookieFileLocation() {
		return $this->cookieFileLocation;
	}

	/**
	 * @param string $cookieFileLocation
	 */
	public function setCookieFileLocation($cookieFileLocation) {
		$this->cookieFileLocation = $cookieFileLocation;
	}

	/**
	 * @return the $post
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * @param boolean $post
	 */
	public function setPost($post) {
		$this->post = $post;
	}

	/**
	 * @return the $postFields
	 */
	public function getPostFields() {
		return $this->postFields;
	}

	/**
	 * @param string $postFields
	 */
	public function setPostFields($postFields) {
		$this->postFields = $postFields;
	}

	/**
	 * @return the $referer
	 */
	public function getReferer() {
		return $this->referer;
	}

	/**
	 * @param string $referer
	 */
	public function setReferer($referer) {
		$this->referer = $referer;
	}

	/**
	 * @return the $session
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * @param resource(curl) $session
	 */
	public function setSession($session) {
		$this->session = $session;
	}

	/**
	 * @return the $result
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * @param string $result
	 */
	public function setResult($result) {
		$this->result = $result;
	}

	/**
	 * @return the $includeHeader
	 */
	public function getIncludeHeader() {
		return $this->includeHeader;
	}

	/**
	 * @param boolean $includeHeader
	 */
	public function setIncludeHeader($includeHeader) {
		$this->includeHeader = $includeHeader;
	}

	/**
	 * @return the $noBody
	 */
	public function getNoBody() {
		return $this->noBody;
	}

	/**
	 * @param boolean $noBody
	 */
	public function setNoBody($noBody) {
		$this->noBody = $noBody;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return the $binaryTransfer
	 */
	public function getBinaryTransfer() {
		return $this->binaryTransfer;
	}

	/**
	 * @param boolean $binaryTransfer
	 */
	public function setBinaryTransfer($binaryTransfer) {
		$this->binaryTransfer = $binaryTransfer;
	}

	/**
	 * @return the $proxy
	 */
	public function getProxy() {
		return $this->proxy;
	}

	/**
	 * @param string $proxy
	 */
	public function setProxy($proxy) {
		$this->proxy = $proxy;
	}

	/**
	 * @return the $proxyUserPwd
	 */
	public function getProxyUserPwd() {
		return $this->proxyUserPwd;
	}

	/**
	 * @param string $proxyUserPwd
	 */
	public function setProxyUserPwd($proxyUserPwd) {
		$this->proxyUserPwd = $proxyUserPwd;
	}

	/**
	 * @return the $authentication
	 */
	public function getAuthentication() {
		return $this->authentication;
	}

	/**
	 * @param boolean $authentication
	 */
	public function setAuthentication($authentication) {
		$this->authentication = $authentication;
	}

	/**
	 * @return the $authName
	 */
	public function getAuthName() {
		return $this->authName;
	}

	/**
	 * @param string $authName
	 */
	public function setAuthName($authName) {
		$this->authName = $authName;
	}

	/**
	 * @return the $authPass
	 */
	public function getAuthPass() {
		return $this->authPass;
	}

	/**
	 * @param string $authPass
	 */
	public function setAuthPass($authPass) {
		$this->authPass = $authPass;
	}

	/**
	 * 
	 * Enter description here ...
	 * @return void
	 */
	public function run() {
		$this->session = curl_init ();
		
		curl_setopt ( $this->session, CURLOPT_URL, $this->url );
		curl_setopt ( $this->session, CURLOPT_HTTPHEADER, array ('Expect:' ) );
		curl_setopt ( $this->session, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt ( $this->session, CURLOPT_MAXREDIRS, $this->maxRedirects );
		curl_setopt ( $this->session, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $this->session, CURLOPT_FOLLOWLOCATION, $this->followlocation );
		if(!empty($this->proxy)){
			curl_setopt ( $this->session, CURLOPT_PROXY, $this->proxy );
		}
		
		if(!empty($this->proxyUserPwd)){
			curl_setopt ( $this->session, CURLOPT_PROXYUSERPWD, $this->proxyUserPwd );
		}
		
		if (! empty ( $this->cookieFileLocation )) {
			curl_setopt ( $this->session, CURLOPT_COOKIEJAR, $this->cookieFileLocation );
			curl_setopt ( $this->session, CURLOPT_COOKIEFILE, $this->cookieFileLocation );
		}
		if ($this->authentication) {
			curl_setopt ( $this->session, CURLOPT_USERPWD, $this->authName . ':' . $this->authPass );
		}
		if ($this->post) {
			curl_setopt ( $this->session, CURLOPT_POST, true );
			curl_setopt ( $this->session, CURLOPT_POSTFIELDS, $this->postFields );
		
		}
		
		if ($this->includeHeader) {
			curl_setopt ( $this->session, CURLOPT_HEADER, true );
		}
		
		if ($this->noBody) {
			curl_setopt ( $this->session, CURLOPT_NOBODY, true );
		}
		
		if ($this->binaryTransfer) {
			curl_setopt ( $this->session, CURLOPT_BINARYTRANSFER, true );
		}
		
		if (! empty ( $this->useragent )) {
			curl_setopt ( $this->session, CURLOPT_USERAGENT, $this->useragent );
		}
		
		if (! empty ( $this->referer )) {
			curl_setopt ( $this->session, CURLOPT_REFERER, $this->referer );
		}
		
		$this->result = curl_exec ( $this->session );
		$this->status = curl_getinfo ( $this->session, CURLINFO_HTTP_CODE );
		curl_close ( $this->session );
	}
	
}

?>