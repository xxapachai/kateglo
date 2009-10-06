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
use Doctrine\Common;
use Doctrine\Common\Cache;
use Doctrine\DBAL;
use Doctrine\ORM;
/**
 * 
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
class DataAccess {

	/**
	 * 
	 * @var EntityManager
	 */
	private static $entityManager = null;
	
	/**
	 * 
	 * @return EntityManager
	 */
	public static function getEntityManager()
	{

		if(self::$entityManager == null){
			
        	$eventManager = new Common\EventManager(); 
        	
        	$params = array("driver"=> configs\Configs::getInstance()->database->adapter, 
        					"host" => configs\Configs::getInstance()->database->host,
        					"port" => configs\Configs::getInstance()->database->port,
        					"dbname" => configs\Configs::getInstance()->database->name,
        					"user" => configs\Configs::getInstance()->database->username,
        					"password" => configs\Configs::getInstance()->database->password);
        	
        	$conn = DBAL\DriverManager::getConnection($params, null, $eventManager);
        	$conn->connect();
        	$config = new ORM\Configuration();
      		$config->setMetadataCacheImpl(new Cache\ArrayCache());
        	$config->setQueryCacheImpl(new Cache\ArrayCache());
        	
        	self::$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config, $eventManager);
		}
		return self::$entityManager;
	}
}

?>