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
use Doctrine\Common\Cache\ApcCache;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
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
 *
 * @Singleton
 */
class DataAccess implements interfaces\DataAccess {

	public static $CLASS_NAME = __CLASS__;
	
	/**
	 *
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager = null;

	/**
	 *
	 * @var Doctrine\Common\Cache\ArrayCache
	 */
	private $metadataCache = null;

	/**
	 *
	 * @var Doctrine\Common\Cache\ArrayCache
	 */
	private $queryCache = null;

	/**
	 *
	 * @var Doctrine\DBAL\Connection
	 */
	private $conn = null;

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
	public function setConfigs(Configs $configs){
		$this->configs = $configs;
	}

	/**
	 *
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		if(! ($this->entityManager instanceof EntityManager)){
			$this->setEntityManager(); 			 
		}
		return $this->entityManager;
	}

	/**
	 *
	 * @return void
	 */
	public function clearEntityManager(){
		$this->entityManager = null;
	}

	/**
	 *
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @return void
	 */
	public function setEntityManager(EntityManager $entityManager = null){
		if($entityManager === null){
			if(! ($this->entityManager instanceof EntityManager)){
				if($this->conn == null){
					$this->setConnection();
				}
				if($this->metadataCache == null){
					$this->metadataCache = new ApcCache();
				}
				if($this->queryCache == null){
					$this->queryCache = new ApcCache();
				}
				$config = new Configuration();
				$config->setMetadataCacheImpl($this->metadataCache);
				$config->setQueryCacheImpl($this->queryCache);
				$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
				$config->setProxyDir(realpath($this->configs->get()->cache->doctrine->proxy));
				$config->setProxyNamespace($this->configs->get()->cache->doctrine->namespace);
				//$config->setAutoGenerateProxyClasses(false); 
				$this->entityManager = EntityManager::create($this->conn, $config);
			}
		}else{
			$this->entityManager = $entityManager;
		}
	}

	/**
	 *
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection(){
		if(! ($this->conn instanceof DriverManager)){
			$this->setConnection();
		}
		return $this->conn;
	}

	/**
	 * @param Doctrine\DBAL\Connection $conn
	 * @return void
	 */
	public function setConnection(Connection $conn = null){
		if($conn === null){
			if(! ($this->conn instanceof DriverManager) ){
				$params = array("driver"=> $this->configs->get()->database->adapter,
        					"host" => $this->configs->get()->database->host,
        					"port" => $this->configs->get()->database->port,
        					"dbname" => $this->configs->get()->database->name,
        					"user" => $this->configs->get()->database->username,
        					"password" => $this->configs->get()->database->password);
				$this->conn = DriverManager::getConnection($params, null);
				$this->conn->setCharset($this->configs->get()->database->charset);
			}
		}else{
			$this->conn = $conn;
		}
		$this->conn->connect();
	}
}

?>