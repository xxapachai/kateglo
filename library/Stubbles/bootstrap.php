<?php
namespace kateglo\library\Stubbles;
/*
 *  $Id: bootstrap.php 266 2010-12-16 21:01:27Z arthur.purnama $
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
/**
 * The bootstrap class takes care of providing all necessary data required in the bootstrap process.
 *
 * @package kateglo\library\Stubbles
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2010-12-16 22:01:27 +0100 (Do, 16 Dez 2010) $
 * @version $LastChangedRevision: 266 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class stubBootstrap extends \stubBootstrap
{
	/**
	 * loads stubbles core classes and initializes pathes
	 *
	 * @param  array<string,string>  $pathes     optional  list of pathes: project, [cache, config, log, page]
	 * @param  string                $classFile  optional  defaults to stubbles.php
	 */
	public static function init(array $pathes = array(), $classFile = 'stubbles.php')
	{
		require_once $classFile;
		\stubClassLoader::load('net::stubbles::lang::stubPathRegistry');
		\stubPathRegistry::setPathes($pathes);
	}
}
?>