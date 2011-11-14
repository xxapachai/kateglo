<?php
namespace kateglo\application\models\solr;
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
 * @package kateglo\application\models\solr
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Document
{

    /**
     * Enter description here ...
     * @var int
     */
    private $id;

    /**
     * Enter description here ...
     * @var string
     */
    private $entry;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $antonyms;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $definitions;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $classes;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $classCategories;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $disciplines;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $samples;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $misspelleds;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $relations;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $synonyms;

    /**
     * Enter description here ...
     * @var string
     */
    private $spelled;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $syllabels;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $types;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $typeCategories;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $source;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $sourceCategories;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $languages;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $equivalentDisciplines;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $foreigns;

    /**
     * Enter description here ...
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $equivalents;

    /**
     * Enter description here ...
     * @var \kateglo\application\faces\Hit
     */
    private $moreLikeThis;

    /**
     * @return array
     */
    public function toArray() {
        return $this->processArray(get_object_vars($this));
    }

    /**
     * @param array $array
     * @return array
     */
    private function processArray($array) {
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $value->toArray();
            }
            if (is_array($value)) {
                $array[$key] = $this->processArray($value);
            }
            if ($value instanceof ArrayCollection) {
                $array[$key] = array();
                $elements = $value->toArray();
                foreach($elements as $key2 => $item){
                    if (is_object($item)) {
                        $array[$key][$key2] = $item->toArray();
                    }
                    else if (is_array($item)) {
                        $array[$key][$key2] = $this->processArray($item);
                    }else{
                        $array[$key][$key2] = $item;
                    }
                }
            }
        }
        // If the property isn't an object or array, leave it untouched
        return $array;
    }

    /**
     * @return string
     */
    public function __toString() {
        return json_encode($this->toArray());
    }

    /**
     * @return the $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEntry() {
        return $this->entry;
    }

    /**
     * @param string $entry
     */
    public function setEntry($entry) {
        $this->entry = $entry;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getAntonyms() {
        return $this->antonyms;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $antonyms
     */
    public function setAntonyms($antonyms) {
        $this->antonyms = $antonyms;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getDefinitions() {
        return $this->definitions;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $definitions
     */
    public function setDefinitions($definitions) {
        $this->definitions = $definitions;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getClassCategories() {
        return $this->classCategories;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $classCategories
     */
    public function setClassCategories($classCategories) {
        $this->classCategories = $classCategories;
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

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSamples() {
        return $this->samples;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $samples
     */
    public function setSamples($samples) {
        $this->samples = $samples;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getMisspelleds() {
        return $this->misspelleds;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $misspelleds
     */
    public function setMisspelleds($misspelleds) {
        $this->misspelleds = $misspelleds;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getRelations() {
        return $this->relations;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $relations
     */
    public function setRelations($relations) {
        $this->relations = $relations;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSynonyms() {
        return $this->synonyms;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $synonyms
     */
    public function setSynonyms($synonyms) {
        $this->synonyms = $synonyms;
    }

    /**
     * @return string
     */
    public function getSpelled() {
        return $this->spelled;
    }

    /**
     * @param string $spelleds
     */
    public function setSpelled($spelled) {
        $this->spelled = $spelled;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSyllabels() {
        return $this->syllabels;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $syllabels
     */
    public function setSyllabels($syllabels) {
        $this->syllabels = $syllabels;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypes() {
        return $this->types;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $types
     */
    public function setTypes($types) {
        $this->types = $types;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTypeCategories() {
        return $this->typeCategories;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $typeCategories
     */
    public function setTypeCategories($typeCategories) {
        $this->typeCategories = $typeCategories;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $source
     */
    public function setSource($source) {
        $this->source = $source;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getSourceCategories() {
        return $this->sourceCategories;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $sourceCategories
     */
    public function setSourceCategories($sourceCategories) {
        $this->sourceCategories = $sourceCategories;
    }

    /**
     * @return the $languages
     */
    public function getLanguages() {
        return $this->languages;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $languages
     */
    public function setLanguages($languages) {
        $this->languages = $languages;
    }

    /**
     * @return the $equivalentDisciplines
     */
    public function getEquivalentDisciplines() {
        return $this->equivalentDisciplines;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $equivalentDisciplines
     */
    public function setEquivalentDisciplines($equivalentDisciplines) {
        $this->equivalentDisciplines = $equivalentDisciplines;
    }

    /**
     * @return the $foreigns
     */
    public function getForeigns() {
        return $this->foreigns;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $foreigns
     */
    public function setForeigns($foreigns) {
        $this->foreigns = $foreigns;
    }

    /**
     * @return the $equivalents
     */
    public function getEquivalentsAsArray() {
        return json_decode($this->equivalents);
    }

    /**
     * @return the $equivalents
     */
    public function getEquivalents() {
        return $this->equivalents;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $equivalents
     */
    public function setEquivalents($equivalents) {
        $this->equivalents = $equivalents;
    }

    /**
     * @param \kateglo\application\faces\Hit $moreLikeThis
     * @return void
     */
    public function setMoreLikeThis($moreLikeThis) {
        $this->moreLikeThis = $moreLikeThis;
    }

    /**
     * @return \kateglo\application\faces\Hit
     */
    public function getMoreLikeThis() {
        return $this->moreLikeThis;
    }

}

?>