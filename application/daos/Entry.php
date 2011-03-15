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
use Doctrine\ORM\EntityManager;
use kateglo\application\daos\exceptions\DomainResultEmptyException;
use kateglo\application\daos\exceptions\DomainObjectNotFoundException;
use kateglo\application\models;
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
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
	/**
	 *
	 * @param Doctrine\ORM\EntityManager $entityManager
	 * @return void
	 * 
	 * @Inject
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}
	
	/**
	 *
	 * @see kateglo\application\daos\interfaces\Entry::getByEntry()
	 * @param string $entry 
	 * @return kateglo\application\models\Entry
	 */
	public function getByEntry($entry) {
		$query = $this->entityManager->createQuery ( "
			SELECT 	entry, meaning, antonym, definition, 
					misspelled, relation, spelled, syllabel, 
					synonym, type, clazz, clazzCategory, discipline,
					equivalent, eqDiscipline, foreign, language, 
					pronounciation,	sample, source, sourceCategory, 
					typeCategory  
			FROM " . models\Entry::CLASS_NAME . " entry 
				LEFT JOIN entry.equivalents equivalent
				LEFT JOIN entry.meanings meaning
				LEFT JOIN entry.sources source
				LEFT JOIN meaning.antonyms antonym
				LEFT JOIN meaning.definitions definition
				LEFT JOIN meaning.misspelleds misspelled
				LEFT JOIN meaning.relations relation
				LEFT JOIN meaning.spelled spelled
				LEFT JOIN meaning.syllabels syllabel
				LEFT JOIN syllabel.pronounciations pronounciation
				LEFT JOIN meaning.synonyms synonym
				LEFT JOIN meaning.types type
				LEFT JOIN type.categories typeCategory
				LEFT JOIN source.category sourceCategory
				LEFT JOIN equivalent.disciplines eqDiscipline
				LEFT JOIN equivalent.foreign foreign
				LEFT JOIN foreign.language language
				LEFT JOIN definition.disciplines discipline
				LEFT JOIN definition.clazz clazz
				LEFT JOIN definition.samples sample
				LEFT JOIN clazz.categories clazzCategory
			WHERE entry.entry = '$entry'" );
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

}

?>