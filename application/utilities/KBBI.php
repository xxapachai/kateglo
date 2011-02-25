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
use kateglo\application\utilities\interfaces\CURL;

use kateglo\application\configs\interfaces\Configs;
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
class KBBI implements interfaces\KBBI {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 *
	 * @var kateglo\application\configs\interfaces\Configs
	 */
	private $configs;
	
	/**
	 * 
	 * Enter description here ...
	 * @var kateglo\application\utilities\interfaces\CURL
	 */
	private $curl;
	
	/**
	 *
	 * @param kateglo\application\configs\interfaces\Configs $configs 
	 * @return void
	 *
	 * @Inject
	 */
	public function setConfigs(Configs $configs) {
		$this->configs = $configs;
	}
	
	/**
	 *
	 * @param kateglo\application\utilities\interfaces\CURL $curl 
	 * @return void
	 *
	 * @Inject
	 */
	public function setCurl(CURL $curl) {
		$this->curl = $curl;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see kateglo\application\utilities\interfaces.KBBI::getEntry()
	 */
	public function getEntry($entry) {
		$url = 'http://pusatbahasa.diknas.go.id/kbbi/index.php';
		$data = 'OPKODE=1&PARAM=%1$s&HEAD=0&MORE=0&PERINTAH2=&PERINTAH=Cari';
		$data = sprintf ( $data, $entry );
		$result = $this->getCurl ( $url, $data );
		$pattern = '/<input type="hidden" name="DFTKATA" value="(.+)" >.+' . '<input type="hidden" name="MORE" value="(.+)" >.+' . '<input type="hidden" name="HEAD" value="(.+)" >/s';
		preg_match ( $pattern, $result, $match );
		return $match;
	}
	
	private function getCurl($url, $data) {
		$this->curl->setUrl ( $url );
		$this->curl->setPost ( true );
		$this->curl->setPostFields ( $data );
		$this->curl->setTimeout ( $this->configs->get ()->curl->timeout );
		$this->curl->run ();
		return $this->curl->getResult ();
	}
}

?>