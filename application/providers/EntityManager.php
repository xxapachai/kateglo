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
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM;
use Doctrine\DBAL\DriverManager;
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
class EntityManager extends \stubBaseObject implements \stubInjectionProvider {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * Enter description here ...
	 * @var Zend_Config
	 */
	private $configs;
	
	/**
	 *
	 * @var Doctrine\Common\Cache\Cache
	 */
	private $metadataCache = null;
	
	/**
	 *
	 * @var Doctrine\Common\Cache\Cache
	 */
	private $queryCache = null;
	
	/**
	 *
	 * @var Doctrine\Common\Cache\Cache
	 */
	private $resultCache = null;
	
	/**
	 *
	 * @var Doctrine\DBAL\Connection
	 */
	private $connection = null;
	
	
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
	public function get($name = NULL) {
		$config = new Configuration ();
		$config->setMetadataCacheImpl ( $this->metadataCache );
		$config->setQueryCacheImpl ( $this->queryCache );
		$config->setMetadataDriverImpl ( $config->newDefaultAnnotationDriver () );
		$config->setProxyDir ( realpath ( $this->configs->cache->doctrine->proxy ) );
		$config->setProxyNamespace ( $this->configs->cache->doctrine->namespace );
		$config->setAutoGenerateProxyClasses ( false );
		return ORM\EntityManager::create ( $this->connection, $config );
	}
	
	/**
	 *
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection() {
		return $this->connection;
	}
	
	/**
	 * @param Doctrine\DBAL\Connection $conn
	 * @return void
	 * 
	 * @Inject
	 */
	public function setConnection(Driver\Connection $connection) {
		$this->connection = $connection;
	}
	
	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function getMetadataCache() {
		return $this->metadataCache;
	}
	
	/**
	 * @param Doctrine\Common\Cache\Cache $metadataCache
	 * @Inject
	 */
	public function setMetadataCache(Cache $metadataCache) {
		$this->metadataCache = $metadataCache;
	}
	
	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function getQueryCache() {
		return $this->queryCache;
	}
	
	/**
	 * @param Doctrine\Common\Cache\Cache $queryCache
	 * @Inject
	 */
	public function setQueryCache(Cache $queryCache) {
		$this->queryCache = $queryCache;
	}
	
	/**
	 * @return Doctrine\Common\Cache\Cache
	 */
	public function getResultCache() {
		return $this->resultCache;
	}
	
	/**
	 * @param Doctrine\Common\Cache\Cache $resultCache
	 * @Inject
	 */
	public function setResultCache(Cache $resultCache) {
		$this->resultCache = $resultCache;
	}
}

?>