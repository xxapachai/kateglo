<?php
namespace kateglo\application\configs;
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
use Doctrine\Common\Cache\ApcCache;
use kateglo\application\daos;
use kateglo\application\services;
use kateglo\application\utilities;
use kateglo\application\faces;
use kateglo\application\configs\interfaces;
/**
 *
 *
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Binder {
	
	public static function bind(\stubBinder $container) {
		
		//Stubbles check with class_exist() and throw exception if the class is not found
		//This class is not loaded yet by Doctrine class loader.
		//So this "Hacks" is necessary to load the class with Doctrine loader before injected by Stubbles
		new ApcCache ();
		
		$container->bind ( interfaces\Configs::INTERFACE_NAME )->to ( Configs::$CLASS_NAME );
		$container->bind ( daos\interfaces\Entry::INTERFACE_NAME )->to ( daos\Entry::$CLASS_NAME );
		$container->bind ( daos\interfaces\User::INTERFACE_NAME )->to ( daos\User::$CLASS_NAME );
		$container->bind ( services\interfaces\Entry::INTERFACE_NAME )->to ( services\Entry::$CLASS_NAME );
		$container->bind ( services\interfaces\Pagination::INTERFACE_NAME )->to ( services\Pagination::$CLASS_NAME );
		$container->bind ( utilities\interfaces\DataAccess::INTERFACE_NAME )->to ( utilities\DataAccess::$CLASS_NAME );
		$container->bind ( utilities\interfaces\LogService::INTERFACE_NAME )->to ( utilities\LogService::$CLASS_NAME );
		$container->bind ( utilities\interfaces\SearchEngine::INTERFACE_NAME )->to ( utilities\SearchEngine::$CLASS_NAME );
		$container->bind ( utilities\interfaces\KBBI::INTERFACE_NAME )->to ( utilities\KBBI::$CLASS_NAME );		
		$container->bind ( utilities\interfaces\CURL::INTERFACE_NAME )->to ( utilities\CURL::$CLASS_NAME );
		$container->bind ( 'Doctrine\Common\Cache\Cache' )->to ( 'Doctrine\Common\Cache\ApcCache' );
		$container->bind ( faces\interfaces\Search::INTERFACE_NAME )->to ( faces\Search::$CLASS_NAME );
	}
}
?>