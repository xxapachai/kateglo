<?php
namespace kateglo\application\models;
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
use kateglo\application\utilities\collections;
use kateglo\application\models;
/**
 *
 *
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since 2009-10-07
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="locale")
 */
class Locale {

	const CLASS_NAME = __CLASS__;

	/**
	 *
	 * @var int
	 * @Id
	 * @Column(type="integer", name="locale_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="locale_name", unique=true, length=255)
	 */
	private $locale;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="locale_abbreviation", unique=true, length=255)
	 */
	private $abbreviation;
	
	/**
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Glossary", mappedBy="locale", cascade={"persist"})
	 */
	private $glossaries;

	public function __construct(){
		$this->glossaries = new collections\ArrayCollection();
	}

	/**
	 *
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 *
	 * @param string $locale
	 * @return void
	 */
	public function setLocale($locale){
		$this->locale = $locale;
	}

	/**
	 *
	 * @return string
	 */
	public function getLocale(){
		return $this->locale;
	}

	/**
	 *
	 * @param string $abbreviation
	 * @return void
	 */
	public function setAbbreviation($abbreviation){
		$this->abbreviation = $abbreviation;
	}

	/**
	 *
	 * @return string
	 */
	public function getAbbreviation(){
		return $this->abbreviation;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Glossary $glossary
	 * @return void
	 */
	public function addGlossary(models\Glossary $glossary){
		$this->glossaries[] = $glossary;
		$glossary->setLocale($this);
	}

	/**
	 *
	 * @param kateglo\application\models\Glossary $glossary
	 * @return void
	 */
	public function removeGlossary(models\Glossary $glossary){
		/*@var $removed kateglo\application\models\Glossary */
		$removed = $this->glossaries->removeElement($glossary);
		if ($removed !== null) {
			$removed->removeLocale();
		}
	}

	/**
	 *
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getGlossaries(){
		return $this->glossaries;
	}
}
?>