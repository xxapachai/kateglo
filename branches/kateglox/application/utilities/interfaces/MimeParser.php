<?php
namespace kateglo\application\utilities\interfaces;
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
 * @package kateglo\application\utilities\interfaces
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate$
 * @version $LastChangedRevision$
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
interface MimeParser {
	
	const INTERFACE_NAME = __CLASS__;
	
    /**
     * Carves up a mime-type and returns an Array of the [type, subtype, params]
     * where "params" is a Hash of all the parameters for the media range.
     *
     * For example, the media range "application/xhtml;q=0.5" would
     * get parsed into:
     *
     * array("application", "xhtml", array( "q" => "0.5" ))
     *
     * @param string $mimeType
     * @return array ($type, $subtype, $params)
     */
    function parseMimeType($mimeType) ;


    /**
     * Carves up a media range and returns an Array of the
     * [type, subtype, params] where "params" is a Hash of all
     * the parameters for the media range.
     *
     * For example, the media range "application/*;q=0.5" would
     * get parsed into:
     *
     * array("application", "*", ( "q", "0.5" ))
     *
     * In addition this function also guarantees that there
     * is a value for "q" in the params dictionary, filling it
     * in with a proper default if necessary.
     *
     * @param string $range
     * @return array ($type, $subtype, $params)
     */
    function parseMediaRange($range) ;

    /**
     * Find the best match for a given mime-type against a list of
     * mediaRanges that have already been parsed by MimeParser::parseMediaRange()
     *
     * Returns the fitness and the "q" quality parameter of the best match, or an
     * array [-1, 0] if no match was found. Just as for MimeParser::quality(),
     * "parsedRanges" must be an Enumerable of parsed media ranges.
     *
     * @param string $mimeType
     * @param array  $parsedRanges
     * @return array ($bestFitness, $bestFitQ)
     */
    function fitnessAndQualityParsed($mimeType, $parsedRanges) ;

    /**
     * Find the best match for a given mime-type against a list of
     * mediaRanges that have already been parsed by MimeParser::parseMediaRange()
     *
     * Returns the "q" quality parameter of the best match, 0 if no match
     * was found. This function behaves the same as MimeParser::quality() except that
     * "parsedRanges" must be an Enumerable of parsed media ranges.
     *
     * @param string $mimeType
     * @param array  $parsedRanges
     * @return float $q
     */
    function qualityParsed($mimeType, $parsedRanges) ;

    /**
     * Returns the quality "q" of a mime-type when compared against
     * the media-ranges in ranges. For example:
     *
     * MimeParser::quality("text/html", "text/*;q=0.3, text/html;q=0.7,
     * text/html;level=1, text/html;level=2;q=0.4, *\/*;q=0.5")
     * => 0.7
     *
     * @param string $mimeType
     * @param string $ranges
     * @return string
     */
    function quality($mimeType, $ranges) ;

    /**
     * Takes a list of supported mime-types and finds the best match
     * for all the media-ranges listed in header. The value of header
     * must be a string that conforms to the format of the HTTP Accept:
     * header. The value of supported is an Enumerable of mime-types
     *
     * MimeParser::bestMatch(array("application/xbel+xml", "text/xml"), "text/*;q=0.5,*\/*; q=0.1")
     * => "text/xml"
     *
     * @param  array  $supported
     * @param  string $header
     * @return mixed  $mimeType or NULL
     */
    function bestMatch($supported, $header) ;
}

?>