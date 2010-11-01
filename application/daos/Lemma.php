<?php
namespace kateglo\application\daos;
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
use kateglo\application\daos\interfaces;
use kateglo\application\daos\exceptions;
use kateglo\application\models;
use kateglo\application\utilities;
/**
 *
 *
 * @package kateglo\application\daos
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Lemma implements interfaces\Lemma{

	public static $CLASS_NAME = __CLASS__;

	/**
	 * 
	 * @var kateglo\application\utilities\interfaces\DataAccess
	 */
	private $dataAccess;
		
	/**
	 *
	 * @param kateglo\application\utilities\interfaces\DataAccess $dataAccess
	 * @return void
	 * 
	 * @Inject
	 */
	public function setDataAccess(utilities\interfaces\DataAccess $dataAccess){
		$this->dataAccess = $dataAccess;
	}
	
	/**
	 *
	 * @param string $lemma 
	 * @return kateglo\application\models\Lemma
	 */
	public function getByLemma($lemma){
		$query = $this->dataAccess->getEntityManager()->createQuery("SELECT l FROM ".models\Lemma::CLASS_NAME." l WHERE l.lemma = '$lemma'");
		$result = $query->getResult();
		if(count($result) === 1){
			if(! ($result[0] instanceof models\Lemma)){
				throw new exceptions\DomainObjectNotFoundException();
			}
		}else{
			throw new exceptions\DomainResultEmptyException();
		}

		return $result[0];
	}

	/**
	 *
	 * @return int
	 */
	public function getTotalCount(){
		$query = $this->dataAccess->getEntityManager()->createQuery("SELECT COUNT(l.id) FROM ".models\Lemma::CLASS_NAME." l ");
		$result = $query->getSingleResult();

		if(! ( is_numeric($result[1]) )){var_dump($result); die();
		throw new exceptions\DomainResultEmptyException("result not found");
		}
			

		return $result[1];
	}

	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getRandom($limit = 10){

		$randomIdResult = $this->dataAccess->getConnection()->query("SELECT lemma_id FROM lemma ORDER BY RAND() LIMIT ".$limit." ");
		$idArray = array();
		foreach($randomIdResult as $idResult){
			$idArray[] = $idResult['lemma_id'];
		}

		$sql = "SELECT l FROM ".models\Lemma::CLASS_NAME." l WHERE l.id IN ('".implode("','", $idArray)."')";

		$query = $this->dataAccess->getEntityManager()->createQuery($sql);
		$result = $query->getResult();
		if(count($result) > 0){
			return $result;
		}else{
			throw new exceptions\DomainResultEmptyException("result not found");
		}
	}

	/**
	 * 
	 * @param int $offset
	 * @param int $limit
	 * @param string $lemma
	 * @param array $type
	 * @param string $definition
	 * @param array $lexical
	 * @param string $orderBy
	 * @param string $direction
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getLists($offset = 0, $limit = 50, $lemma = "", array $type, $definition = "", array $lexical, $orderBy = "", $direction = "ASC"){
		$sqlSelect = "SELECT l";
		$sqlFrom = "FROM ".models\Lemma::CLASS_NAME." l";
		
		$whereClause = "";

		if($lemma !== ""){
			$whereClause .= " l.lemma LIKE '".$lemma."%' ";
		}

		if($definition !== ""){
			$sqlSelect .= ", d";
			$sqlFrom .= " LEFT JOIN l.definitions d";
			if($whereClause !== ""){
				$whereClause .= " AND ";
			}
			$whereClause .= " d.definition LIKE '".$definition."%' ";
		}

		if(count($type) > 0){
			$sqlSelect .= ", t";
			$sqlFrom .= " LEFT JOIN l.types t";
			if($whereClause !== ""){
				$whereClause .= " AND ";
			}
			$whereClause .= " t.type IN ('".implode("','", $type)."') ";
		}

		if(count($lexical) > 0){
			if($definition !== ""){
				$sqlSelect .= ", lx";
				$sqlFrom .= " LEFT JOIN d.lexical lx";
			}else{
				$sqlSelect .= ", d, lx";
				$sqlFrom .= " LEFT JOIN l.definitions d LEFT JOIN d.lexical lx";
			}
			if($whereClause !== ""){
				$whereClause .= " AND ";
			}
			$whereClause .= " lx.lexical IN ('".implode("','", $lexical)."') ";
		}
		$sql = $sqlSelect." ".$sqlFrom." ";
		if($whereClause !== ""){
			$sql .= ' WHERE '.$whereClause;
		}

		switch ($orderBy){
			case 'lemma':
				$orderBy = " l.lemma ";
				break;
			case 'type':
				$orderBy = " t.type ";
				break;
			case 'definition':
				$orderBy = " d.definition ";
				break;
			case 'lexical':
				$orderBy = " lx.lexical ";
				break;
			default:
				$orderBy = " l.lemma ";
		}


		$sql .= " ORDER BY".$orderBy." ".$direction;

		$query = $this->dataAccess->getEntityManager()->createQuery($sql);
		$query->setFirstResult($offset);
		$query->setMaxResults($limit);
		$result = $query->getResult();
		if(count($result) > 0){
			if(! ($result[0] instanceof models\Lemma)){
				throw new exceptions\DomainObjectNotFoundException("wrong result");
			}
		}else{
			throw new exceptions\DomainResultEmptyException("result not found");
		}

		return $result;

	}

}
?>