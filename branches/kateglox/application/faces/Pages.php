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
use kateglo\application\helpers;
use kateglo\application\utilities\collections\ArrayCollection;
use kateglo\application\faces\Page;
/**
 * 
 * 
 * @package kateglo\application\faces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2010-12-16 21:41:15 +0100 (Do, 16 Dez 2010) $
 * @version $LastChangedRevision: 265 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Pages implements interfaces\Pages {
	
	public static $CLASS_NAME = __CLASS__;
	
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
	 * Enter description here ...
	 * @return int
	 */
	public function getPageAmount() {
		return  (int)(ceil ( $this->amount / $this->limit ));
	}
	
	/**
	 * Enter description here ...
	 * @return int
	 */
	public function getCurrentPage() {
		return ( int ) (floor ( $this->offset / $this->limit ) + 1);
	}
	
	/**
	 * Enter description here ...
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getPagination() {
		$pageCollection = new ArrayCollection ();
		
		//worth to process?
		if ($this->getPageAmount () > 1) {
			
			//compensates for small data sets
			$range = min ( $this->getPageAmount (), $this->pageRange );
			
			//Calculate Range
			$rangeMin = null;
			$rangeMax = null;
			if ($range % 2 === 0) {
				$rangeMin = floor ( $range / 2 ) - 1;
				$rangeMax = $rangeMin + 1;
			} else {
				$rangeMin = floor ( ($range - 1) / 2 );
				$rangeMax = $rangeMin;
			}
			
			//Calculate Pages
			$pageMin = null;
			$pageMax = null;
			if ($this->getCurrentPage () < ($rangeMax + 1)) {
				$pageMin = 1;
				$pageMax = $range;
			} else {
				$pageMin = min ( ($this->getCurrentPage () - $rangeMin), ($this->getPageAmount () - ($range - 1)) );
				$pageMax = min ( ($this->getCurrentPage () + $rangeMax), $this->getPageAmount () );
			}
			
			//Start Create pagination
			$page = 0;
			$start = 0;
			if ($pageMin > 1) { //create at least Prev Page element
				if ($pageMin > 2) { //create First Page element
					$page = 1;
					$pageObject = new Page ( $page, $start, 'First', false );
					$pageCollection [] = $pageObject;
				}
				$page = $pageMin - 1;
				$start = ($pageMin - 1) * $this->limit;
				$pageObject = new Page ( $page, $start, 'Prev', false );
				$pageCollection [] = $pageObject;
			}
			for($i = ( int ) $pageMin; $i <= ( int ) $pageMax; $i ++) { //create numbered Page Elements
				if ($i === $this->getCurrentPage ()) { //current Page elements
					$page = $i;
					$start = ($page - 1) * $this->limit;
					$pageObject = new Page ( $page, $start, ( string ) $page, true );
					$pageCollection [] = $pageObject;
				} else {
					$page = $i;
					$start = ($page - 1) * $this->limit;
					$pageObject = new Page ( $page, $start, ( string ) $page, false );
					$pageCollection [] = $pageObject;
				}
			}
			if ($pageMax < $this->getPageAmount ()) { //create Next Page Element
				$page = $pageMax + 1;
				$start = ($page - 1) * $this->limit;
				$pageObject = new Page ( $page, $start, 'Next', false );
				$pageCollection [] = $pageObject;
				if ($page < $this->getPageAmount ()) { //create Last Page Element
					$page = $this->getPageAmount ();
					$start = ($page - 1) * $this->limit;
					$pageObject = new Page ( $page, $start, 'Last', false );
					$pageCollection [] = $pageObject;
				}
			}
		}
		
		return $pageCollection;
	}
	
	/**
	 * Enter description here ...
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getAllPages() {
		$pageCollection = new ArrayCollection ();
		$start = 0;
		for($i = 1; $i <= $this->getPageAmount (); $i ++) {
			$start = ($i - 1) * $this->limit;
			$pageObject = new Page ( $i, $start, ( string ) $i );
			$pageCollection [] = $pageObject;
		}
		
		return $pageCollection;
	}
}
?>