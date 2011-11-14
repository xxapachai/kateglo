<?php
namespace kateglo\application\services;
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
/** @noinspection PhpUndefinedNamespaceInspection */
use kateglo\application\models\front;
use kateglo\application\models\front\Page;
use Doctrine\Common\Collections\ArrayCollection;
/**
 *
 *
 * @package kateglo\application\services
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Pagination implements interfaces\Pagination
{

    public static $CLASS_NAME = __CLASS__;

    /**
     * @param $amount
     * @param \kateglo\application\models\front\Pagination $pagination
     * @return void
     */
    public function create(front\Pagination $pagination) {
        $this->doCreate($pagination);
    }

    /**
     * @param \kateglo\application\models\front\Pagination $pagination
     * @return array
     */
    public function createAsArray(front\Pagination $pagination) {
        $this->doCreate($pagination);
        return $this->convert2Array($pagination);
    }

    /**
     * Enter description here ...
     * @param int $amount
     * @param int $limit
     * @return int
     */
    public function pageAmount($amount, $limit) {
        return ( int )(ceil($amount / $limit));
    }

    /**
     * Enter description here ...
     * @param int $offset
     * @param int $limit
     * @return int
     */
    public function currentPage($offset, $limit) {
        return ( int )(floor($offset / $limit) + 1);
    }

    /**
     * @param \kateglo\application\models\front\Pagination $pagination
     * @return void
     */
    private function doCreate(front\Pagination $pagination) {
        $pageCollection = new ArrayCollection ();

        $pagination->setCurrentPage($this->currentPage($pagination->getOffset(), $pagination->getLimit()));
        $pagination->setPageAmount($this->pageAmount($pagination->getAmount(), $pagination->getLimit()));

        //worth to process?
        if ($pagination->getPageAmount() > 1) {

            //compensates for small data sets
            $range = min($pagination->getPageAmount(), $pagination->getPageRange());

            //Calculate Range
            $rangeMin = null;
            $rangeMax = null;
            if ($range % 2 === 0) {
                $rangeMin = floor($range / 2) - 1;
                $rangeMax = $rangeMin + 1;
            } else {
                $rangeMin = floor(($range - 1) / 2);
                $rangeMax = $rangeMin;
            }

            //Calculate Pages
            $pageMin = null;
            $pageMax = null;
            if ($pagination->getCurrentPage() < ($rangeMax + 1)) {
                $pageMin = 1;
                $pageMax = $range;
            } else {
                $pageMin = min(($pagination->getCurrentPage() - $rangeMin), ($pagination->getPageAmount() - ($range - 1)));
                $pageMax = min(($pagination->getCurrentPage() + $rangeMax), $pagination->getPageAmount());
            }

            //Start Create pagination
            $page = 0;
            $start = 0;
            if ($pageMin > 1) { //create at least Prev Page element
                if ($pageMin > 2) { //create First Page element
                    $page = 1;
                    $pageObject = new Page ($page, $start, html_entity_decode('&laquo; Pertama', ENT_QUOTES, 'UTF-8'), false);
                    $pageCollection [] = $pageObject;
                }
                $page = $pageMin - 1;
                $start = ($pageMin - 1) * $pagination->getLimit();
                $pageObject = new Page ($page, $start, html_entity_decode('&lsaquo; Sebelumnya', ENT_QUOTES, 'UTF-8'), false);
                $pageCollection [] = $pageObject;
            }
            for ($i = ( int )$pageMin; $i <= ( int )$pageMax; $i++) { //create numbered Page Elements
                if ($i === $pagination->getCurrentPage()) { //current Page elements
                    $page = $i;
                    $start = ($page - 1) * $pagination->getLimit();
                    $pageObject = new Page ($page, $start, ( string )$page, true);
                    $pageCollection [] = $pageObject;
                } else {
                    $page = $i;
                    $start = ($page - 1) * $pagination->getLimit();
                    $pageObject = new Page ($page, $start, ( string )$page, false);
                    $pageCollection [] = $pageObject;
                }
            }
            if ($pageMax < $pagination->getPageAmount()) { //create Next Page Element
                $page = $pageMax + 1;
                $start = ($page - 1) * $pagination->getLimit();
                $pageObject = new Page ($page, $start, html_entity_decode('Berikutnya &rsaquo;', ENT_QUOTES, 'UTF-8'), false);
                $pageCollection [] = $pageObject;
                if ($page < $pagination->getPageAmount()) { //create Last Page Element
                    $page = $pagination->getPageAmount();
                    $start = ($page - 1) * $pagination->getLimit();
                    $pageObject = new Page ($page, $start, html_entity_decode('Terakhir &raquo;', ENT_QUOTES, 'UTF-8'), false);
                    $pageCollection [] = $pageObject;
                }
            }
        }

        $pagination->setPages($pageCollection);
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\faces\Pagination $pagination
     * @return array
     */
    private function convert2Array(faces\Pagination $pagination) {
        $paginationArray = array();
        $paginationArray ['offset'] = $pagination->getOffset();
        $paginationArray ['limit'] = $pagination->getLimit();
        $paginationArray ['amount'] = $pagination->getAmount();
        $paginationArray ['pageRange'] = $pagination->getPageRange();
        $paginationArray ['pageAmount'] = $pagination->getPageAmount();
        $paginationArray ['currentPage'] = $pagination->getCurrentPage();
        $pages = array();
        /* @var $page kateglo\application\faces\Page */
        foreach ($pagination->getPages() as $page) {
            $pageArray ['page'] = $page->getPage();
            $pageArray ['start'] = $page->getStart();
            $pageArray ['text'] = $page->getText();
            $pageArray ['current'] = $page->isCurrent();
            $pages [] = $pageArray;
        }
        $paginationArray ['pages'] = $pages;
        return $paginationArray;
    }
}

?>