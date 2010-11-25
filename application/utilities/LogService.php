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
use kateglo\application\configs;
use kateglo\application\utilities\interfaces;
/**
 *
 *
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Singleton
 */
class LogService implements interfaces\LogService{

	public static $CLASS_NAME = __CLASS__;
	
	/**
	 *
	 * @var Zend_Log
	 */
	private $logInstance;
	
	/**
	 *
	 * @var kateglo\application\configs\interfaces\Configs
	 */
	private $configs;
	
	/**
	 *
	 * @param kateglo\application\configs\interfaces\Configs $configs 
	 * @return void
	 *
	 * @Inject
	 */
	public function setConfigs(configs\interfaces\Configs $configs){
		$this->configs = $configs;
	}

	/**
	 *
	 * @return Zend_Log
	 */
	public function get(){
		if(! ($this->logInstance instanceof \Zend_Log)){
			$this->set();
		}

		return $this->logInstance;
	}

	/**
	 * 
	 * @param Zend_Log $logInstance
	 * @return void
	 */
	public function set(\Zend_Log $logInstance = null){
		if($logInstance === null){
			if(! ($this->logInstance instanceof \Zend_Log)){
				$this->logInstance = new \Zend_Log();
				$this->logInstance->addWriter(new \Zend_Log_Writer_Stream($this->configs->get()->errorLog));
			}
		}else{
			$this->logInstance = $logInstance;
		}
	}
}

?>