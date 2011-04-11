<?php 
/*
 *  $Id: PhpTal.php 300 2011-03-31 18:00:36Z arthur.purnama $
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
  
  
/** Zend_View_Interface */  
require_once 'Zend/View/Interface.php';  
  
/** PHPTAL */  
require_once 'PHPTAL.php';
use kateglo\application\utilities\Injector;
/**
 * A PHPTAL templating engine implementation.
 * 
 * PHPTAL is a separate creative work distributed under the GNU Lesser General 
 * Public License and copyright (c) 2004-2005 Laurent Bedubourg.  For more 
 * information, consult the COPYING file contained in the PHPTAL distribution.
 * Only use PHPTAL and this interface if your project license permits it.
 *
 * @package kateglo\library\Zend\View
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-31 20:00:36 +0200 (Do, 31 Mrz 2011) $
 * @version $LastChangedRevision: 300 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */  
class Zend_View_PhpTal implements Zend_View_Interface 
{ 
	const PHPTAL_DEFAULT_ENCODING = 'UTF-8';
	
    /** @var PHPTAL PHPTAL engine */ 
    private $_engine = null; 
 
    /** @var array Context variables */ 
    private $_variables = array(); 
 
    /** @var array Paths to templates */ 
    private $_paths = array(); 
 
    /** @var string Template filename */ 
    private $_template = ''; 
 
    // The following are PHPTAL-specific options: 
 
    /** @var string Character encoding */ 
    private $_encoding = 'UTF-8'; 
 
    /** @var string Output mode (XML or XHTML) */ 
    private $_outputMode = PHPTAL::XHTML; 
 
    /** @var bool Ignore HTML/XHTML comments on parsing */ 
    private $_stripComments = false; 
 
    /**
     * Constructor.
     *
     * @param array $config Configuration key-value pairs.
     */ 
    public function __construct(array $config = array()) 
    { 
        $this->_engine = new PHPTAL(); 
 		$this->_engine->setPhpCodeDestination(Injector::getInstance('Zend_Config')->cache->tal);
        if (isset($config['scriptPath'])) { 
            $this->setScriptPath($config['scriptPath']); 
        } 
        if (isset($config['encoding'])) { 
            $this->setEncoding($config['encoding']); 
        } 
        if (isset($config['outputMode'])) { 
            $this->setOutputMode($config['outputMode']); 
        } 
        if (isset($config['stripComments'])) { 
            $this->setStripComments($config['stripComments']); 
        } 
        if (isset($config['forceReparse'])) { 
            $this->setForceReparse($config['forceReparse']); 
        }
        $this->_engine->setPostFilter(Injector::getInstance('kateglo\application\utilities\TalTidy'));
    } 
 
    /**
     * Adds to the stack of view script paths in LIFO order.
     *
     * @param string|array The directory or directories to add.
     */ 
    public function addScriptPath($path) 
    { 
        $this->_engine->setTemplateRepository($this->getScriptPaths()); 
        $this->_addPath($path); 
        return $this; 
    } 
 
    /**
     * Resets the stack of view script paths.
     *
     * To clear all paths, use Zend_View_PhpTal::setScriptPath(null).
     *
     * @param string|array The directory or directories to set as the path
     */ 
    public function setScriptPath($path) 
    { 
        $this->_paths = array(); 
        if (!empty($path)) { 
            $this->addScriptPath($path); 
        } 
        return $this; 
    } 
 
    /**
     * Returns an array of all currently set script paths.
     *
     * @return array
     */ 
    public function getScriptPaths() 
    { 
        return $this->_paths; 
    } 
 
    /**
     * Given a base path, sets the templates, helper, and filter paths relative to it
     *
     * Assumes a directory structure of:
     * <code>
     * basePath/
     *     templates/
     *     helpers/
     *     filters/
     * </code>
     * 
     * @param  string $path 
     * @param  string $prefix Prefix to use for helper and filter paths
     * @return Zend_View_Abstract
     */ 
    public function setBasePath($path, $classPrefix = 'Zend_View_PhpTal') 
    { 
        $path        = rtrim($path, '/'); 
        $path        = rtrim($path, '\\'); 
        $path       .= DIRECTORY_SEPARATOR; 
        $classPrefix = rtrim($classPrefix, '_') . '_'; 
        $this->setScriptPath($path . 'templates'); 
        $this->setHelperPath($path . 'helpers', $classPrefix . 'Helper'); 
        $this->setFilterPath($path . 'filters', $classPrefix . 'Filter'); 
        return $this; 
    } 
 
    /**
     * Given a base path, add script, helper, and filter paths relative to it
     *
     * Assumes a directory structure of:
     * <code>
     * basePath/
     *     templates/
     *     helpers/
     *     filters/
     * </code>
     * 
     * @param  string $path 
     * @param  string $prefix Prefix to use for helper and filter paths
     * @return Zend_View_Abstract
     */ 
    public function addBasePath($path, $classPrefix = 'Zend_View_PhpTal') 
    { 
        $path        = rtrim($path, '/'); 
        $path        = rtrim($path, '\\'); 
        $path       .= DIRECTORY_SEPARATOR; 
        $classPrefix = rtrim($classPrefix, '_') . '_'; 
        $this->addScriptPath($path . 'scripts'); 
        //$this->addHelperPath($path . 'helpers', $classPrefix . 'Helper'); 
        //$this->addFilterPath($path . 'filters', $classPrefix . 'Filter'); 
        return $this; 
    } 
 
    /**
     * Assign variables to the view script via differing strategies.
     *
     * Suggested implementation is to allow setting a specific key to the
     * specified value, OR passing an array of key => value pairs to set en
     * masse.
     *
     * @see    __set()
     * @param  string|array $mixed The assignment strategy to use (key or array of
     *                             key => value pairs)
     * @param  mixed        $value If assigning a named variable, use this as the
     *                             value (optional).
     * @return void
     */ 
    public function assign($mixed, $value = null) 
    { 
        if (is_string($mixed)) { 
            $this->_variables[$mixed] = $value; 
        } elseif (is_array($mixed)) { 
            foreach ($mixed as $key => $value) { 
                $this->_variables[$key] = $value; 
            } 
        } else { 
            throw new Zend_View_Exception('assign() expects a string or array, received ' . gettype($mixed)); 
        } 
    } 
 
    /**
     * Clear all assigned variables.
     *
     * Clears all variables assigned to Zend_View_PhpTal either via {@link assign()} or
     * property overloading ({@link __get()}/{@link __set()}).
     */ 
    public function clearVars() 
    { 
        $this->_variables = array(); 
    } 
 
    /**
     * Processes a view script and returns the output.
     *
     * @param  string $template Script name to process
     * @return string Output
     */ 
    public function render($template) 
    { 
        // Find the script file name using the parent private method 
        $this->_template = $this->_script($template); $la = $this->_template;
        unset($template); // Remove $template from local scope 
        $this->_engine->setTemplate($this->_template); 
        $this->_assignAll($this->_variables); $blob = $this->_engine;
 		$bleh = $this->_engine->execute();
        return $bleh; 
    } 
 
    /**
     * Assign a variable to the view.
     *
     * @param string $key Variable name
     * @param mixed  $val Variable value
     */ 
    public function __set($key, $value) 
    { 
        if ($key[0] != '_') { 
            $this->_variables[$key] = $value; 
        } 
    } 
 
    /**
     * Retrieve an assigned variable.
     *
     * @param  string $key Variable name
     * @return mixed  Variable value
     */ 
    public function __get($key) 
    { 
        if ($this->__isset($key)) { 
            return $this->_variables[$key]; 
        } 
        return null; 
    } 
 
    /**
     * Allows testing with empty() and isset() to work.
     *
     * @param  string $key
     * @return boolean
     */ 
    public function __isset($key) 
    { 
        return array_key_exists($key, $this->_variables) and ($key[0] != '_'); 
    } 
 
    /**
     * Allows unset() on object properties to work.
     *
     * @param string $key
     */ 
    public function __unset($key) 
    { 
        if ($this->__isset($key)) { 
            unset($this->_vars[$key]); 
        } 
    } 
 
    /**
     * Clone template state and context.
     */ 
    public function __clone() 
    { 
        $this->_engine = clone $this->_engine; 
    } 
 
    /**
     * Return the PHPTAL engine object.
     *
     * @return PHPTAL Engine
     */ 
    public function getEngine() 
    { 
        return $this->_engine; 
    } 
 
    /**
     * Returns array of exceptions catched by tal:on-error attribute.
     *
     * @return array Exceptions
     */ 
    public function getErrors() 
    { 
        return $this->_engine->getErrors(); 
    } 
 
    /**
     * Set character encoding for output.
     *
     * @param string $encoding Character encoding (e.g., 'UTF-8')
     */ 
    public function setEncoding($encoding = 'UTF-8') 
    { 
        $this->_engine->setEncoding($encoding); 
        $this->_encoding = $encoding; 
        return $this; 
    } 
 
    /**
     * Returns character encoding for output.
     *
     * @return string Character encoding (e.g., 'UTF-8')
     */ 
    public function getEncoding() 
    { 
        return $this->_encoding; 
    } 
 
    /**
     * Set output mode (XHTML or XML).
     *
     * @param int $mode PHPTAL_XHTML or PHPTAL_XML
     */ 
    public function setOutputMode($mode) 
    { 
        $this->_engine->setOutputMode($mode); 
        $this->_outputMode = $mode; 
        return $this; 
    } 
 
    /**
     * Get output mode (XHTML or XML).
     *
     * @return int Constant value of PHPTAL_XHTML or PHPTAL_XML
     */ 
    public function getOutputMode() 
    { 
        return $this->_outputMode; 
    } 
 
    /**
     * Set whether to ignore HTML comments when parsing.
     *
     * @param bool $flag
     */ 
    public function setStripComments($flag = true) 
    { 
        $this->_engine->stripComments($flag); 
        $this->_stripComments = $flag; 
        return $this; 
    } 
 
    /**
     * Get whether to ignore HTML comments when parsing.
     *
     * @return bool
     */ 
    public function getStripComments() 
    { 
        return $this->_stripComments; 
    } 
 
    /**
     * Set whether to force a reparse with every page load.  This defines a
     * constant, so once set, this cannot be set again.
     *
     * @param bool $flag
     */ 
    public function setForceReparse($flag) 
    { 
        if (defined('PHPTAL_FORCE_REPARSE')) { 
            throw new Zend_View_Exception('setForceReparse() defines a constant, and cannot be called twice'); 
        } 
        define('PHPTAL_FORCE_REPARSE', (int) $flag); 
        return $this; 
    } 
 
    /**
     * Get whether to force a reparse or not.
     *
     * @return bool
     */ 
    public function getForceReparse() 
    { 
        if (defined('PHPTAL_FORCE_REPARSE')) { 
            return (bool) PHPTAL_FORCE_REPARSE; 
        } 
        return false; 
    } 
 
    /**
     * Get the path to the PHP generated file.
     *
     * @return string PHP generated file path
     */ 
    public function getCodePath() 
    { 
        return PHPTAL_PHP_CODE_DESTINATION;  
    } 
 
    /**
     * Get the extension used for PHP files.
     *
     * @return string PHP extension (e.g., 'php')
     */ 
    public function getCodeExtension() 
    { 
        return PHPTAL_PHP_CODE_EXTENSION; 
    } 
 
    /**
     * Assign all variables to the PHPTAL engine.  This is done at render time
     * because PHPTAL has no method to unset context variables.
     *
     * @param array $variables Variables to assign
     */ 
    protected function _assignAll(array $variables = array()) 
    { 
        foreach ($variables as $key => $value) { 
            $this->_engine->set($key, $value); 
        } 
    } 
 
    /**
     * Finds a view script from the available directories.
     *
     * @param  string $name Base name of the template
     * @return string Complete path to template
     */ 
    protected function _script($template) 
    { 
        if (count($this->_paths) == 0) { 
            throw new Zend_View_Exception('No view script directory set'); 
        } 
 
        foreach ($this->_paths as $directory) { 
            if (is_readable($directory . $template)) { 
                return $directory . $template; 
            } 
        } 
 
        throw new Zend_View_Exception("Template '{$template}' not found in path"); 
    } 
 
    /**
     * Adds paths to the path stack in LIFO order.
     *
     * _addPath($type, 'dirname') adds one directory to the path stack.
     * _addPath($type, $array) adds one directory for each array element value.
     *
     * @param string|array $path
     */ 
    private function _addPath($path) 
    { 
        foreach ((array) $path as $directory) { 
            $directory = rtrim($directory, '\\/' . DIRECTORY_SEPARATOR) 
                       . DIRECTORY_SEPARATOR; 
            array_unshift($this->_paths, $directory); 
        } 
    } 
}
?>