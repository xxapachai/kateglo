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
 * @Table(name="sample")
 */
class Sample {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="sample_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="sample_text", unique=true, length=255)
	 */
	private $sample;
	
	/**
	 * @var kateglo\application\models\Definition
	 * @ManyToOne(targetEntity="kateglo\application\models\Definition")
	 * @JoinColumn(name="sample_definition_id", referencedColumnName="definition_id")
	 */
	private $definition;
	
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
	 *
	 * @param string $sample
	 * @return void
	 */
	public function setSample($sample) {
		$this->sample = $sample;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSample() {
		return $this->sample;
	}
	
	/**
	 *
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */
	public function setDefinition(Definition $definition) {
		$this->definition = $definition;
	}
	
	/**
	 *
	 * @return kateglo\application\models\Definition
	 */
	public function getDefinition() {
		return $this->definition;
	}
	
	/**
	 *
	 * @return void
	 */
	public function removeDefinition() {
		if ($this->entry !== null) {
			/*@var $entry kateglo\application\models\Definition */
			$definition = $this->definition;
			$this->definition = null;
			$definition->removeSample($this);
		}
	}

}

?>