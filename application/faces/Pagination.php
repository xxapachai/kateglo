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

use kateglo\application\utilities\collections\ArrayCollection;
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
class Pagination {
	
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
	 * @var kateglo\application\utilities\collections\ArrayCollection
	 */
	private $pages;
	
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
	 * @return kateglo\application\utilities\collections\ArrayCollection
	 */
	public function getPages() {
		return $this->pages;
	}

	/**
	 * @param kateglo\application\utilities\collections\ArrayCollection $pages
	 */
	public function setPages(ArrayCollection $pages) {
		$this->pages = $pages;
	}
}
?>