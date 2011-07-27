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
interface Entry {

	const INTERFACE_NAME = __CLASS__;

	/**
	 *
	 * @param string $entry
	 * @return kateglo\application\models\Entry
	 */
	function getByEntry($entry);

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getTypes();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getTypeCategories();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getClasses();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getClassCategories();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getSourceCategories();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getDisciplines();

	/**
	 * Enter description here ...
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getLanguages();

	/**
	 * Enter description here ...
	 * @param $entries \Doctrine\Common\Collections\ArrayCollection
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getMeanings($entries);

	/**
	 * Enter description here ...
	 * @param $foreigns \Doctrine\Common\Collections\ArrayCollection
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	function getForeigns($foreigns);

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	function update(models\Entry $entry);

	/**
	 * @param \kateglo\application\models\Entry $entry
	 * @return \kateglo\application\models\Entry
	 */
	function insert(models\Entry $entry);

	/**
	 * @param int id
	 * @return \kateglo\application\models\Entry
	 */
	function delete($id);

}

?>