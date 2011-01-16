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
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\models;
use kateglo\application\utilities\interfaces\DataAccess;
/**
 *
 *
 * @package kateglo\application\daos
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Entry implements interfaces\Entry {
	
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
	public function setDataAccess(DataAccess $dataAccess) {
		$this->dataAccess = $dataAccess;
	}
	
	/**
	 *
	 * @see kateglo\application\daos\interfaces\Entry::getAll()
	 * @param string $entry 
	 * @return kateglo\application\models\Entry
	 */
	public function getByEntry($entry) {
		$query = $this->dataAccess->getEntityManager ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e WHERE e.entry = '$entry'" );
		$result = $query->getResult ();
		if (count ( $result ) === 1) {
			if (! ($result [0] instanceof models\Entry)) {
				throw new DomainObjectNotFoundException ();
			}
		} else {
			throw new DomainResultEmptyException ();
		}
		
		return $result [0];
	}
	
	/**
	 *
	 * @return int
	 */
	public function getTotalCount() {
		$query = $this->dataAccess->getEntityManager ()->createQuery ( "SELECT COUNT(e.id) FROM " . models\Entry::CLASS_NAME . " e " );
		$result = $query->getSingleResult ();
		
		if (! (is_numeric ( $result [1] ))) {
			throw new DomainResultEmptyException ( "result not found" );
		}
		
		return $result [1];
	}
	
	/**
	 *
	 * @param int $limit
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getRandom($limit = 10) {
		$randomIdResult = $this->dataAccess->getConnection ()->query ( "SELECT entry_id FROM entry ORDER BY RAND() LIMIT " . $limit . " " );
		$idArray = array ();
		foreach ( $randomIdResult as $idResult ) {
			$idArray [] = $idResult ['entry_id'];
		}
		
		$query = $this->dataAccess->getEntityManager ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e WHERE e.id IN ('" . implode ( "','", $idArray ) . "')" );
		$result = $query->getResult ();
		if (count ( $result ) > 0) {
			return $result;
		} else {
			throw new exceptions\DomainResultEmptyException ( "result not found" );
		}
	}
	
	/**
	 * 
	 * @see kateglo\application\daos\interfaces\Entry::getAll()
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getAll() {
		$query = $this->dataAccess->getEntityManager ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e " );
		$result = $query->getResult ();
		if (count ( $result ) > 0) {
			if (! ($result [0] instanceof models\Entry)) {
				throw new DomainObjectNotFoundException ( "wrong result" );
			}
		} else {
			throw new DomainResultEmptyException ( "result not found" );
		}
		
		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $limit
	 * @param int $offset
	 * @see kateglo\application\daos\interfaces\Entry::getSome()
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getSome($limit, $offset) {
		$query = $this->dataAccess->getEntityManager ()->createQuery ( "SELECT e FROM " . models\Entry::CLASS_NAME . " e " );
		$query->setFirstResult ( $offset );
		$query->setMaxResults ( $limit );
		$result = $query->getResult ();
		if (count ( $result ) > 0) {
			if (! ($result [0] instanceof models\Entry)) {
				throw new DomainObjectNotFoundException ( "wrong result" );
			}
		} else {
			throw new DomainResultEmptyException ( "result not found" );
		}
		
		return $result;
	}

}

?>