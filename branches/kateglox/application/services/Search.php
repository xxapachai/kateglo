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
     * @var \kateglo\application\daos\interfaces\Entry
     */
    private $entry;

    /**
     *
     * @param \kateglo\application\daos\interfaces\Search
     * @return void
     *
     * @Inject
     */
    public function setSearch(daos\interfaces\Search $search)
    {
        $this->search = $search;
    }

    /**
     *
     * @param \kateglo\application\daos\interfaces\Search
     * @return void
     *
     * @Inject
     */
    public function setEntry(daos\interfaces\Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return \kateglo\applications\models\solr\Amount
     */
    public function getAmount()
    {
        return $this->search->getAmount();
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function entry($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->entry($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function thesaurus($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->thesaurus($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function equivalent($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->equivalent($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function proverb($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->proverb($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function acronym($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->acronym($searchText, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param string $alphabet
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    public function alphabet($searchText, $alphabet, front\Pagination $pagination, front\Facet $facet = null)
    {
        return $this->search->alphabet($searchText, $alphabet, $pagination, $facet);
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function source($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        /*$hits = $this->search->source($searchText, $pagination, $facet);
        $documents = $hits->getDocuments();*/
        $entryIds = array();
        /** @var $document \kateglo\application\models\solr\Document */
        /*foreach ($documents as $document) {
            $entryIds[] = $document->getId();
        }
        $sources = new ArrayCollection();
        if (count($entryIds) > 0) {*/
            $sources = $this->entry->getSourceFromEntryIds($entryIds);
        /*}
        return $sources;*/
    }

    /**
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function foreign($searchText, front\Pagination $pagination, front\Facet $facet = null)
    {
        $hits = $this->search->foreign($searchText, $pagination, $facet);
        $documents = $hits->getDocuments();
        $entryIds = array();
        /** @var $document \kateglo\application\models\solr\Document */
        foreach ($documents as $document) {
            $entryIds[] = $document->getId();
        }
        $foreigns = new ArrayCollection();
        if (count($entryIds) > 0) {
            $foreigns = $this->entry->getForeignFromEntryIds($entryIds);
        }
        return $foreigns;
    }

    /**
     * @param int $limit
     * @return \kateglo\application\models\solr\Hit
     */
    public function randomMisspelled($limit = 6)
    {
        return $this->search->randomMisspelled($limit);
    }

    /**
     * @param int $limit
     * @return \kateglo\application\models\solr\Hit
     */
    public function randomEntry($limit = 5)
    {
        return $this->search->randomEntry($limit);
    }
}

?>