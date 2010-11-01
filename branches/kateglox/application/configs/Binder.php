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
use kateglo\application\utilities;
use kateglo\application\services;
use kateglo\application\daos;
/**
 *
 *
 * @package kateglo\application\configs
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since
 * @version 0.0
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Binder{
	
	public static function bind(\stubBinder $container){
		$container->bind(interfaces\Configs::INTERFACE_NAME)->to(Configs::$CLASS_NAME);
		$container->bind(daos\interfaces\Glossary::INTERFACE_NAME)->to(daos\Glossary::$CLASS_NAME);
		$container->bind(daos\interfaces\Lemma::INTERFACE_NAME)->to(daos\Lemma::$CLASS_NAME);
		$container->bind(daos\interfaces\Lexical::INTERFACE_NAME)->to(daos\Lexical::$CLASS_NAME);
		$container->bind(daos\interfaces\Misspelled::INTERFACE_NAME)->to(daos\Misspelled::$CLASS_NAME);
		$container->bind(daos\interfaces\Type::INTERFACE_NAME)->to(daos\Type::$CLASS_NAME);
		$container->bind(daos\interfaces\User::INTERFACE_NAME)->to(daos\User::$CLASS_NAME);
		$container->bind(services\interfaces\Amount::INTERFACE_NAME)->to(services\Amount::$CLASS_NAME);
		$container->bind(services\interfaces\Lists::INTERFACE_NAME)->to(services\Lists::$CLASS_NAME);
		$container->bind(services\interfaces\Lucene::INTERFACE_NAME)->to(services\Lucene::$CLASS_NAME);
		$container->bind(services\interfaces\Search::INTERFACE_NAME)->to(services\Search::$CLASS_NAME);
		$container->bind(utilities\interfaces\DataAccess::INTERFACE_NAME)->to(utilities\DataAccess::$CLASS_NAME);
		$container->bind(utilities\interfaces\LogService::INTERFACE_NAME)->to(utilities\LogService::$CLASS_NAME);	
		$container->bind('Zend_View_Interface')->to('Zend_View_PhpTal');	
	}
}
?>