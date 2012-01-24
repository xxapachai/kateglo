<?php
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
use kateglo\application\controllers\exceptions\HTTPBadRequestException;
use kateglo\application\controllers\exceptions\HTTPNotFoundException;
use kateglo\application\services;
use kateglo\application\models;

/**
 *
 *
 * @package kateglo\application\controllers
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Cpanel_EntriController extends Zend_Controller_Action_Stubbles
{

    /**
     *
     * Enter description here ...
     * @var kateglo\application\services\interfaces\Entry;
     */
    private $entry;

    /**
     *
     * Enter description here ...
     * @var kateglo\application\services\interfaces\Meaning;
     */
    private $meaning;

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\Entry $entry
     *
     * @Inject
     */
    public function setEntry(services\interfaces\Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\Meaning $meaning
     *
     * @Inject
     */
    public function setMeaning(services\interfaces\Meaning $meaning)
    {
        $this->meaning = $meaning;
    }

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @var int $id
     * @return void
     * @Get
     * @Path('/id/{entryId}')
     * @PathParam{id}(entryId)
     * @Produces('application/json')
     */
    public function getById($id)
    {
        $cacheId = __METHOD__ . '\\' . $id;
        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $entry = $this->entry->getEntryById($id);
                $this->content = $entry->toArray();
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->_helper->json($this->content);
    }

    /**
     * @var string $requestEntry
     * @return void
     * @Put
     * @Path('/')
     * @Produces('application/json')
     * @Consumes('application/json')
     * @ConsumeParam{requestEntry}
     */
    public function insert($requestEntry)
    {
        $entryObj = json_decode($requestEntry);
        if ($entryObj !== null) {
            if (property_exists($entryObj, 'entry')) {
                $entry = new models\Entry();
                $entry->setEntry($entryObj->entry);
                $entry = $this->entry->insert($entry);
                $this->_helper->json($entry->toArray());
            } else {
                throw new HTTPBadRequestException('Property not found');
            }
        } else {
            throw new HTTPBadRequestException('Invalid JSON');
        }
    }

    /**
     * @var string $requestEntry
     * @return void
     * @Post
     * @Path('/')
     * @Produces('application/json')
     * @Consumes('application/json')
     * @ConsumeParam{requestEntry}
     */
    public function update($requestEntry)
    {
        $entryObj = json_decode($requestEntry);
        if ($entryObj !== null) {
            if (property_exists($entryObj, 'id') && property_exists($entryObj, 'version') &&
                property_exists($entryObj, 'entry')
            ) {
                $entry = new models\Entry();
                $entry->setEntry($entryObj->entry);
                $entry->setVersion($entryObj->version);
                $entry->setId($entryObj->id);
                $entry = $this->entry->update($entry);
                $this->_helper->json($entry->toArray());
            } else {
                throw new HTTPBadRequestException('Property not found');
            }
        } else {
            throw new HTTPBadRequestException('Invalid JSON');
        }
    }

    /**
     * @var int $id
     * @return void
     * @Delete
     * @Path('/id/{entryId}')
     * @PathParam{id}(entryId)
     * @Produces('application/json')
     */
    public function delete($id)
    {
        if ($id !== null && is_numeric($id)) {
            $this->entry->delete(intval($id));
            $this->_helper->json(array());
        } else {
            throw new HTTPBadRequestException('Undefined Identity');
        }
    }

    /**
     * @var string $requestEntry
     * @return void
     * @Put
     * @Path('/meaning/{meaningId}/types')
     * @PathParam{id}(meaningId)
     * @Produces('application/json')
     * @Consumes('application/json')
     * @ConsumeParam{requestEntry}
     */
    public function types($id, $requestEntry)
    {
        if ($id !== null && is_numeric($id)) {
            $entryObj = json_decode($requestEntry);
            if ($entryObj !== null) {
                if (property_exists($entryObj, 'id') && property_exists($entryObj, 'version') &&
                    property_exists($entryObj, 'types')
                ) {
                    $meaning = $this->meaning->updateTypes($id, $entryObj->version, $entryObj->types);
                    $this->_helper->json($meaning->toArray());
                } else {
                    throw new HTTPBadRequestException('Property not found');
                }
            } else {
                throw new HTTPBadRequestException('Invalid JSON');
            }
        } else {
            throw new HTTPBadRequestException('Undefined Identity');
        }
    }

}

?>