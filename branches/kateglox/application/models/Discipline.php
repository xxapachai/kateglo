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
 * @Table(name="discipline")
 */
class Discipline {
	
	const CLASS_NAME = __CLASS__;
	
	/**
	 * @var int
	 * @Id
	 * @Column(type="integer", name="discipline_id")
	 * @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 *
	 * @var string
	 * @Column(type="string", name="discipline_name", unique=true, length=255)
	 */
	protected $discipline;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Definition")
	 * @JoinTable(name="rel_definition_discipline",
	 * joinColumns={@JoinColumn(name="rel_discipline_id", referencedColumnName="discipline_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_definition_id", referencedColumnName="definition_id")}
	 * )
	 */
	private $definitions;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @ManyToMany(targetEntity="kateglo\application\models\Equivalent")
	 * @JoinTable(name="rel_equivalent_discipline",
	 * joinColumns={@JoinColumn(name="rel_discipline_id", referencedColumnName="discipline_id")},
	 * inverseJoinColumns={@JoinColumn(name="rel_equivalent_id", referencedColumnName="equivalent_id")}
	 * )
	 */
	protected $equivalents;
	
	public function __construct(){
		$this->definitions = new ArrayCollection ();
		$this->equivalents = new ArrayCollection ();
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return the $discipline
	 */
	public function getDiscipline() {
		return $this->discipline;
	}
	
	/**
	 * @param string $discipline
	 */
	public function setDiscipline($discipline) {
		$this->discipline = $discipline;
	}
	
	/**
	 * 
	 * @param kateglo\application\models\Definition $definition
	 * @return void
	 */	
	public function addDefinition(Definition $definition){
        if (!$this->definitions->contains($definition)) {
            $this->definitions[] = $definition;
            $definition->addDiscipline($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Definition $definition
     * @return void
     */
    public function removeDefinition(Definition $definition){
    	/*@var $removed kateglo\application\models\Definition */
        $removed = $this->definitions->removeElement($definition);
        if ($removed !== null) {
            $removed->removeDiscipline($this);
        }
    }

    /**
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getDefinitions(){
        return $this->definitions;
    }
    
	/**
	 * 
	 * @param kateglo\application\models\Equivalent $equivalent
	 * @return void
	 */	
	public function addEquivalent(Equivalent $equivalent){
        if (!$this->equivalents->contains($equivalent)) {
            $this->equivalents[] = $equivalent;
            $equivalent->addDiscipline($this);
        }
    }

    /**
     * 
     * @param kateglo\application\models\Equivalent $equivalent
     * @return void
     */
    public function removeEquivalent(Equivalent $equivalent){
    	/*@var $removed kateglo\application\models\Equivalent */
        $removed = $this->equivalents->removeElement($equivalent);
        if ($removed !== null) {
            $removed->removeDiscipline($this);
        }
    }

    /**
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getEquivalents(){
        return $this->equivalents;
    }
}

?>