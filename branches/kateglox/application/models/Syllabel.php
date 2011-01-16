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
use Doctrine\Common\Collections\ArrayCollection;
/**
 *
 *
 * @package kateglo\application\models
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 *
 * @Entity
 * @Table(name="syllabel")
 */
class Syllabel {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="syllabel_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="syllabel_text", unique=true, length=255)
	 */
	protected $syllabel;
	
	/**
	 * @var kateglo\application\models\Meaning
	 * @ManyToOne(targetEntity="kateglo\application\models\Meaning")
	 * @JoinColumn(name="syllabel_meaning_id", referencedColumnName="meaning_id")
	 */
	private $meaning;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity="kateglo\application\models\Pronounciation", mappedBy="syllabel", cascade={"persist"})
	 */
	private $pronounciations;
	
	function __construct() {
		$this->pronounciations = new ArrayCollection ();
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param string $syllabel
	 * @return void
	 */
	public function setSyllabel($syllabel) {
		$this->syllabel = $syllabel;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSyllabel() {
		return $this->syllabel;
	}
	
	/**
	 * @return kateglo\application\models\Meaning
	 */
	public function getMeaning() {
		return $this->meaning;
	}
	
	/**
	 * @param kateglo\application\models\Meaning $meaning
	 * @return void
	 */
	public function setMeaning(Meaning $meaning) {
		$this->meaning = $meaning;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeMeaning() {
		if ($this->meaning !== null) {
			/*@var $entry kateglo\application\models\Meaning */
			$meaning = $this->meaning;
			$this->meaning = null;
			$meaning->removeSyllabel ( $this );
		}
	}
	
	/**
	 *
	 * @param kateglo\application\models\Pronounciation $pronounciation
	 * @return void
	 */
	public function addPronounciation(Pronounciation $pronounciation) {
		$this->pronounciations [] = $pronounciation;
		$pronounciation->setSyllabel ( $this );
	}
	
	/**
	 *
	 * @param kateglo\application\models\Pronounciation $pronounciation
	 * @return void
	 */
	public function removePronounciation(Pronounciation $pronounciation) {
		/*@var $removed kateglo\application\models\Pronounciation */
		$removed = $this->pronounciations->removeElement ( $pronounciation );
		if ($removed !== null) {
			$removed->removeSyllabel ();
		}
	}
	
	/**
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getPronounciations() {
		return $this->pronounciations;
	}
}

?>