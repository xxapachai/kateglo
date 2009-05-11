<?php
/**
 * @author ivan@lanin.org
 *
 */
require_once('HTML/QuickForm.php');
class form extends HTML_QuickForm
{
    /**
     * @return unknown_type
     */
    function setup()
    {
    	$this->setJsWarnings('Ada kesalahan pada informasi yang dimasukkan.', 'Harap perbaiki isian tersebut.');
    }
    
	/**
	 * @param $element
	 * @return HTML code of the element
	 */
	function getElementHtml($element)
	{
		return($this->getElement($element)->toHtml());
	}
	
	/**
	 * @return unknown_type
	 */
	function beginForm()
	{
		$form_array = $this->toArray();
		return('<form' . $form_array['attributes'] . '>' . LF);
	}
	
	/**
	 * @return unknown_type
	 */
	function endForm()
	{
		$form_array = $this->toArray();
		return($form_array['javascript']. LF . '</form>' . LF);
	}
}
?>