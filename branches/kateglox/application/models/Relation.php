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
 * @Table(name="relation")
 */
class Relation {

	const CLASS_NAME = __CLASS__;

	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="relation_id")
	 */
	private $id;
	
	/**
	 * @var kateglo\application\models\Lemma
	 * @ManyToOne(targetEntity="kateglo\application\models\Lemma")
	 * @JoinColumn(name="relation_parent_id", referencedColumnName="lemma_id")
	 */
	private $parent;

	/**
	 * @var kateglo\application\models\Lemma
	 * @OneToOne(targetEntity="kateglo\application\models\Lemma")
	 * @JoinColumn(name="relation_child_id", referencedColumnName="lemma_id")
	 */
	private $child;

	/**
	 * @var kateglo\application\models\RelationType
	 * @ManyToOne(targetEntity="kateglo\application\models\RelationType")
	 * @JoinColumn(name="relation_type_id", referencedColumnName="relation_type_id")
	 */
	private $type;

	/**
	 *
	 * @param kateglo\application\models\Lemma $parent
	 * @return void
	 */
	public function setParent(models\Lemma $parent){
		$this->parent = $parent;
	}

	/**
	 *
	 * @return kateglo\application\models\Phrase
	 */
	public function getParent(){
		return $this->parent;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Lemma $child
	 * @return void
	 */
	public function setChild(models\Lemma $child){
		$this->child = $child;
	}

	/**
	 *
	 * @return kateglo\application\models\Lemma
	 */
	public function getChild(){
		return $this->child;
	}

	/**
	 *
	 * @param kateglo\application\models\RelationType $type
	 * @return void
	 */
	public function setType(models\RelationType $type){
		$this->type = $type;
	}

	/**
	 *
	 * @return kateglo\application\models\RelationType
	 */
	public function getType(){
		return $this->type;
	}

	/**
	 *
	 * @return void
	 */
	public function removeType() {
		if ($this->type !== null) {
			/*@var $phrase kateglo\application\models\RelationType */
			$type = $this->type;
			$this->type = null;
			$type->removeRelation($this);
		}
	}
}
?>