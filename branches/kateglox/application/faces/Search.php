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
use kateglo\application\helpers\HTTPMethod;
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
class Search implements interfaces\Search {

	public static $CLASS_NAME = __CLASS__;

	/**
	 *
	 * @var string
	 */
	private $fieldValue = '';

	/**
	 * Enter description here ...
	 * @var string
	 */
	private $formAction = '';

	/**
	 * Enter description here ...
	 * @var string
	 */
	private $filterUri;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	private $filters;

	/**
	 * @var string
	 */
	private $filterTypeUri;

	/**
	 * @var string
	 */
	private $filterClassUri;

	/**
	 * @var string
	 */
	private $filterSourceUri;

	/**
	 * @var string
	 */
	private $filterDisciplineUri;

	/**
	 * @var string
	 */
	private $filterTypeValue;

	/**
	 * @var string
	 */
	private $filterClassValue;

	/**
	 * @var string
	 */
	private $filterSourceValue;

	/**
	 * @var string
	 */
	private $filterDisciplineValue;

	/**
	 * @var string
	 */
	private $paginationUri;

	/**
	 * @var array
	 */
	private $filterQuery;

	/**
	 *
	 */
	public function __construct() {
		$this->filters = new ArrayCollection();
	}

	/**
	 * @return the $formAction
	 */
	public function getFormAction() {
		return $this->formAction;
	}

	/**
	 * @param string $formAction
	 */
	public function setFormAction($formAction) {
		$this->formAction = $formAction;
	}

	/**
	 *
	 * @return string
	 */
	public function getFormMethod() {
		return HTTPMethod::GET;
	}

	/**
	 *
	 * @return string
	 */
	public function getFieldName() {
		return "query";
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterName() {
		return "filter";
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterTypeValue() {
		return $this->filterTypeValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterClassValue() {
		return $this->filterClassValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterSourceValue() {
		return $this->filterSourceValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterDisciplineValue() {
		return $this->filterDisciplineValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterTypeUri() {
		return $this->filterTypeUri;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterClassUri() {
		return $this->filterClassUri;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterSourceUri() {
		return $this->filterSourceUri;
	}

	/**
	 *
	 * @return string
	 */
	public function getFilterDisciplineUri() {
		return $this->filterDisciplineUri;
	}

	/**
	 *
	 * @return string
	 */
	public function setFieldValue($fieldValue) {
		$this->fieldValue = $fieldValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getFieldValue() {
		return $this->fieldValue;
	}

	/**
	 *
	 * @return string
	 */
	public function getButtonValue() {
		return "Telusuri";
	}

	/**
	 * @param string $filterUri
	 */
	public function setFilterUri($filterUri) {
		$this->filterUri = $filterUri;
	}

	/**
	 * @return string
	 */
	public function getFilterUri() {
		return $this->filterUri;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $filters
	 */
	public function setFilters($filters) {
		$this->filters = $filters;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * @return string
	 */
	public function getPaginationUri() {
		return $this->paginationUri;
	}

	/**
	 * @return string
	 */
	public function getFilterQuery() {
		return $this->filterQuery;
	}

	/**
	 * @param $filterQuery
	 * @return void
	 */
	public function setFilterQuery($filterQuery) {
		$this->filterQuery = $filterQuery;
	}

	/**
	 * @return void
	 */
	public function createFilters() {
		$filters = array();
		$filtersMap = array();
		if (is_string($this->filterUri) && trim($this->filterUri) !== '') {
			$commaExplode = array_map('trim', explode(',', $this->filterUri));
			$countCommaExplode = count($commaExplode);
			for ($i = 0; $i < $countCommaExplode; $i++) {
				$filterUri = implode(',', array_slice($commaExplode, 0, $i + 1));
				$nameValExplode = array_map('trim', explode(':', $commaExplode[$i]));
				$filter = new Filter();
				$filter->setName($nameValExplode[0]);
				$filter->setValue($nameValExplode[1]);
				$filter->setUri($filterUri);
				$filters[] = $filter;
				$filtersMap[$nameValExplode[0]] = $filter;
			}

		}
		$this->filterTypeValue = array_key_exists('t', $filtersMap) ? $filtersMap['t']->getValue() : null;
		$this->filterClassValue = array_key_exists('c', $filtersMap) ? $filtersMap['c']->getValue() : null;
		$this->filterSourceValue = array_key_exists('s', $filtersMap) ? $filtersMap['s']->getValue() : null;
		$this->filterDisciplineValue = array_key_exists('d', $filtersMap) ? $filtersMap['d']->getValue() : null;
		$queryUri = $this->fieldValue != '' ? 'query=' . $this->fieldValue . '&filter=' : 'filter=';
		$typeUri = array();
		foreach ($filters as $filter) {
			if ($filter->getName() !== 't') {
				$explodeUri = explode(',', $filter->getUri());
				foreach ($explodeUri as $uri) {
					$explodeAgain = explode(':', $uri);
					if (!in_array($uri, $typeUri) && $explodeAgain[0] != 't')
						$typeUri[] = $uri;
				}
			}
		}
		$filterTypeUri = implode(',', $typeUri);
		if ($filterTypeUri != '') {
			$filterTypeUri .= ',';
		}
		$this->filterTypeUri = $queryUri . $filterTypeUri;
		$classUri = array();
		foreach ($filters as $filter) {
			if ($filter->getName() !== 'c') {
				$explodeUri = explode(',', $filter->getUri());
				foreach ($explodeUri as $uri) {
					$explodeAgain = explode(':', $uri);
					if (!in_array($uri, $classUri) && $explodeAgain[0] != 'c')
						$classUri[] = $uri;
				}
			}
		}
		$filterClassUri = implode(',', $classUri);
		if ($filterClassUri != '') {
			$filterClassUri .= ',';
		}
		$this->filterClassUri = $queryUri . $filterClassUri;
		$sourceUri = array();
		foreach ($filters as $filter) {
			if ($filter->getName() !== 's') {
				$explodeUri = explode(',', $filter->getUri());
				foreach ($explodeUri as $uri) {
					$explodeAgain = explode(':', $uri);
					if (!in_array($uri, $sourceUri) && $explodeAgain[0] != 's')
						$sourceUri[] = $uri;
				}
			}
		}
		$filterSourceUri = implode(',', $sourceUri);
		if ($filterSourceUri != '') {
			$filterSourceUri .= ',';
		}
		$this->filterSourceUri = $queryUri . $filterSourceUri;
		$disciplineUri = array();
		foreach ($filters as $filter) {
			if ($filter->getName() !== 'd') {
				$explodeUri = explode(',', $filter->getUri());
				foreach ($explodeUri as $uri) {
					$explodeAgain = explode(':', $uri);
					if (!in_array($uri, $disciplineUri) && $explodeAgain[0] != 'd')
						$disciplineUri[] = $uri;
				}
			}
		}
		$filterDisciplineUri = implode(',', $disciplineUri);
		if ($filterDisciplineUri != '') {
			$filterDisciplineUri .= ',';
		}
		$this->filterDisciplineUri = $queryUri . $filterDisciplineUri;
		$this->filters = new ArrayCollection($filters);
		$this->paginationUri = ($this->fieldValue != '' ? 'query=' . $this->fieldValue . '&' : '');
		$this->paginationUri = ($this->filterUri != '' ? $this->paginationUri . 'filter=' . $this->filterUri . '&'
				: $this->paginationUri);

		$filterQueryArray = array();
		$filterUriArray = array();
		foreach ($filters as $filter) {
			switch ($filter->getName()) {
				case 't':
					$filterQueryArray[] = 'typeExact:"' . $filter->getValue() . '"';
					$filterUriArray[] = 't:' . $filter->getValue();
					break;
				case 'c':
					$filterQueryArray[] = 'classExact:"' . $filter->getValue() . '"';
					$filterUriArray[] = 'c:' . $filter->getValue();
					break;
				case 's':
					$filterQueryArray[] = 'sourceExact:"' . $filter->getValue() . '"';
					$filterUriArray[] = 's:' . $filter->getValue();
					break;
				case 'd':
					$filterQueryArray[] = 'disciplineExact:"' . $filter->getValue() . '"';
					$filterUriArray[] = 'd:' . $filter->getValue();
					break;
			}
			$filter->setUri($queryUri . implode(',', $filterUriArray));
		}
		$this->filterQuery = implode(' ', $filterQueryArray);
	}
}

?>