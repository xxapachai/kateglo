<?php
namespace kateglo\application\models\front;
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
 * @package kateglo\application\models\front
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Page
{

    /**
     * Enter description here ...
     * @var int
     */
    private $page;

    /**
     * Enter description here ...
     * @var int
     */
    private $start;

    /**
     * Enter description here ...
     * @var string
     */
    private $text;

    /**
     * Enter description here ...
     * @var boolean
     */
    private $current;

    /**
     * Enter description here ...
     * @param int $page
     * @param int $start
     * @param string $text
     */
    function __construct($page, $start, $text, $current) {
        $this->page = $page;
        $this->start = $start;
        $this->text = $text;
        $this->current = $current;
    }

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
                foreach($elements as $item){
                    if (is_object($item)) {
                        $array[$key][] = $item->toArray();
                    }
                    else if (is_array($item)) {
                        $array[$key][] = $this->processArray($item);
                    }else{
                        $array[$key][] = $item;
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
     * @return int $page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * @return int $start
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart($start) {
        $this->start = $start;
    }

    /**
     * @return string $text
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return boolean $current
     */
    public function isCurrent() {
        return $this->current;
    }

    /**
     * @param boolean $current
     */
    public function setCurrent($current) {
        $this->current = $current;
    }


}

?>