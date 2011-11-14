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
use Doctrine\Common\Collections\ArrayCollection;
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
class Pagination
{

    /**
     * Enter description here ...
     * @var int
     */
    private $offset;

    /**
     * Enter description here ...
     * @var int
     */
    private $limit;

    /**
     * Enter description here ...
     * @var int
     */
    private $amount;

    /**
     * Enter description here ...
     * @var int
     */
    private $pageRange = 10;

    /**
     * Enter description here ...
     * @var int
     */
    private $pageAmount;

    /**
     * Enter description here ...
     * @var int
     */
    private $currentPage;

    /**
     * Enter description here ...
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $pages;

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
     * @return int $pageRange
     */
    public function getPageRange() {
        return $this->pageRange;
    }

    /**
     * @param int $pageRange
     */
    public function setPageRange($pageRange) {
        $this->pageRange = $pageRange;
    }

    /**
     * @return int $amount
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return int $limit
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * @return int $offset
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset) {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getPageAmount() {
        return $this->pageAmount;
    }

    /**
     * @param int $pageAmount
     */
    public function setPageAmount($pageAmount) {
        $this->pageAmount = $pageAmount;
    }

    /**
     * @return int
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    /**
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getPages() {
        return $this->pages;
    }

    /**
     * @param Doctrine\Common\Collections\ArrayCollection $pages
     */
    public function setPages(ArrayCollection $pages) {
        $this->pages = $pages;
    }
}

?>