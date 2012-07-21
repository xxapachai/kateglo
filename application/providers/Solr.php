<?php
namespace kateglo\application\providers;
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
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Singleton
 */
class Solr extends \net\stubbles\lang\BaseObject implements \net\stubbles\ioc\InjectionProvider {
	
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
	 * @param Zend_Config $configs
	 * 
	 * @Inject
	 */
	public function setConfigs(\Zend_Config $configs) {
		$this->configs = $configs;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see stubInjectionProvider::get()
	 */
	public function get($name = NULL){
		return new \Apache_Solr_Service ( $this->configs->solr->host, $this->configs->solr->port, $this->configs->solr->path );
	}
}

?>