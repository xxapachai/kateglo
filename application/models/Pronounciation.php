<?php
namespace kateglo\application\models;
/*
 *  $Id: Pronounciation.php 286 2011-03-06 10:56:42Z arthur.purnama $
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
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-06 11:56:42 +0100 (So, 06 Mrz 2011) $
 * @version $LastChangedRevision: 286 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="pronounciation")
 */
class Pronounciation {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="pronounciation_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 * @Version
	 * @Column(type="integer", name="pronounciation_version") 
	 */
	private $version;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="pronounciation_text", length=255)
	 */
	private $pronounciation;
	
	/**
	 * @var kateglo\application\models\Syllabel
	 * @ManyToOne(targetEntity="kateglo\application\models\Syllabel")
	 * @JoinColumn(name="pronounciation_syllabel_id", referencedColumnName="syllabel_id")
	 */
	private $syllabel;
	
	public function __construct() {
	
	}
	
	/**
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param int $version
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

	/**
	 *
	 * @param string pronounciation
	 * @return void
	 */
	public function setPronounciation($pronounciation) {
		$this->pronounciation = $pronounciation;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getPronounciation() {
		return $this->pronounciation;
	}
	
	/**
	 * @return kateglo\application\models\Syllabel
	 */
	public function getSyllabel() {
		return $this->syllabel;
	}
	
	/**
	 * @param kateglo\application\models\Syllabel $syllabel
	 * @return void
	 */
	public function setSyllabel(Syllabel $syllabel) {
		$this->syllabel = $syllabel;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeSyllabel() {
		if ($this->syllabel !== null) {
			/*@var $entry kateglo\application\models\Syllabel */
			$syllabel = $this->syllabel;
			$this->syllabel = null;
			$syllabel->removePronounciation ( $this );
		}
	}
}

?>