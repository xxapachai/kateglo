<?php
namespace kateglo\library\Stubbles;
/**
 * The bootstrap class takes care of providing all necessary data required in the bootstrap process.
 *
 * @author  Arthur Purnama <arthur@purnama.de>
 * @package  stubbles
 * @version  $Id$
 */
/**
 * The bootstrap class takes care of providing all necessary data required in the bootstrap process.
 *
 * @package  stubbles
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