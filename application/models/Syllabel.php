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
 * @Table(name="syllabel")
 */
class Syllabel {

	const CLASS_NAME = __CLASS__;

	/**
	 * 
	 * @var int
	 * @Id 
	 * @Column(type="integer", name="syllabel_lemma_id")
	 */
	private $id;
	
	/**
	 * @var kateglo\application\models\Lemma
	 * @OneToOne(targetEntity="kateglo\application\models\Lemma")
	 * @JoinColumn(name="syllabel_lemma_id", referencedColumnName="lemma_id")
	 */
	private $lemma;

	/**
	 *
	 * @var string
	 * @Column(type="string", name="syllabel_name", unique=true, length=255)
	 */
	private $syllabel;

	/**
	 *
	 * @param kateglo\application\models\Lemma $lemma
	 * @return void
	 */
	public function setLemma(models\Lemma $lemma){
		$this->lemma = $lemma;
	}

	/**
	 *
	 * @return kateglo\application\models\Lemma
	 */
	public function getLemma(){
		return $this->lemma;
	}

	/**
	 *
	 * @param string $syllabel
	 * @return void
	 */
	public function setSyllabel($syllabel){
		$this->syllabel = $syllabel;
	}

	/**
	 *
	 * @return string
	 */
	public function getSyllabel(){
		return $this->syllabel;
	}
}
?>