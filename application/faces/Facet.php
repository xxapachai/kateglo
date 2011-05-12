<?php
namespace kateglo\application\faces;
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
 * @package kateglo\application\faces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Facet {

    /**
     *
     * Enter description here ...
     * @var string
     */
    const TYPE = 'typeExact';

    /**
     *
     * Enter description here ...
     * @var string
     */
    const TYPE_CATEGORY = 'typeCategoryExact';

    /**
     *
     * Enter description here ...
     * @var string
     */
    const CLAZZ = 'classExact';

    /**
     *
     * Enter description here ...
     * @var string
     */
    const CLAZZ_CATEGORY = 'classCategoryExact';

    /**
     *
     * Enter description here ...
     * @var string
     */
    const DISCIPLINE = 'disciplineExact';

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $type;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $typeCategory;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $clazz;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $clazzCategory;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $discipline;

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $clazz
     * @return void
     */
    public function setClazz($clazz) {
        $this->clazz = $clazz;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClazz() {
        return $this->clazz;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $clazzCategory
     * @return void
     */
    public function setClazzCategory($clazzCategory) {
        $this->clazzCategory = $clazzCategory;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getClazzCategory() {
        return $this->clazzCategory;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $type
     * @return void
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $typeCategory
     * @return void
     */
    public function setTypeCategory($typeCategory) {
        $this->typeCategory = $typeCategory;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypeCategory() {
        return $this->typeCategory;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $discipline
     * @return void
     */
    public function setDiscipline($discipline) {
        $this->discipline = $discipline;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDiscipline() {
        return $this->discipline;
    }
}

?>