<?php
namespace kateglo\application\annotations;
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

/**
 *
 *
 * @package kateglo\application\daos
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class Post extends \stubAbstractAnnotation implements \stubAnnotation{

    /**
     * returns a string representation of the class
     *
     * The result is a short but informative representation about the class and
     * its values. Per default, this method returns:
     * [fully-qualified-class-name] ' {' [members-and-value-list] '}'
     * <code>
     * example.MyClass {
     *     foo(string): hello
     *     bar(example::AnotherClass): example::AnotherClass {
     *         baz(int): 5
     *     }
     * }
     * </code>
     *
     * @return  string
     */
    public function __toString() {
        // TODO: Implement __toString() method.
    }

    /**
     * checks whether a value is equal to the class
     *
     * @param   mixed  $compare
     * @return  bool
     */
    public function equals($compare) {
        // TODO: Implement equals() method.
    }

    /**
     * do some last operations after all values have been set
     *
     * This method may check if all required values have been set and throw
     * an exception if values are missing.
     *
     * @throws  ReflectionException
     */
    public function finish() {
        // TODO: Implement finish() method.
    }

    /**
     * Returns the name under which the annotation is stored.
     *
     * @return  string
     */
    public function getAnnotationName() {
        // TODO: Implement getAnnotationName() method.
    }

    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget() {
        return \stubAnnotation::TARGET_ALL;
    }

    /**
     * returns class informations
     *
     * @return  stubReflectionObject
     */
    public function getClass() {
        // TODO: Implement getClass() method.
    }

    /**
     * returns the full qualified class name
     *
     * @return  string
     */
    public function getClassName() {
        // TODO: Implement getClassName() method.
    }

    /**
     * returns package informations
     *
     * @return  stubReflectionPackage
     */
    public function getPackage() {
        // TODO: Implement getPackage() method.
    }

    /**
     * returns the name of the package where the class is inside
     *
     * @return  string
     */
    public function getPackageName() {
        // TODO: Implement getPackageName() method.
    }

    /**
     * returns a serialized representation of the class
     *
     * @return  stubSerializedObject
     */
    public function getSerialized() {
        // TODO: Implement getSerialized() method.
    }

    /**
     * returns a unique hash code for the class
     *
     * @return  string
     */
    public function hashCode() {
        // TODO: Implement hashCode() method.
    }

    /**
     * Sets the name under which the annotation is stored.
     *
     * @param  string  $name
     */
    public function setAnnotationName($name) {
        // TODO: Implement setAnnotationName() method.
    }
}
?>
