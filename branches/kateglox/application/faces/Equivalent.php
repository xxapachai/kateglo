<?php
namespace kateglo\application\faces;
/*
 *  $Id: Equivalent.php 285 2011-03-01 16:40:47Z arthur.purnama $
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

/**
 *
 *
 * @package kateglo\application\faces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-01 17:40:47 +0100 (Di, 01 Mrz 2011) $
 * @version $LastChangedRevision: 285 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Equivalent {
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const FOREIGN = 'foreign';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const LANGUAGE = 'language';
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	const DISCIPLINE = 'discipline';
		
	/**
	 * Enter description here ...
	 * @var string
	 */
	private $foreign;
	
	/**
	 * Enter description here ...
	 * @var string
	 */
	private $language;
	
	/**
	 * Enter description here ...
	 * @var Doctrine\Common\Collections\ArrayCollection
	 */
	private $disciplines;
		
	/**
	 * @return the $foreign
	 */
	public function getForeign() {
		return $this->foreign;
	}
	
	/**
	 * @param string $foreign
	 */
	public function setForeign($foreign) {
		$this->foreign = $foreign;
	}
	
	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
	
	/**
	 * @param string $entry
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}
	
	/**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDisciplines() {
		return $this->disciplines;
	}
	
	/**
	 * @param Doctrine\Common\Collections\ArrayCollection $disciplines
	 */
	public function setDisciplines($disciplines) {
		$this->disciplines = $disciplines;
	}
	
}

?>