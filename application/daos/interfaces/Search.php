<?php
namespace kateglo\application\daos\interfaces;
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
use kateglo\application\models\front;
use kateglo\application\models;

/**
 *
 *
 * @package kateglo\application\daos\interfaces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
interface Search
{

    const INTERFACE_NAME = __CLASS__;


    /**
     * @return \kateglo\application\models\solr\Amount
     */
    function getAmount();

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function entry($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function thesaurus($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function equivalent($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function proverb($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function acronym($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param string $alphabet
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \kateglo\application\models\solr\Hit
     */
    function alphabet($searchText, $alphabet, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function source($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param string $searchText
     * @param \kateglo\application\models\front\Pagination $pagination
     * @param \kateglo\application\models\front\Facet|null $facet
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function foreign($searchText, front\Pagination $pagination, front\Facet $facet = null);

    /**
     * @abstract
     * @param int $limit
     * @return \kateglo\application\models\solr\Hit
     */
    function randomMisspelled($limit = 6);

    /**
     * @abstract
     * @param int $limit
     * @return \kateglo\application\models\solr\Hit
     */
    function randomEntry($limit = 5);

    /**
     * @abstract
     * @param \kateglo\application\models\Entry $entry
     * @return void
     */
    function update(models\Entry $entry);

    /**
     * @abstract
     * @param \kateglo\application\models\Entry $entry
     * @return void
     */
    function insert(models\Entry $entry);

    /**
     * @abstract
     * @param int $id
     * @return \kateglo\application\models\Entry
     */
    function delete($id);

}

?>