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
class Search implements interfaces\Search{
	
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
	public function getFormMethod(){
		return helpers\HTTPMethod::GET;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getFieldName(){
		return "query";
	}
	
	/**
	 * 
	 * @return string
	 */
	public function setFieldValue($fieldValue){
		$this->fieldValue = $fieldValue;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getFieldValue(){
		return $this->fieldValue;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getButtonValue(){
		return "Cari";
	}
}
?>