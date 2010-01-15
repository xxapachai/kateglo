<?php
namespace kateglo\application\domains;
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

use kateglo\application\domains\exceptions;
use kateglo\application\utilities;
use kateglo\application\models;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;
/**
 *
 *
 * @package kateglo\application\domains
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Misspelled {

	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public static function getRandom($limit = 5){
		
		$resultMapping = new Query\ResultSetMapping;
		$resultMapping->addEntityResult(models\Misspelled::CLASS_NAME, 'm');
		$resultMapping->addFieldResult('m', 'lemma_id', 'id');
		
		$query = utilities\DataAccess::getEntityManager()->createNativeQuery('SELECT lemma_id FROM misspelled ORDER BY RAND() LIMIT '.$limit.' ; ', $resultMapping);
		$query->setParameter(1, $limit);
		
		$randomIdResult = $query->getResult();
		$randomId = '';
		foreach ($randomIdResult as $randomId){
			$randomId .= "'".$randomId->getId()."',";
		}	
		$getImplode = "'".implode("','", $randomIdArray)."'"; var_dump($getImplode); var_dump();
		$query = utilities\DataAccess::getEntityManager()->createQuery("SELECT m FROM ".models\Misspelled::CLASS_NAME." m WHERE m.id IN ($getImplode) ");
		$result = $query->getResult();
		$newResult = new utilities\collections\ArrayCollection();
		if(count($result) > 0){
//			$random = array_rand($result, $limit);
//			foreach($random as $randomKey){
//				if(! ($result[$randomKey] instanceof models\Misspelled)){
//					throw new exceptions\DomainObjectNotFoundException("wrong result");
//				}else{
//					$newResult->add($result[$randomKey]);
//				}
//			}
		return $result;
		}else{
			throw new exceptions\DomainResultEmptyException("result not found");
		}

	}	
}
?>