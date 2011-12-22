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
use kateglo\application\services\interfaces;
use kateglo\application\models;
use kateglo\application\controllers\exceptions\HTTPNotFoundException;

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
class EntriController extends Zend_Controller_Action_Stubbles
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
     * @var kateglo\application\services\interfaces\WordOfTheDay;
     */
    private $wotd;

    /**
     *
     * Enter description here ...
     * @var \kateglo\application\services\interfaces\StaticData;
     */
    private $staticData;

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\Entry $entry
     *
     * @Inject
     */
    public function setEntry(interfaces\Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\WordOfTheDay $wotd
     *
     * @Inject
     */
    public function setWotd(interfaces\WordOfTheDay $wotd)
    {
        $this->wotd = $wotd;
    }

    /**
     *
     * Enter description here ...
     * @param kateglo\application\services\interfaces\StaticData $staticData
     *
     * @Inject
     */
    public function setStaticData(interfaces\StaticData $staticData)
    {
        $this->staticData = $staticData;
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
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}')
     * @PathParam{text}(entry)
     * @Produces('text/html')
     */
    public function indexHtml($text)
    {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __METHOD__ . '\\' . $text;
        $search = new kateglo\application\models\front\Search();

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $search->setFieldValue($text);
                $search->setFormAction('/kamus');
                $entryObj = $this->entry->getEntry($text);
                $entry = $entryObj->toArray();
                $countMeaning = count($entry['meanings']);
                for ($i = 0; $i < $countMeaning; $i++) {
                    $index = 0;
                    $meaning = $entry['meanings'][$i];
                    $classes = array();
                    $classNames = array();
                    $countDefinition = count($meaning['definitions']);
                    for ($j = 0; $j < $countDefinition; $j++) {
                        $definition = $meaning['definitions'][$j];
                        if (array_key_exists('class', $definition)) {
                            $classObj = $definition['class'];
                            $className = $definition['class']['class'];
                            if (in_array($className, $classNames)) {
                                $classNameIndex = array_search($className, $classNames);
                                $classes[$classNameIndex]['definitions'][] = $definition;
                            } else {
                                $classNames[] = $className;
                                $preparedArray = array();
                                $preparedArray['class'] = $classObj;
                                $preparedArray['definitions'] = array();
                                $preparedArray['definitions'][] = $definition;
                                $classes[] = $preparedArray;
                            }
                        } else {
                            if (array_key_exists('', $classes)) {
                                $classNameIndex = array_search($className, $classNames);
                                $classes[$classNameIndex]['definitions'][] = $definition;
                            } else {
                                $classNames[] = '';
                                $preparedArray = array();
                                $preparedArray['definitions'] = array();
                                $preparedArray['definitions'][] = $definition;
                                $classes[] = $preparedArray;
                            }
                        }
                    }
                    for ($k = 0; $k < count($classes); $k++) {
                        for ($l = 0; $l < count($classes[$k]['definitions']); $l++) {
                            $classes[$k]['definitions'][$l]['index'] = $index + 1;
                            $index++;
                        }
                    }
                    $entry['meanings'][$i]['classes'] = $classes;
                }
                $jsonEncode = json_encode($entry);
                $entry = json_decode($jsonEncode);
                $this->view->search = $search;
                $this->view->entry = $entry;
                $this->content = $this->_helper->viewRenderer->view->render('entri/index.html');
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->getResponse()->appendBody($this->content);
    }

    /**
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}/tesaurus')
     * @PathParam{text}(entry)
     * @Produces('text/html')
     */
    public function thesaurusHtml($text)
    {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __METHOD__ . '\\' . $text;
        $search = new kateglo\application\models\front\Search();

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $search->setFieldValue($text);
                $search->setFormAction('/kamus');
                /** @var $entry \kateglo\application\models\Entry */
                $entry = $this->entry->getEntry($text);
                $entryArray = array();
                $entryArray['entry'] = $entry->getEntry();
                $entryArray['synonyms'] = array();
                $entryArray['antonyms'] = array();
                $entryArray['relations'] = array();
                /** @var $meaning \kateglo\application\models\Meaning */
                foreach ($entry->getMeanings() as $meaning) {
                    /** @var $antonym \kateglo\application\models\Antonym */
                    foreach ($meaning->getAntonyms() as $antonym) {
                        if (!in_array($antonym->getAntonym()->getEntry()->getEntry(), $entryArray['antonyms'])) {
                            $entryArray['antonyms'][] = $antonym->getAntonym()->getEntry()->getEntry();
                        }
                    }
                    /** @var $synonym \kateglo\application\models\Synonym */
                    foreach ($meaning->getSynonyms() as $synonym) {
                        if (!in_array($synonym->getSynonym()->getEntry()->getEntry(), $entryArray['synonyms'])) {
                            $entryArray['synonyms'][] = $synonym->getSynonym()->getEntry()->getEntry();
                        }
                    }
                    /** @var $relation \kateglo\application\models\Relation */
                    foreach ($meaning->getRelations() as $relation) {
                        if (!in_array($relation->getRelation()->getEntry()->getEntry(), $entryArray['relations'])) {
                            $entryArray['relations'][] = $relation->getRelation()->getEntry()->getEntry();
                        }
                    }
                }

                $jsonEncode = json_encode($entryArray);
                $entry = json_decode($jsonEncode);
                $this->view->search = $search;
                $this->view->entry = $entry;
                $this->content = $this->_helper->viewRenderer->view->render('entri/thesaurus.html');
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->getResponse()->appendBody($this->content);
    }

    /**
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}/padanan')
     * @PathParam{text}(entry)
     * @Produces('text/html')
     */
    public function equivalentHtml($text)
    {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __METHOD__ . '\\' . $text;
        $search = new kateglo\application\models\front\Search();

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $search->setFieldValue($text);
                $search->setFormAction('/kamus');
                $entryObj = $this->entry->getEntry($text);
                $entry = $entryObj->toArray();
                $static = $this->staticData->getLanguages();
                $jsonEncode = json_encode($entry);
                $entry = json_decode($jsonEncode);
                $this->view->search = $search;
                $this->view->entry = $entry;
                $this->view->static = $static;
                $this->view->lang = '';
                $this->content = $this->_helper->viewRenderer->view->render('entri/equivalent.html');
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->getResponse()->appendBody($this->content);
    }

    /**
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}/padanan/{language}')
     * @PathParam{text}(entry)
     * @PathParam{language}(language)
     * @Produces('text/html')
     */
    public function equivalentLanguageHtml($text, $language)
    {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __METHOD__ . '\\' . $text . '\\' . $language;
        $search = new kateglo\application\models\front\Search();

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $search->setFieldValue($text);
                $search->setFormAction('/kamus');
                $entryObj = $this->entry->getEntry($text);
                $entry = $entryObj->toArray();
                $static = $this->staticData->getLanguages();
                $jsonEncode = json_encode($entry);
                $jsonDecode = json_decode($jsonEncode);
                $newEquivalents = array();
                foreach ($jsonDecode->equivalents as $equivalent) {
                    if (strtolower($equivalent->foreign->language->language) == $language) {
                        $newEquivalents[] = $equivalent;
                    }
                }
                $jsonDecode->equivalents = $newEquivalents;
                $this->view->search = $search;
                $this->view->entry = $jsonDecode;
                $this->view->static = $static;
                $this->view->lang = $language;
                $this->content = $this->_helper->viewRenderer->view->render('entri/equivalent.html');
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->getResponse()->appendBody($this->content);
    }

    /**
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}/sumber')
     * @PathParam{text}(entry)
     * @Produces('text/html')
     */
    public function sourceHtml($text)
    {
        $this->_helper->viewRenderer->setNoRender();
        $cacheId = __METHOD__ . '\\' . $text;
        $search = new kateglo\application\models\front\Search();

        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $search->setFieldValue($text);
                $search->setFormAction('/kamus');
                $entryObj = $this->entry->getEntry($text);
                $entry = $entryObj->toArray();
                $jsonEncode = json_encode($entry);
                $entry = json_decode($jsonEncode);
                $this->view->search = $search;
                $this->view->entry = $entry;
                $this->content = $this->_helper->viewRenderer->view->render('entri/source.html');
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }

        $this->responseBuilder($cacheId);
        $this->getResponse()->appendBody($this->content);
    }

    /**
     * @var string $text
     * @return void
     * @Get
     * @Path('/{entry}')
     * @PathParam{text}(entry)
     * @Produces('application/json')
     */
    public function indexJson($text)
    {
        $cacheId = __METHOD__ . '\\' . $text;
        if (!$this->evaluatePreCondition($cacheId)) {
            try {
                $entryObj = $this->entry->getEntry($text);
                $this->content = $entryObj->toArray();
            } catch (DomainResultEmptyException $e) {
                throw new HTTPNotFoundException('Entry Not Found.');
            }
        }
        $this->responseBuilder($cacheId);
        $this->_helper->json($this->content);
    }

    /**
     * @return void
     * @Get
     * @Path('/hariini')
     * @Produces('text/html')
     */
    public function wordOfTheDay()
    {
        $this->_helper->viewRenderer->setNoRender();
        $wordOfTheDay = $this->wotd->getToday();
        $this->getResponse()->setHttpResponseCode(303);
        $this->getResponse()->setHeader('Location', '/entri/' . urlencode($wordOfTheDay->getEntry()));
    }
}

?>