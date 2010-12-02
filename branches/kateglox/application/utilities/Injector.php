<?php
namespace kateglo\application\utilities;
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
use kateglo\application\configs;
use kateglo\application\utilities\exceptions;
/**
 *
 *
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Injector {
	
	/**
	 * 
	 * @var stubBinder
	 */
	private static $container;
	
	/**
	 * 
	 * @param string $className
	 * @return Object<T>
	 */
	public static function getInstance($className) {
		if (! (self::$container instanceof \stubBinder)) {
			self::set ();
		}
		$object = self::$container->getInjector ()->getInstance ( $className );
		if (! ($object instanceof $className)) {
			throw new exceptions\InjectorException ( 'Object instantiating failed!' );
		}
		return $object;
	}
	
	/**
	 *
	 * @param stubBinder $container
	 */
	public static function set(stubBinder $container = null) {
		if ($container === null) {
			if (! (self::$container instanceof \stubBinder)) {
				self::$container = new \stubBinder ();
				configs\Binder::bind ( self::$container );
			}
		} else {
			self::$container = $container;
		}
	}
	
	/**
	 *
	 * @param stubBinder $container
	 */
	public static function get(stubBinder $container = null) {
		if (! (self::$container instanceof \stubBinder)) {
			self::set ();
		}
		return self::$container;
	}
}
?>