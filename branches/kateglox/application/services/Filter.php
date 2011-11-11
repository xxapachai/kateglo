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
class Filter implements interfaces\Filter
{

    public static $CLASS_NAME = __CLASS__;

    /**
     * @param \kateglo\application\models\front\Filter $filter
     * @return void
     */
    public function create(front\Filter $filter) {
        $filters = array();
        $filtersMap = array();
        if (is_string($filter->getUri()) && trim($filter->getUri()) !== '') {
            $commaExplode = array_map('trim', explode(',', $filter->getUri()));
            $countCommaExplode = count($commaExplode);
            for ($i = 0; $i < $countCommaExplode; $i++) {
                $filterUri = implode(',', array_slice($commaExplode, 0, $i + 1));
                $nameValExplode = array_map('trim', explode(':', $commaExplode[$i]));
                switch ($nameValExplode[0]) {
                    case 't':
                        $filter->setTypeValue($nameValExplode[1]);
                        $filter->setTypeUri($this->createUri($commaExplode, $commaExplode[$i]));
                        break;
                    case 'c' :
                        $filter->setClassValue($nameValExplode[1]);
                        $filter->setClassUri($this->createUri($commaExplode, $commaExplode[$i]));
                        break;
                    case 's' :
                        $filter->setSourceValue($nameValExplode[1]);
                        $filter->setSourceUri($this->createUri($commaExplode, $commaExplode[$i]));
                        break;
                    case 'd' :
                        $filter->setDisciplineValue($nameValExplode[1]);
                        $filter->setDisciplineUri($this->createUri($commaExplode, $commaExplode[$i]));
                        break;
                }
            }

        }
    }

    private function createUri($uriArray, $filterUri) {
        $newUriArray = array();
        foreach ($uriArray as $singleUri) {
            if ($singleUri !== $filterUri) {
                $newUriArray[] = $singleUri;
            }
        }
        return implode(',', $newUriArray);
    }

}

?>