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

use kateglo\application\daos;
use kateglo\application\models\front;
use kateglo\application\models\front\Filter;
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
class Search implements interfaces\Search
{

    public static $CLASS_NAME = __CLASS__;

    /**
     *
     * @var \kateglo\application\daos\interfaces\Search
     */
    private $search;

    /**
     *
     * @param \kateglo\application\daos\interfaces\Search
     * @return void
     *
     * @Inject
     */
    public function setSearch(daos\interfaces\Search $search) {
        $this->search = $search;
    }

    /**
     * @return \kateglo\applications\models\solr\Amount
     */
    public function getAmount() {
        return $this->search->getAmount();
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function entry($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        return $this->search->entry($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function thesaurus($searchText, front\Pagination $pagination, front\Facet $facet = null) {
         return $this->search->thesaurus($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function equivalent($searchText, front\Pagination $pagination, front\Facet $facet = null) {
        return $this->search->equivalent($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function proverb($searchText, front\Pagination $pagination, front\Facet $facet = null) {
         return $this->search->proverb($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function acronym($searchText, front\Pagination $pagination, front\Facet $facet = null) {
         return $this->search->acronym($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param string $alphabet
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function alphabet($searchText, $alphabet, front\Pagination $pagination, front\Facet $facet = null) {
         return $this->search->alphabet($searchText, $alphabet, $pagination, $facet);
    }
}

?>