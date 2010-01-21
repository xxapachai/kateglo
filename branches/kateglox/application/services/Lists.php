<?php
namespace kateglo\application\services;
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

use kateglo\application\domains;
/**
 *
 *
 * @package kateglo\application\services
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-10-07
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Lists {

	const CLASS_NAME = __CLASS__;

	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function randomMisspelled($limit = 5){
		$result = domains\Misspelled::getRandom($limit);
		return $result;
	}

	/**
	 *
	 * @param int $limit
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function randomLemma($limit = 10){
		$result = domains\Lemma::getRandom($limit);
		return $result;
	}

	/**
	 *
	 * @param int $offset
	 * @param int $limit
	 * @param array $filters
	 * @param string $orderBy
	 * @param string $direction
	 * @return array
	 */
	public function listLemma($offset = 0, $limit = 50, array $filters = array(), $orderBy = "", $direction = "ASC"){
		$lemma = "";
		$type = array();
		$definition = "";
		$lexical = array();
		
		foreach($filters as $filter){
			if($filter['field'] == 'lemma'){
				$lemma = $filter['data']['value'];
			}
			
			if($filter['field'] == 'type'){
				$type = explode(',', $filter['data']['value']);
			}
			
			if($filter['field'] == 'definition'){
				$definition = $filter['data']['value'];
			}
			
			if($filter['field'] == 'lexical'){
				$lexical = explode(',', $filter['data']['value']);
			}
		}		
		
		$result = domains\Lemma::getLists($offset, $limit, $lemma, $type, $definition, $lexical, $orderBy, $direction);
		$arrayResult = array();
		/*@var $lemma kateglo\application\models\Lemma */
		foreach($result as $lemma){
			if($lemma->getDefinitions()->count() > 0){
				/*@var $definition kateglo\application\models\Definition */
				foreach ($lemma->getDefinitions() as $definition){
					$entityArray = array();
					$entityArray['lemma'] = $lemma->getLemma();
					/*@var $type kateglo\application\models\Type */
					foreach($lemma->getTypes() as $type){
						$entityArray['type'] = $type->getType();
					}
					$entityArray['definition'] = $definition->getDefinition();
					$entityArray['lexical'] = $definition->getLexical()->getLexical();					
					$arrayResult[] = $entityArray;
				}
			}else{				
				$entityArray = array();
				$entityArray['lemma'] = $lemma->getLemma();
				/*@var $type kateglo\application\models\Type */
				foreach($lemma->getTypes() as $type){
					$entityArray['type'] = $type->getType();
				}
				$arrayResult[] = $entityArray;
			}

		}
		return $arrayResult;
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function listType(){
		$result = domains\Type::getAllType();
		return $result;
	}
	
	/**
	 * 
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function listLexical(){
		$result = domains\Lexical::getAllLexical();
		return $result;
	}
}
?>