<?php
namespace kateglo\application\utilities;
/*
 *  $Id: KBBI.php 293 2011-03-15 10:53:09Z arthur.purnama $
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
use Doctrine\Common\Collections\Collection;
use kateglo\application\models\Synonym;
use kateglo\application\models\Meaning;
use kateglo\application\models\Entry;
use Doctrine\Common\Collections\ArrayCollection;
use kateglo\application\utilities\interfaces\CURL;
use kateglo\application\utilities\interfaces;
/**
 *
 *
 * @package kateglo\application\utilities
 * @license <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html> GPL 2.0
 * @link http://code.google.com/p/kateglo/
 * @since $LastChangedDate: 2011-03-15 11:53:09 +0100 (Di, 15 Mrz 2011) $
 * @version $LastChangedRevision: 293 $
 * @author  Arthur Purnama <arthur@purnama.de>
 * @copyright Copyright (c) 2009 Kateglo (http://code.google.com/p/kateglo/)
 */
class KBBI implements interfaces\KBBI {
	
	public static $CLASS_NAME = __CLASS__;
	
	/**
	 * 
	 * Enter description here ...
	 * @var Zend_Config
	 */
	private $configs;
	
	/**
	 * 
	 * Enter description here ...
	 * @var kateglo\application\utilities\interfaces\CURL
	 */
	private $curl;
		
	/**
	 * 
	 * Enter description here ...
	 * @param Zend_Config $configs
	 * 
	 * @Inject
	 */
	public function setConfigs(\Zend_Config $configs) {
		$this->configs = $configs;
	}
	
	/**
	 *
	 * @param kateglo\application\utilities\interfaces\CURL $curl 
	 * @return void
	 *
	 * @Inject
	 */
	public function setCurl(CURL $curl) {
		$this->curl = $curl;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param Doctrine\Common\Collections\ArrayCollection $entry
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDefinitions($entries) {
		$return = new ArrayCollection ();
		foreach ( $entries as $entry ) {
			$definition = $this->getDefinition ( $entry );
			if (! empty ( $definition )) {
				$return->add ( $definition );
			}
		}
		return $return;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $entry
	 * @param Doctrine\Common\Collections\ArrayCollection $result
	 * @param int $head
	 * @param string $perintah
	 * @param string $perintah2
	 */
	public function getEntry($entry, $head = 0, $perintah = 'Cari', $perintah2 = '') {
		$result = new ArrayCollection ();
		$url = 'http://pusatbahasa.diknas.go.id/kbbi/index.php';
		$data = 'OPKODE=1&PARAM=%1$s&HEAD=%2$d&MORE=0&PERINTAH=%3$s&PERINTAH2=%4$s';
		$data = sprintf ( $data, $entry, $head, $perintah, $perintah2 );
		$resource = $this->getCurl ( $url, $data );
		$pattern = '/<input type="hidden" name="DFTKATA" value="(.+)" >.+' . '<input type="hidden" name="MORE" value="(.+)" >.+' . '<input type="hidden" name="HEAD" value="(.+)" >/s';
		preg_match ( $pattern, $resource, $match );
		if (is_array ( $match )) {
			if (array_key_exists ( 2, $match ) && $match [2] == 1) {
				$newHead = array_key_exists ( 3, $match ) ? $match [3] + 15 : $head;
				$this->search ( $entry, $result, '', 'Berikut', $newHead );
			}
			if (array_key_exists ( 1, $match )) {
				$matchs = explode ( ';', $match [1] );
				foreach ( $matchs as $value ) {
					$result->add ( $value );
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $query
	 * @return string
	 */
	public function getDefinition($entry) {
		$url = 'http://pusatbahasa.diknas.go.id/kbbi/index.php';
		$data = 'DFTKATA=%1$s&HEAD=0&KATA=%1$s&MORE=0&OPKODE=1&PARAM=&PERINTAH2=Tampilkan';
		$data = sprintf ( $data, $entry );
		$result = $this->getCurl ( $url, $data );
		$pattern = '/(<p style=\'margin-left:\.5in;text-indent:-\.5in\'>)(.+)(<\/(p|BODY)>)/s';
		preg_match ( $pattern, $result, $match );
		$definition = '';
		if (is_array ( $match )) {
			$definition = trim ( $match [2] );
			
			// manual fixes
			if ($entry == 'air')
				$definition = preg_replace ( '/minuman[\s]+(<br>)+terbuat/U', 'minuman terbuat', $definition );
			if ($entry == 'tarik')
				$definition = preg_replace ( '/menyenangkan[\s]+(<br>)+\(menggirangkan/U', 'menyenangkan (menggirangkan', $definition );
			if ($entry == 'harta')
				$definition = preg_replace ( '/oleh[\s]+(<br>)+mempelai laki/U', 'oleh mempelai laki', $definition );
			if ($entry == 'alur')
				$definition = preg_replace ( '/alur[\s]+(<br>)+kedua/U', 'alur kedua', $definition );
			if ($entry == 'hutan')
				$definition = preg_replace ( '/hutan[\s]+(<br>)+guna/U', 'hutan guna', $definition );
			if ($entry == 'lemah (1)')
				$definition = preg_replace ( '/el[\s]+(<br>)+oknya/U', 'eloknya', $definition );
			if ($entry == 'lepas')
				$definition = preg_replace ( '/tempatnya la[\s]+(<br>)+gi/U', 'tempatnya lagi', $definition );
			if ($entry == 'minyak')
				$definition = str_replace ( '<br><i>--</i><b> adas manis</b>', '<br>--<b> adas manis</b>', $definition );
			if ($entry == 'kepala')
				$definition = str_replace ( 'suka sekali; --<b>', 'suka sekali;' . LF . '<br>--<b>', $definition );
			if ($entry == 'induk')
				$definition = str_replace ( '<br>--</i><b> bako', '<br>--<b> bako', $definition );
			if ($entry == 'lampu')
				$definition = str_replace ( 'mati); --<b> atret', 'mati);' . LF . '<br>--<b> atret', $definition );
			if ($entry == 'beri tahu')
				$definition = str_replace ( 'ri</b> <b>ta', 'ri ta', $definition );
			
			$definition = str_replace ( '<br>', '<br><br>', $definition );
		}
		
		return $definition;
	}
	
	public function parse($entry) {
		$entries = $this->getEntry ( $entry );
		$definitions = $this->getDefinitions ( $entries );
		
		$entryEntity = new Entry ();
		
		if ($definitions->count () === 0) {
			throw new \Exception ( 'nothing to parse' );
		} else {
			$entryEntity->setEntry ( $entry );
		
		}
		foreach ( $definitions as $definition ) {
			$meaning = new Meaning ();
			$entryEntity->addMeaning ( $meaning );
			$lines = $this->createLines ( $definition, $entryEntity );
			if (! $this->parseRedirect ( $lines, $entryEntity, $meaning )) {
				$this->parseStandard ( $lines, $entryEntity, $meaning );
			}
		}
	
	}
	
	public function createLines($definition, Entry $entry) {
		$kbbiData = $definition;
		//		foreach ( $definitions as $value ) {
		//			$kbbiData .= $kbbiData ? "\n<br>" : '';
		//			$kbbiData .= $value;
		//		}
		

		// hack v
		if (strtolower ( $entry->getEntry () ) == 'v')
			$kbbiData = str_replace ( '<b>V</b>, v', '<b>V, v</b>', $kbbiData );
		if (strtolower ( $entry->getEntry () ) == 'amnesia') {
			$kbbiData = str_replace ( '<b>/</b>', '/', $kbbiData );
			$kbbiData = str_replace ( '/</b>', '</b>/', $kbbiData );
		}
		if (strtolower ( $entry->getEntry () ) == 'data') {
			$kbbiData = str_replace ( '<b>- data</b> <b>1', '<b>-- data 1</b>', $kbbiData );
		}
		
		// parse into lines and process
		$lines = preg_split ( '/[\n|\r](?:<br>)*(?:<\/i>)*/', $kbbiData );
		
		$arrayCollection = new ArrayCollection ( $lines );
		
		return $arrayCollection;
	}
	
	public function parseStandard(Collection $lines, Entry $entry, Meaning $meaning) {
		$pairs = $this->parsePairs ( $lines );
		$newPairs = $this->parseCleansing ( $pairs );
		$definitions = $this->parseDefinitions ( $newPairs );
		$newDefinitions = $this->parseFinalize ( $definitions );
	}
	
	public function parsePairs(Collection $lines) {
		// normal
		$lineCount = $lines->count ();
		
		$temporaryPair = array ();
		// process each line
		for($i = 0; $i < $lineCount; $i ++) {
			// assume type
			$temporaryType = 'r';
			
			// hack for me- peng-
			$pattern = '/\(<b>.+<\/b>\)/U';
			if ($lineCount == 1 && preg_match ( $pattern, $lines->get ( 0 ) )) {
				$lines->set ( 0, preg_replace ( $pattern, '', $lines->get ( 0 ) ) );
			}
			
			// hack for redirect
			if ($lineCount == 2 && strpos ( $lines->get ( $i ), '</b>' ) === false && strpos ( $lines->get ( $i ), '?' ) !== false) {
				$lines->set ( $i, str_replace ( '?', '</b>?', $lines->get ( $i ) ) );
			}
			
			// hack, found in titik
			if (strpos ( $lines->get ( $i ), '--</i><b>' ) !== false) {
				$lines->set ( $i, str_replace ( '--</i><b>', '--<b>', $lines->get ( $i ) ) );
			}
			
			$pattern = '/([-|~]*<b>.+<\/b>)/U';
			$match = new ArrayCollection ( preg_split ( $pattern, $lines->get ( $i ), - 1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY ) );
			$lines->set ( $i, $match );
			
			$lineCount2 = $match->count ();
			
			// normal statement always paired
			if ($lineCount2 > 1) {
				for($j = 0; $j < $lineCount2 / 2; $j ++) {
					$pair1 = trim ( $match->get ( $j * 2 ) );
					$pair2 = trim ( $match->get ( $j * 2 + 1 ) );
					$temporaryDefinition = '';
					$temporarySample = '';
					
					// remove unnecessary elements
					$pair1 = str_replace ( '&#183;', '', $pair1 ); // remove &#183; suku kata
					$pair1 = preg_replace ( '/<sup>\d+<\/sup>/', '', $pair1 ); // remove superscript
					preg_match ( '/^[-|~]*<b>.+<\/b>$/', $pair1, $matchBold );
					
					// check pair 1 - word or index
					if (count ( $matchBold ) > 0) {
						$pair1 = strip_tags ( $pair1 );
						$pairKey = is_numeric ( $pair1 ) ? 'index' : 'phrase';
						$temporaryPair [$i] [$j] [$pairKey] = trim ( $pair1 );
					}
					
					// check pair 2 - info or definition
					

					// pronounciation
					if (preg_match ( '/^\/([^\/]+)\/(.*)/', $pair2, $pron )) {
						$temporaryPair [$i] [$j] ['pron'] = trim ( $pron [1] );
						$pair2 = trim ( $pron [2] );
					}
					
					// TODO: possibility of more than 2 tags
					preg_match ( '/^([-|~]*<i>.+<\/i>)(.*)$/U', $pair2, $matchItalic );
					if (count ( $matchItalic ) > 0) {
						$temporaryPair [$i] [$j] ['info'] = trim ( strip_tags ( $matchItalic [1] ) );
						$pair2 = trim ( $matchItalic [2] );
						// definition, watch for possible additional <i> tags
						if ($pair2 != '') {
							$temporaryDefinition = trim ( $matchItalic [2] );
							preg_match ( '/^([-|~]*<i>.+<\/i>)(.*)$/U', $pair2, $matchItalic );
							if (count ( $matchItalic ) > 0) {
								$temporaryPair [$i] [$j] ['info'] .= ' ' . trim ( strip_tags ( $matchItalic [1] ) );
								$temporaryDefinition = trim ( $matchItalic [2] );
							}
						}
					} else {
						if ($pair2)
							$temporaryDefinition = trim ( $pair2 );
					}
					
					// phrase that contains number
					$temporaryPair [$i] [$j] ['phrase'] = preg_replace ( '/^(\d+)/U', '', array_key_exists ( 'phrase', $temporaryPair [$i] [$j] ) ? $temporaryPair [$i] [$j] ['phrase'] : '' );
					
					$temporaryPhrase = $temporaryPair [$i] [$j] ['phrase'];
					preg_match ( '/^(.+) (\d+)$/U', $temporaryPhrase, $phraseMatch );
					if (count ( $phraseMatch ) > 0) {
						$temporaryPhrase = $phraseMatch [1];
						$temporaryPair [$i] [$j] ['index'] = $phraseMatch [2];
					}
					
					// clean up definition
					if ($temporaryDefinition == ',')
						unset ( $temporaryDefinition );
					if ($temporaryDefinition)
						$temporaryDefinition = strip_tags ( $temporaryDefinition );
					if ($i > 0) {
						if (strpos ( $temporaryPhrase, '--' ) !== false)
							$temporaryType = 'c';
						if (strpos ( $temporaryPhrase, '~' ) !== false)
							$temporaryType = 'c';
					}
					
					// parse info
					if (array_key_exists ( 'info', $temporaryPair [$i] [$j] )) {
						if ($temporaryPair [$i] [$j] ['info'] != '') {
							$temporaryPair [$i] [$j] = $this->parseInfoLexical ( $temporaryPair [$i] [$j] );
						}
					}
					
					// sample
					if (strpos ( $temporaryDefinition, ':' )) {
						$sample = explode ( ':', $temporaryDefinition );
						if ($sample) {
							$temporaryDefinition = trim ( $sample [0] );
							$temporarySample = trim ( strip_tags ( $sample [1] ) );
						}
					}
					
					// hack a, b
					if (strlen ( $temporaryPhrase ) == 1) {
						unset ( $temporaryPair [$i] [$j] ['phrase'] );
						$temporaryPhrase = '';
					}
					
					// syntax like meng-
					$temporaryPhrase = trim ( preg_replace ( '/\(.+\)$/U', '', $temporaryPhrase ) );
					
					// syntax like U, u
					$temporaryPhrase = trim ( preg_replace ( '/,.+$/U', '', $temporaryPhrase ) );
					
					// syntax like ? apotek
					$temploraryDefinition1 = trim ( preg_replace ( '/^\?\s*(.+)$/U', '\1', $temporaryDefinition ) );
					if ($temploraryDefinition1 != $temporaryDefinition) {
						$temporaryDefinition = 'lihat ' . $temploraryDefinition1;
						$temporaryPair [$i] [$j] ['see'] = $temploraryDefinition1;
					}
					
					// phrase contains comma ,
					if (strpos ( $temporaryPhrase, ',' ) !== false) {
						$temporaryPhrase = trim ( str_replace ( ',', '', $temporaryPhrase ) );
					}
					// phrase contains backslash /
					if (strpos ( $temporaryPhrase, '/' ) !== false) {
						$temporaryPhrase = trim ( str_replace ( '/', '', $temporaryPhrase ) );
					}
					// write
					if ($temporaryPhrase)
						$temporaryPair [$i] [$j] ['phrase'] = $temporaryPhrase;
					if ($temporaryDefinition)
						$temporaryPair [$i] [$j] ['def'] = $temporaryDefinition;
					if ($temporarySample)
						$temporaryPair [$i] [$j] ['sample'] = $temporarySample;
					if ($temporaryType) {
						$temporaryPair [$i] [$j] ['type'] = $temporaryType;
					}
					// look back
					if ($j > 0) {
						// for two definition
						if (array_key_exists ( 0, $temporaryPair [$i] )) {
							if ($temporaryPair [$i] [$j] ['phrase'] != $temporaryPair [$i] [$j - 1] ['phrase']) {
								if (! array_key_exists ( 'def', $temporaryPair [$i] [$j - 1] ) && strlen ( $temporaryPair [$i] [$j] ['phrase'] ) > 1) {
									$temporaryPair [$i] [$j - 1] ['def'] = 'lihat ' . $temporaryPair [$i] [$j] ['phrase'];
									$temporaryPair [$i] [$j - 1] ['see'] = $temporaryPair [$i] [$j] ['phrase'];
								}
							}
						}
					}
				} // .. but sometimes proverb isn't paired
			} else {
				// hack if it's not started with <i>
				if (array_key_exists ( 0, $lines->get ( $i ) )) {
					if (strpos ( substr ( $lines->get ( $i )->get ( 0 ), 0, 10 ), '<i>' ) === false) {
						$lines->get ( $i )->set ( 0, '<i>' . $lines->get ( $i )->get ( 0 ) );
					}
					// split into word and meaning
					$match = preg_split ( '/([-|~]*<i>)/U', $lines->get ( $i )->get ( 0 ), - 1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
				}
				$lineCount2 = count ( $match );
				for($j = 0; $j < $lineCount2 / 2; $j ++) {
					$proverbPair = trim ( $match [$j * 2] ) . ' ' . trim ( $match [$j * 2 + 1] );
					$proverbArray = explode ( '</i>', $proverbPair );
					$temporaryPhrase = trim ( strip_tags ( $proverbArray [0] ) );
					$temporaryPhrase = preg_replace ( '/,\s*pb/U', '', $temporaryPhrase );
					$temporaryPhrase = trim ( $temporaryPhrase );
					$temporaryDefinition = trim ( $proverbArray [1] );
					$temporaryPair [$i] [] = array ('proverb' => $temporaryPhrase, 'def' => $temporaryDefinition, 'is_proverb' => true );
				}
			}
		
		}
		return $temporaryPair;
	}
	
	public function parseCleansing(array $temporaryPair) {
		
		$pairCount = count ( $temporaryPair );
		for($i = 0; $i < $pairCount; $i ++) {
			$pairCount2 = 0;
			if (array_key_exists ( $i, $temporaryPair )) {
				$pairCount2 = count ( $temporaryPair [$i] );
			}
			for($j = 0; $j < $pairCount2; $j ++) {
				// phrase that contains only one letter
				if ($j > 0 && strlen ( $temporaryPair [$i] [$j] ['phrase'] ) <= 1) {
					unset ( $temporaryPair [$i] [$j] ['phrase'] );
				}
				
				// temporary
				$def = array_key_exists ( 'def', $temporaryPair [$i] [$j] ) ? $temporaryPair [$i] [$j] ['def'] : '';
				$phrase = array_key_exists ( 'phrase', $temporaryPair [$i] [$j] ) ? $temporaryPair [$i] [$j] ['phrase'] : '';
				$see = array_key_exists ( 'see', $temporaryPair [$i] [$j] ) ? $temporaryPair [$i] [$j] ['see'] : '';
				
				// ilmu: fisika
				if ($def == ';' && $temporaryPair [$i] [$j - 1] ['def'] = 'lihat') {
					$temporaryPair [$i] [$j - 1] ['def'] = 'lihat ' . $temporaryPair [$i] [$j] ['phrase'];
					$temporaryPair [$i] [$j - 1] ['see'] = $temporaryPair [$i] [$j] ['phrase'];
					unset ( $temporaryPair [$i] [$j] );
				}
				// redirect with number in front
				$pattern = '/^lihat (\? )?\d/U';
				if (preg_match ( $pattern, $def )) {
					$def = preg_replace ( $pattern, '', $def );
					$temporaryPair [$i] [$j] ['def'] = $def;
					$temporaryPair [$i] [$j] ['see'] = $def;
				}
				// redirect with ? in front
				$pattern = '/^lihat \?/U';
				if (preg_match ( $pattern, $def )) {
					$def = preg_replace ( $pattern, '', $def );
					$temporaryPair [$i] [$j] ['def'] = trim ( $def );
					$temporaryPair [$i] [$j] ['see'] = trim ( $def );
				}
				// redirect
				$pattern = '/^lihat /U';
				if (preg_match ( $pattern, $def )) {
					$def = preg_replace ( $pattern, '', $def );
					$temporaryPair [$i] [$j] ['def'] = trim ( $def );
					$temporaryPair [$i] [$j] ['see'] = trim ( $def );
				}
				// phrase: buang
				if ($phrase == '-hamil') {
					$temporaryPair [$i] [$j] ['phrase'] = '- hamil';
				}
				
				// phrase: banter
				if ($phrase == ', - biola')
					$temporaryPair [$i] [$j] ['phrase'] = '- biola';
				if (array_key_exists ( 'see', $temporaryPair [$i] [$j] ) && $temporaryPair [$i] [$j] ['see'] == ', - biola') {
					$temporaryPair [$i] [$j] ['see'] = 'membanter biola';
					$temporaryPair [$i] [$j] ['def'] = 'membanter biola';
				}
				
				// phrase: telang
				if ($phrase == '(bunga -- )')
					$temporaryPair [$i] [$j] ['phrase'] = 'bunga telang';
				if ($see == '(bunga -- )') {
					$temporaryPair [$i] [$j] ['see'] = 'bunga telang';
				}
				
				// phrase: jiwa
				if ($phrase == '(jiwa)')
					$temporaryPair [$i] [$j] ['phrase'] = 'menarungkan jiwa';
				if ($see == '(jiwa)') {
					$temporaryPair [$i] [$j] ['see'] = 'menarungkan jiwa';
				}
				// phrase: pun lah
				if ($phrase == '(pun lah)')
					$temporaryPair [$i] [$j] ['phrase'] = 'pun lah';
				if ($see == '(pun lah)') {
					$temporaryPair [$i] [$j] ['see'] = 'pun lah';
				}
				// phrase: galah
				if ($phrase == '(main) -- panjang') {
					$temporaryPair [$i] [$j] ['phrase'] = '-- panjang';
				}
				// phrase: bracket: tik, roboh, seliwer
				$pattern = '/^\(([^\)]+)\) ?/U';
				if (preg_match ( $pattern, $phrase )) {
					$phrase = preg_replace ( $pattern, '\1', $phrase );
					$temporaryPair [$i] [$j] ['phrase'] = $phrase;
				}
				if (preg_match ( $pattern, $see )) {
					$see = preg_replace ( $pattern, '\1', $see );
					$temporaryPair [$i] [$j] ['see'] = $see;
				}
			}
		}
		
		return $temporaryPair;
	}
	
	public function parseDefinitions(array $temporaryPair) {
		
		$definitions = array ();
		
		// put into array
		$i = 0;
		foreach ( $temporaryPair as $pairDefinition ) {
			foreach ( $pairDefinition as $phraseDefinition ) {
				
				// abbreviation
				$abbrev = array ('dl' => 'dalam', 'dng' => 'dengan', 'dl' => 'dalam', 'dr' => 'dari', 'dp' => 'daripada', 'kpd' => 'kepada', 'krn' => 'karena', 'msl' => 'misal', 'pd' => 'pada', 'sbg' => 'sebagai', 'spt' => 'seperti', 'thd' => 'terhadap', 'tsb' => 'tersebut', 'tt' => 'tentang', 'yg' => 'yang' );
				foreach ( $abbrev as $key => $value ) {
					$pattern = '/\b' . $key . '\b/';
					if (array_key_exists ( 'sample', $phraseDefinition )) {
						$phraseDefinition ['sample'] = preg_replace ( $pattern, $value, $phraseDefinition ['sample'] );
					}
					if (array_key_exists ( 'def', $phraseDefinition )) {
						$phraseDefinition ['def'] = preg_replace ( $pattern, $value, $phraseDefinition ['def'] );
					}
					if (array_key_exists ( 'proverb', $phraseDefinition )) {
						$phraseDefinition ['proverb'] = preg_replace ( $pattern, $value, $phraseDefinition ['proverb'] );
					}
				}
				
				// fixing, watch for extra space after - in phrase
				if (array_key_exists ( 'phrase', $phraseDefinition ) && $phraseDefinition ['phrase'] == '-gelembung') {
					$phraseDefinition ['phrase'] = '- gelembung';
				}
				if (array_key_exists ( 'phrase', $phraseDefinition ) && $phraseDefinition ['phrase'] == '-rektor') {
					$phraseDefinition ['phrase'] = '- rektor';
				}
				
				if (array_key_exists ( 'sample', $phraseDefinition )) {
					$phraseDefinition ['sample'] = preg_replace ( '/;$/U', '', $phraseDefinition ['sample'] );
				}
				if (array_key_exists ( 'def', $phraseDefinition )) {
					$phraseDefinition ['def'] = preg_replace ( '/;$/U', '', $phraseDefinition ['def'] );
				}
				
				if (array_key_exists ( 'phrase', $phraseDefinition )) {
					$phraseDefinition ['phrase'] = preg_replace ( '/^-+ /U', '-- ', $phraseDefinition ['phrase'] );
				}
				// root word
				$temporaryPhrase = array_key_exists ( 'proverb', $phraseDefinition ) ? $phraseDefinition ['proverb'] : (array_key_exists ( 'phrase', $phraseDefinition ) ? $phraseDefinition ['phrase'] : '');
				$isLast = true;
				$lastPhrase = '';
				if (strpos ( $temporaryPhrase, '~' ) !== false) {
					$temporaryPhrase = str_replace ( '~', $lastPhrase, $temporaryPhrase );
					$isLast = false;
				}
				if (preg_match ( '/^--/', $temporaryPhrase ) || preg_match ( '/--$/', $temporaryPhrase )) {
					$temporaryPhrase = preg_replace ( '/--/', $lastPhrase, $temporaryPhrase );
					$isLast = false;
				}
				if ($isLast) {
					if ($temporaryPhrase && ! array_key_exists ( 'proverb', $phraseDefinition ))
						$lastPhrase = $temporaryPhrase;
				}
				
				// see if it's a compound word
				if (array_key_exists ( 'type', $phraseDefinition ) && $phraseDefinition ['type'] == 'c') {
					if ($temporaryPhrase)
						$last_compound = $temporaryPhrase;
					else
						$temporaryPhrase = $last_compound;
				}
				
				// push def
				if ($temporaryPhrase) {
					if (array_key_exists ( 'proverb', $phraseDefinition ))
						$phraseDefinition ['proverb'] = $temporaryPhrase;
					else
						$phraseDefinition ['phrase'] = $temporaryPhrase;
				}
				if (! array_key_exists ( 'phrase', $phraseDefinition )) {
					$phraseDefinition ['phrase'] = $lastPhrase;
				}
				// main
				

				if (array_key_exists ( 'pron', $phraseDefinition )) {
					$definitions ['pron'] = $phraseDefinition ['pron'];
				}
				if (array_key_exists ( 'type', $phraseDefinition )) {
					$definitions ['type'] = $phraseDefinition ['type'];
				}
				// lexical class and info
				if (array_key_exists ( 'definitions', $definitions ) && count ( $definitions ['definitions'] ) <= 0) {
					if ($phraseDefinition ['lex_class'])
						$definitions ['lex_class'] = $phraseDefinition ['lex_class'];
					if ($phraseDefinition ['info'])
						$definitions ['info'] = $phraseDefinition ['info'];
				}
				
				// proverb
				if (array_key_exists ( 'is_proverb', $phraseDefinition )) {
					$proverbIndex = array_key_exists ( 'proverbs', $definitions ) ? count ( $definitions ['proverbs'] ) : 0;
					if ($phraseDefinition ['proverb'])
						$definitions ['proverbs'] [$proverbIndex] ['proverb'] = str_replace ( '--', $phraseDefinition ['phrase'], $phraseDefinition ['proverb'] );
					if ($phraseDefinition ['def'])
						$definitions ['proverbs'] [$proverbIndex] ['def'] = $phraseDefinition ['def'];
				} else {
					// definition
					if (array_key_exists ( 'def', $phraseDefinition )) {
						$definitionIndex = array_key_exists ( 'definitions', $definitions ) ? count ( $definitions ['definitions'] ) : 0;
						$definitions ['definitions'] [$definitionIndex] ['text'] = $phraseDefinition ['def'];
						if (array_key_exists ( 'see', $phraseDefinition )) {
							$definitions ['definitions'] [$definitionIndex] ['see'] = $phraseDefinition ['see'];
						}
						if (array_key_exists ( 'sample', $phraseDefinition )) {
							$definitions ['definitions'] [$definitionIndex] ['sample'] = $phraseDefinition ['sample'];
						}
						if (array_key_exists ( 'lex_class', $phraseDefinition ) && array_key_exists ( 'lex_class', $definitions ) && $phraseDefinition ['lex_class'] != $definitions ['lex_class'])
							$definitions ['definitions'] [$definitionIndex] ['lex_class'] = $phraseDefinition ['lex_class'];
						if (array_key_exists ( 'info', $phraseDefinition ) && array_key_exists ( 'info', $definitions ) && $phraseDefinition ['info'] != $definitions ['info'])
							$definitions ['definitions'] [$definitionIndex] ['info'] = $phraseDefinition ['info'];
					}
				}
			}
		}
		
		return $definitions;
	
	}
	
	public function parseFinalize(array $definitions) {
		
		$lastLexical = '';
		/***** NOT MAKE SENSE !!!! ****/
		// final
		//$i = 0;
		//		foreach ( $definitions as $definitionKey => $definitionValue ) {
		//			// the first one is always an r
		//			if ($i == 0) {
		//				$definitionValue ['type'] == 'r';
		//			}
		//			// affix
		//			if ($i > 0 && $definitionValue ['type'] == 'r') {
		//				$definitionValue ['type'] = 'f';
		//			}
		/***** NOT MAKE SENSE !!!! ****/
		
		$lastType = '???'; // undefined????
		// last type
		if ($definitions ['type'] == 'c' && $lastType == 'f')
			$definitions ['type'] = 'f';
		else {
			$lastType = $definitions ['type'];
		}
		// lexical
		if (array_key_exists ( 'lex_class', $definitions ))
			$lastLexical = $definitions ['lex_class'];
		else {
			if (! empty ( $lastLexical )) {
				$definitions ['lex_class'] = $lastLexical;
			}
		}
		// synonym
		$definitions = $this->parseSynonym ( $definitions );
		// definitions
		$j = 0;
		if ($definitions ['definitions']) {
			foreach ( $definitions ['definitions'] as $definitionItem ) {
				$j ++;
				$definitionItem ['index'] = $j;
			}
		}
		// proverbs
		if (array_key_exists ( 'proverbs', $definitions )) {
			$proverbs = $definitions ['proverbs'];
		}
		// fix rel_type
		if ($definitions ['type'] != 'r') {
			$definitions ['type'] = 'd';
		}
		
		/***** NOT MAKE SENSE !!!! ****/
		// increment
		//			$i ++;
		//		}
		/***** NOT MAKE SENSE !!!! ****/
		
		return $definitions;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $lines
	 * @param kateglo\application\models\Entry $entry
	 */
	public function parseRedirect(Collection $lines, Entry $entry, Meaning $meaning) {
		// try redirect: pair with no space
		$isRedirect = false;
		if ($lines->count () == 1) {
			$redirectString = str_replace ( '&#183;', '', strip_tags ( $lines->get ( 0 ) ) );
			$redirectPair = explode ( '?', $redirectString );
			if (count ( $redirectPair ) == 2) {
				$redirectFrom = trim ( $redirectPair [0] );
				$redirectTo = trim ( $redirectPair [1] );
				$isRedirect = (strpos ( $redirectFrom, ' ' ) === false);
				$isRedirect = $isRedirect && (strpos ( $redirectTo, ' ' ) === false);
				$isRedirect = $isRedirect || ($entry->getName () == 'bilau');
				if ($isRedirect) {
					$entryRedirect = new Entry ();
					$meaningRedirect = new Meaning ();
					$entryRedirect->addMeaning ( $meaningRedirect );
					$entryRedirect->setEntry ( $redirectTo );
					$synonym = new Synonym ();
					$synonym->setSynonym ( $meaningRedirect );
					$synonym->setMeaning ( $meaning );
				}
			}
		}
		return $isRedirect;
	}
	
	private function parseInfoLexical($item) {
		if (array_key_exists ( 'info', $item )) {
			$item ['info'] = preg_replace ( '/,$/U', '', $item ['info'] );
			$infos = explode ( ' ', $item ['info'] );
			$lexical = '';
			$other = '';
			foreach ( $infos as $info ) {
				if (in_array ( $info, array ('n', 'v', 'a', 'adv', 'p', 'num', 'pron' ) )) {
					if ($info == 'a')
						$info = 'adj';
					if ($info == 'p')
						$info = 'l';
					$lexical .= $lexical ? ', ' : '';
					$lexical .= $info;
				} else {
					$other .= $other ? ', ' : '';
					$other .= $info;
				}
			}
			if (! empty ( $lexical ))
				$item ['lex_class'] = $lexical;
			if (! empty ( $other ))
				$item ['info'] = $other;
			else
				unset ( $item ['info'] );
		}
		
		return $item;
	}
	
	private function parseSynonym($clean) {
		if ($clean ['definitions']) {
			foreach ( $clean ['definitions'] as $definitionKey => $definition ) {
				$definitionItems = explode ( ';', $definition ['text'] );
				if (is_array ( $definitionItems ) && count ( $definitionItems ) > 0) {
					foreach ( $definitionItems as $definitionItem ) {
						$definitionItem = trim ( $definitionItem );
						$spaceCount = substr_count ( $definitionItem, ' ' );
						if ($spaceCount < 1)
							$clean ['synonyms'] [] = $definitionItem;
					}
				}
			}
		}
		return $clean;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $url
	 * @param string $data
	 * @return string
	 */
	private function getCurl($url, $data) {
		$this->curl->setUrl ( $url );
		$this->curl->setPost ( true );
		$this->curl->setPostFields ( $data );
		$this->curl->setTimeout ( $this->configs->get ()->curl->timeout );
		$this->curl->setProxy ( $this->configs->get ()->curl->proxy );
		$this->curl->setProxyUserPwd ( $this->configs->get ()->curl->proxyUserPwd );
		$this->curl->run ();
		return $this->curl->getResult ();
	}
}

?>