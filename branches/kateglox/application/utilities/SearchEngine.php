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
class SearchEngine implements interfaces\SearchEngine {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 *
	 * @var Apache_Solr_Service
	 */
	private $service = null;
	
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
	public function setConfigs(Configs $configs) {
		$this->configs = $configs;
	}
	
	/**
	 *
	 * @return Apache_Solr_Service
	 */
	public function getSolrService() {
		if (! ($this->service instanceof \Apache_Solr_Service)) {
			$this->setSolrService ();
		}
		if ($this->service->ping ()) {
			return $this->service;
		} else {
			throw new exceptions\SearchEngineException ();
		}
	}
	
	/**
	 *
	 * @param Apache_Solr_Service $service
	 * @return void
	 */
	public function setSolrService(\Apache_Solr_Service $service = null) {
		if ($service === null) {
			$this->service = new \Apache_Solr_Service ( $this->configs->get ()->solr->host, $this->configs->get ()->solr->port, $this->configs->get ()->solr->path );
		} else {
			$this->service = $service;
		}
	}
	
	/**
	 * Simple Search interface
	 *
	 * @param string $query The raw query string
	 * @param int $offset The starting offset for result documents
	 * @param int $limit The maximum number of result documents to return
	 * @param array $params key / value pairs for other query parameters (see Solr documentation), use arrays for parameter keys used more than once (e.g. facet.field)
	 * @return Apache_Solr_Response
	 *
	 * @throws Exception If an error occurs during the service call
	 */
	public function search($query, $offset = 0, $limit = 10, $params = array(), $method = self::METHOD_GET) {
		return $this->getSolrService ()->search ( $query, $offset, $limit, $params, $method );
	}
}

?>