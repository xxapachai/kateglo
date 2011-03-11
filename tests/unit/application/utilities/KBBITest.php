<?php
require_once 'tests/Bootstrap.php';
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
use kateglo\application\utilities\KBBI;
use kateglo\application\models\Entry;
use kateglo\application\configs\interfaces\Configs;
use kateglo\application\utilities\CURL;
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
class KBBITest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var KBBI
	 */
	private $KBBI;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		$this->KBBI = new KBBI ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->KBBI = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
	
	}
	
	public function parse() {
		$entry = '</tbody></table><input type="hidden" ID="PERINTAH2" name="PERINTAH2" value=""><input type="hidden" ID="KATA" name="KATA" value=""><input type="hidden" name="DFTKATA" value="terbang (1);terbang (2)" ><input type="hidden" name="MORE" value="0" ><input type="hidden" name="HEAD" value="0" ></form><br><br>';
		
		$definition1 = '<table width="100%" border="1"><tr><td><br><p style=\'margin-left:.5in;text-indent:-.5in\'><b><sup>1</sup>ter&#183;bang</b> <i>v</i> <b>1</b> bergerak atau melayang di udara dng tenaga sayap (tt burung dsb) atau dng tenaga mesin (tt pesawat terbang dsb): <i>burung itu sanggup -- jauh sampai ke pantai;</i> <i>dng lincahnya pesawat kecil itu -- berakrobat di udara;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, daun-daunan, dsb): <i>seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dr pasir yg --;</i> <b>3</b> <i>Fis</i> mudah menjadi uap (gas); mudah menguap: <i>minyak --; zat --;</i> <b>4</b> <i>ki</i> hilang lenyap (dicuri orang): <i>sepuluh peti berisi suku cadang mobil -- dr gudang pelabuhan;</i> <b>5</b> <i>ki</i> berlari cepat; <b>6</b> melarikan diri; kabur: <i>mobil yg menabrak anak itu -- dan menghilang;</i> <b>7</b> <i>ki</i> naik pesawat terbang; bepergian dng pesawat terbang: <i>pagi ini rombongan perutusan -- ke Bali;<br></i>--<i> bertumpu hinggap mencekam, pb</i> jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya;<br>--<b> arwah</b> terbang semangat; pingsan; hilang ingatan; sangat terperanjat; --<b> darahnya</b> <i>ki</i> sangat terkejut; --<b> layang</b> terbang dng pesawat buatan dr rangka kayu (kain parasut), yg mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang; --<b> menerkam</b> melompat sambil menangkap: <i>penjaga gawang Brazil -- menerkam bola</i>; --<b> pikiran</b> hilang pikiran;<br><b>ter&#183;bang-ter&#183;bang, ~ hinggap</b> tidak tetap tempat tinggalnya;<br><b>be&#183;ter&#183;bang&#183;an</b> <i>v</i> <b>1</b> terbang ke mana-mana (banyak yg terbang): <i>burung-burung ~ keluar dr sangkar raksasa itu; daun-daun ~ ditiup angin;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, kapuk, dsb);<br><b>me&#183;ner&#183;bangi</b> <i>v</i> terbang melalui ...; menempuh jarak dng pesawat terbang: <i>sewaktu ~ Laut Merah, tampak laut yg biru dan gurun pasir yg kelabu, merah kuning, dan cokelat;<br></i><b>me&#183;ner&#183;bang&#183;kan</b> <i>v</i> <b>1</b> membiarkan terbang; melepaskan supaya terbang: <i>pd upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati;</i> <b>2</b> membawa terbang: <i>burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yg dapat ~ kami ke daerah terpencil itu;</i> <b>3</b> mengangkut dng pesawat terbang: <i>helikopter itu telah ~ orang yg luka-luka dr daerah pertempuran;</i> <b>4</b> mengemudikan pesawat terbang: <i>pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri;</i> <b>5</b> <i>ki</i> membawa kabur; melarikan: <i>orang yg ~ uang kas itu telah tertangkap;<br></i><b>pe&#183;ner&#183;bang</b> <i>n</i> pengemudi pesawat terbang; juru terbang: <i>tamat dr akademi penerbangan itu, ia dilantik menjadi ~;<br></i><b>pe&#183;ner&#183;bang&#183;an</b> <i>n</i> <b>1</b> proses, cara, perbuatan menerbangkan; <b>2</b> perjalanan dng pesawat terbang; lalu lintas dng pesawat terbang; <b>3</b> segala sesuatu yg bertalian dng lalu lintas udara: <i>keberangkatan kapal dapat dilihat pd jadwal ~;</i> <b>4</b> perihal terbang dng pesawat terbang: ~<i> yg dialaminya cukup menyenangkan</p><br><br><br><br><br></td></tr></table></td></tr></tbody></table>';
		$definition2 = '<table width="100%" border="1"><tr><td><br><p style=\'margin-left:.5in;text-indent:-.5in\'><b><sup>2</sup>ter&#183;bang</b> <i>n</i> rebana</p><br><br><br><br><br></td></tr></table></td></tr></tbody></table>';
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @test
	 */
	public function parseStandard() {
		
		$lines = unserialize ( 'a:18:{i:0;s:923:"<b><sup>1</sup>ter&#183;bang</b> <i>v</i> <b>1</b> bergerak atau melayang di udara dng tenaga sayap (tt burung dsb) atau dng tenaga mesin (tt pesawat terbang dsb): <i>burung itu sanggup -- jauh sampai ke pantai;</i> <i>dng lincahnya pesawat kecil itu -- berakrobat di udara;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, daun-daunan, dsb): <i>seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dr pasir yg --;</i> <b>3</b> <i>Fis</i> mudah menjadi uap (gas); mudah menguap: <i>minyak --; zat --;</i> <b>4</b> <i>ki</i> hilang lenyap (dicuri orang): <i>sepuluh peti berisi suku cadang mobil -- dr gudang pelabuhan;</i> <b>5</b> <i>ki</i> berlari cepat; <b>6</b> melarikan diri; kabur: <i>mobil yg menabrak anak itu -- dan menghilang;</i> <b>7</b> <i>ki</i> naik pesawat terbang; bepergian dng pesawat terbang: <i>pagi ini rombongan perutusan -- ke Bali;";i:1;s:0:"";i:2;s:127:"--<i> bertumpu hinggap mencekam, pb</i> jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya;";i:3;s:0:"";i:4;s:418:"--<b> arwah</b> terbang semangat; pingsan; hilang ingatan; sangat terperanjat; --<b> darahnya</b> <i>ki</i> sangat terkejut; --<b> layang</b> terbang dng pesawat buatan dr rangka kayu (kain parasut), yg mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang; --<b> menerkam</b> melompat sambil menangkap: <i>penjaga gawang Brazil -- menerkam bola</i>; --<b> pikiran</b> hilang pikiran;";i:5;s:0:"";i:6;s:76:"<b>ter&#183;bang-ter&#183;bang, ~ hinggap</b> tidak tetap tempat tinggalnya;";i:7;s:0:"";i:8;s:250:"<b>be&#183;ter&#183;bang&#183;an</b> <i>v</i> <b>1</b> terbang ke mana-mana (banyak yg terbang): <i>burung-burung ~ keluar dr sangkar raksasa itu; daun-daun ~ ditiup angin;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, kapuk, dsb);";i:9;s:0:"";i:10;s:194:"<b>me&#183;ner&#183;bangi</b> <i>v</i> terbang melalui ...; menempuh jarak dng pesawat terbang: <i>sewaktu ~ Laut Merah, tampak laut yg biru dan gurun pasir yg kelabu, merah kuning, dan cokelat;";i:11;s:0:"";i:12;s:686:"<b>me&#183;ner&#183;bang&#183;kan</b> <i>v</i> <b>1</b> membiarkan terbang; melepaskan supaya terbang: <i>pd upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati;</i> <b>2</b> membawa terbang: <i>burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yg dapat ~ kami ke daerah terpencil itu;</i> <b>3</b> mengangkut dng pesawat terbang: <i>helikopter itu telah ~ orang yg luka-luka dr daerah pertempuran;</i> <b>4</b> mengemudikan pesawat terbang: <i>pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri;</i> <b>5</b> <i>ki</i> membawa kabur; melarikan: <i>orang yg ~ uang kas itu telah tertangkap;";i:13;s:0:"";i:14;s:138:"<b>pe&#183;ner&#183;bang</b> <i>n</i> pengemudi pesawat terbang; juru terbang: <i>tamat dr akademi penerbangan itu, ia dilantik menjadi ~;";i:15;s:0:"";i:16;s:364:"<b>pe&#183;ner&#183;bang&#183;an</b> <i>n</i> <b>1</b> proses, cara, perbuatan menerbangkan; <b>2</b> perjalanan dng pesawat terbang; lalu lintas dng pesawat terbang; <b>3</b> segala sesuatu yg bertalian dng lalu lintas udara: <i>keberangkatan kapal dapat dilihat pd jadwal ~;</i> <b>4</b> perihal terbang dng pesawat terbang: ~<i> yg dialaminya cukup menyenangkan";i:17;s:48:"<b><sup>2</sup>ter&#183;bang</b> <i>n</i> rebana";}' );
		$this->KBBI->parseStandard ( $lines, new Entry () );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @test
	 */
	public function parseCleansing() {
		$temporaryPair = unserialize ( 'a:10:{i:0;a:8:{i:0;a:3:{s:6:"phrase";s:7:"terbang";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:5:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:111:"bergerak atau melayang di udara dng tenaga sayap (tt burung dsb) atau dng tenaga mesin (tt pesawat terbang dsb)";s:6:"sample";s:100:"burung itu sanggup -- jauh sampai ke pantai; dng lincahnya pesawat kecil itu -- berakrobat di udara;";s:4:"type";s:1:"r";}i:2;a:5:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:69:"berhamburan atau melayang-layang di udara (tt debu, daun-daunan, dsb)";s:6:"sample";s:118:"seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dr pasir yg --;";s:4:"type";s:1:"r";}i:3;a:6:{s:5:"index";s:1:"3";s:4:"info";s:3:"Fis";s:6:"phrase";s:0:"";s:3:"def";s:38:"mudah menjadi uap (gas); mudah menguap";s:6:"sample";s:18:"minyak --; zat --;";s:4:"type";s:1:"r";}i:4;a:6:{s:5:"index";s:1:"4";s:4:"info";s:2:"ki";s:6:"phrase";s:0:"";s:3:"def";s:28:"hilang lenyap (dicuri orang)";s:6:"sample";s:61:"sepuluh peti berisi suku cadang mobil -- dr gudang pelabuhan;";s:4:"type";s:1:"r";}i:5;a:5:{s:5:"index";s:1:"5";s:4:"info";s:2:"ki";s:6:"phrase";s:0:"";s:3:"def";s:14:"berlari cepat;";s:4:"type";s:1:"r";}i:6;a:5:{s:5:"index";s:1:"6";s:6:"phrase";s:0:"";s:3:"def";s:21:"melarikan diri; kabur";s:6:"sample";s:45:"mobil yg menabrak anak itu -- dan menghilang;";s:4:"type";s:1:"r";}i:7;a:6:{s:5:"index";s:1:"7";s:4:"info";s:2:"ki";s:6:"phrase";s:0:"";s:3:"def";s:51:"naik pesawat terbang; bepergian dng pesawat terbang";s:6:"sample";s:40:"pagi ini rombongan perutusan -- ke Bali;";s:4:"type";s:1:"r";}}i:2;a:1:{i:0;a:3:{s:7:"proverb";s:28:"-- bertumpu hinggap mencekam";s:3:"def";s:87:"jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya;";s:10:"is_proverb";b:1;}}i:4;a:5:{i:0;a:3:{s:6:"phrase";s:8:"-- arwah";s:3:"def";s:62:"terbang semangat; pingsan; hilang ingatan; sangat terperanjat;";s:4:"type";s:1:"c";}i:1;a:4:{s:6:"phrase";s:11:"-- darahnya";s:4:"info";s:2:"ki";s:3:"def";s:16:"sangat terkejut;";s:4:"type";s:1:"c";}i:2;a:3:{s:6:"phrase";s:9:"-- layang";s:3:"def";s:149:"terbang dng pesawat buatan dr rangka kayu (kain parasut), yg mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang;";s:4:"type";s:1:"c";}i:3;a:4:{s:6:"phrase";s:11:"-- menerkam";s:3:"def";s:25:"melompat sambil menangkap";s:6:"sample";s:39:"penjaga gawang Brazil -- menerkam bola;";s:4:"type";s:1:"c";}i:4;a:3:{s:6:"phrase";s:10:"-- pikiran";s:3:"def";s:15:"hilang pikiran;";s:4:"type";s:1:"c";}}i:6;a:1:{i:0;a:3:{s:6:"phrase";s:15:"terbang-terbang";s:3:"def";s:30:"tidak tetap tempat tinggalnya;";s:4:"type";s:1:"c";}}i:8;a:3:{i:0;a:3:{s:6:"phrase";s:11:"beterbangan";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:5:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:40:"terbang ke mana-mana (banyak yg terbang)";s:6:"sample";s:72:"burung-burung ~ keluar dr sangkar raksasa itu; daun-daun ~ ditiup angin;";s:4:"type";s:1:"r";}i:2;a:4:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:64:"berhamburan atau melayang-layang di udara (tt debu, kapuk, dsb);";s:4:"type";s:1:"r";}}i:10;a:1:{i:0;a:5:{s:6:"phrase";s:10:"menerbangi";s:4:"info";s:1:"v";s:3:"def";s:55:"terbang melalui ...; menempuh jarak dng pesawat terbang";s:6:"sample";s:95:"sewaktu ~ Laut Merah, tampak laut yg biru dan gurun pasir yg kelabu, merah kuning, dan cokelat;";s:4:"type";s:1:"r";}}i:12;a:6:{i:0;a:3:{s:6:"phrase";s:12:"menerbangkan";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:5:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:45:"membiarkan terbang; melepaskan supaya terbang";s:6:"sample";s:92:"pd upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati;";s:4:"type";s:1:"r";}i:2;a:5:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:15:"membawa terbang";s:6:"sample";s:129:"burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yg dapat ~ kami ke daerah terpencil itu;";s:4:"type";s:1:"r";}i:3;a:5:{s:5:"index";s:1:"3";s:6:"phrase";s:0:"";s:3:"def";s:30:"mengangkut dng pesawat terbang";s:6:"sample";s:64:"helikopter itu telah ~ orang yg luka-luka dr daerah pertempuran;";s:4:"type";s:1:"r";}i:4;a:5:{s:5:"index";s:1:"4";s:6:"phrase";s:0:"";s:3:"def";s:28:"mengemudikan pesawat terbang";s:6:"sample";s:71:"pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri;";s:4:"type";s:1:"r";}i:5;a:6:{s:5:"index";s:1:"5";s:4:"info";s:2:"ki";s:6:"phrase";s:0:"";s:3:"def";s:24:"membawa kabur; melarikan";s:6:"sample";s:41:"orang yg ~ uang kas itu telah tertangkap;";s:4:"type";s:1:"r";}}i:14;a:1:{i:0;a:5:{s:6:"phrase";s:9:"penerbang";s:4:"info";s:1:"n";s:3:"def";s:39:"pengemudi pesawat terbang; juru terbang";s:6:"sample";s:56:"tamat dr akademi penerbangan itu, ia dilantik menjadi ~;";s:4:"type";s:1:"r";}}i:16;a:5:{i:0;a:3:{s:6:"phrase";s:11:"penerbangan";s:4:"info";s:1:"n";s:4:"type";s:1:"r";}i:1;a:4:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:37:"proses, cara, perbuatan menerbangkan;";s:4:"type";s:1:"r";}i:2;a:4:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:64:"perjalanan dng pesawat terbang; lalu lintas dng pesawat terbang;";s:4:"type";s:1:"r";}i:3;a:5:{s:5:"index";s:1:"3";s:6:"phrase";s:0:"";s:3:"def";s:49:"segala sesuatu yg bertalian dng lalu lintas udara";s:6:"sample";s:46:"keberangkatan kapal dapat dilihat pd jadwal ~;";s:4:"type";s:1:"r";}i:4;a:5:{s:5:"index";s:1:"4";s:6:"phrase";s:0:"";s:3:"def";s:35:"perihal terbang dng pesawat terbang";s:6:"sample";s:34:"~ yg dialaminya cukup menyenangkan";s:4:"type";s:1:"r";}}i:17;a:1:{i:0;a:4:{s:6:"phrase";s:7:"terbang";s:4:"info";s:1:"n";s:3:"def";s:6:"rebana";s:4:"type";s:1:"r";}}}' );
		$this->KBBI->parseCleansing ( $temporaryPair );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @test
	 */
	public function parseDefinitions() {
		$temporaryPair = unserialize ( 'a:10:{i:0;a:8:{i:0;a:3:{s:6:"phrase";s:7:"terbang";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:4:{s:5:"index";s:1:"1";s:3:"def";s:111:"bergerak atau melayang di udara dng tenaga sayap (tt burung dsb) atau dng tenaga mesin (tt pesawat terbang dsb)";s:6:"sample";s:100:"burung itu sanggup -- jauh sampai ke pantai; dng lincahnya pesawat kecil itu -- berakrobat di udara;";s:4:"type";s:1:"r";}i:2;a:4:{s:5:"index";s:1:"2";s:3:"def";s:69:"berhamburan atau melayang-layang di udara (tt debu, daun-daunan, dsb)";s:6:"sample";s:118:"seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dr pasir yg --;";s:4:"type";s:1:"r";}i:3;a:5:{s:5:"index";s:1:"3";s:4:"info";s:3:"Fis";s:3:"def";s:38:"mudah menjadi uap (gas); mudah menguap";s:6:"sample";s:18:"minyak --; zat --;";s:4:"type";s:1:"r";}i:4;a:5:{s:5:"index";s:1:"4";s:4:"info";s:2:"ki";s:3:"def";s:28:"hilang lenyap (dicuri orang)";s:6:"sample";s:61:"sepuluh peti berisi suku cadang mobil -- dr gudang pelabuhan;";s:4:"type";s:1:"r";}i:5;a:4:{s:5:"index";s:1:"5";s:4:"info";s:2:"ki";s:3:"def";s:14:"berlari cepat;";s:4:"type";s:1:"r";}i:6;a:4:{s:5:"index";s:1:"6";s:3:"def";s:21:"melarikan diri; kabur";s:6:"sample";s:45:"mobil yg menabrak anak itu -- dan menghilang;";s:4:"type";s:1:"r";}i:7;a:5:{s:5:"index";s:1:"7";s:4:"info";s:2:"ki";s:3:"def";s:51:"naik pesawat terbang; bepergian dng pesawat terbang";s:6:"sample";s:40:"pagi ini rombongan perutusan -- ke Bali;";s:4:"type";s:1:"r";}}i:2;a:1:{i:0;a:3:{s:7:"proverb";s:28:"-- bertumpu hinggap mencekam";s:3:"def";s:87:"jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya;";s:10:"is_proverb";b:1;}}i:4;a:5:{i:0;a:3:{s:6:"phrase";s:8:"-- arwah";s:3:"def";s:62:"terbang semangat; pingsan; hilang ingatan; sangat terperanjat;";s:4:"type";s:1:"c";}i:1;a:4:{s:6:"phrase";s:11:"-- darahnya";s:4:"info";s:2:"ki";s:3:"def";s:16:"sangat terkejut;";s:4:"type";s:1:"c";}i:2;a:3:{s:6:"phrase";s:9:"-- layang";s:3:"def";s:149:"terbang dng pesawat buatan dr rangka kayu (kain parasut), yg mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang;";s:4:"type";s:1:"c";}i:3;a:4:{s:6:"phrase";s:11:"-- menerkam";s:3:"def";s:25:"melompat sambil menangkap";s:6:"sample";s:39:"penjaga gawang Brazil -- menerkam bola;";s:4:"type";s:1:"c";}i:4;a:3:{s:6:"phrase";s:10:"-- pikiran";s:3:"def";s:15:"hilang pikiran;";s:4:"type";s:1:"c";}}i:6;a:1:{i:0;a:3:{s:6:"phrase";s:15:"terbang-terbang";s:3:"def";s:30:"tidak tetap tempat tinggalnya;";s:4:"type";s:1:"c";}}i:8;a:3:{i:0;a:3:{s:6:"phrase";s:11:"beterbangan";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:4:{s:5:"index";s:1:"1";s:3:"def";s:40:"terbang ke mana-mana (banyak yg terbang)";s:6:"sample";s:72:"burung-burung ~ keluar dr sangkar raksasa itu; daun-daun ~ ditiup angin;";s:4:"type";s:1:"r";}i:2;a:3:{s:5:"index";s:1:"2";s:3:"def";s:64:"berhamburan atau melayang-layang di udara (tt debu, kapuk, dsb);";s:4:"type";s:1:"r";}}i:10;a:1:{i:0;a:5:{s:6:"phrase";s:10:"menerbangi";s:4:"info";s:1:"v";s:3:"def";s:55:"terbang melalui ...; menempuh jarak dng pesawat terbang";s:6:"sample";s:95:"sewaktu ~ Laut Merah, tampak laut yg biru dan gurun pasir yg kelabu, merah kuning, dan cokelat;";s:4:"type";s:1:"r";}}i:12;a:6:{i:0;a:3:{s:6:"phrase";s:12:"menerbangkan";s:4:"info";s:1:"v";s:4:"type";s:1:"r";}i:1;a:5:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:45:"membiarkan terbang; melepaskan supaya terbang";s:6:"sample";s:92:"pd upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati;";s:4:"type";s:1:"r";}i:2;a:5:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:15:"membawa terbang";s:6:"sample";s:129:"burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yg dapat ~ kami ke daerah terpencil itu;";s:4:"type";s:1:"r";}i:3;a:5:{s:5:"index";s:1:"3";s:6:"phrase";s:0:"";s:3:"def";s:30:"mengangkut dng pesawat terbang";s:6:"sample";s:64:"helikopter itu telah ~ orang yg luka-luka dr daerah pertempuran;";s:4:"type";s:1:"r";}i:4;a:5:{s:5:"index";s:1:"4";s:6:"phrase";s:0:"";s:3:"def";s:28:"mengemudikan pesawat terbang";s:6:"sample";s:71:"pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri;";s:4:"type";s:1:"r";}i:5;a:6:{s:5:"index";s:1:"5";s:4:"info";s:2:"ki";s:6:"phrase";s:0:"";s:3:"def";s:24:"membawa kabur; melarikan";s:6:"sample";s:41:"orang yg ~ uang kas itu telah tertangkap;";s:4:"type";s:1:"r";}}i:14;a:1:{i:0;a:5:{s:6:"phrase";s:9:"penerbang";s:4:"info";s:1:"n";s:3:"def";s:39:"pengemudi pesawat terbang; juru terbang";s:6:"sample";s:56:"tamat dr akademi penerbangan itu, ia dilantik menjadi ~;";s:4:"type";s:1:"r";}}i:16;a:5:{i:0;a:3:{s:6:"phrase";s:11:"penerbangan";s:4:"info";s:1:"n";s:4:"type";s:1:"r";}i:1;a:4:{s:5:"index";s:1:"1";s:6:"phrase";s:0:"";s:3:"def";s:37:"proses, cara, perbuatan menerbangkan;";s:4:"type";s:1:"r";}i:2;a:4:{s:5:"index";s:1:"2";s:6:"phrase";s:0:"";s:3:"def";s:64:"perjalanan dng pesawat terbang; lalu lintas dng pesawat terbang;";s:4:"type";s:1:"r";}i:3;a:5:{s:5:"index";s:1:"3";s:6:"phrase";s:0:"";s:3:"def";s:49:"segala sesuatu yg bertalian dng lalu lintas udara";s:6:"sample";s:46:"keberangkatan kapal dapat dilihat pd jadwal ~;";s:4:"type";s:1:"r";}i:4;a:5:{s:5:"index";s:1:"4";s:6:"phrase";s:0:"";s:3:"def";s:35:"perihal terbang dng pesawat terbang";s:6:"sample";s:34:"~ yg dialaminya cukup menyenangkan";s:4:"type";s:1:"r";}}i:17;a:1:{i:0;a:4:{s:6:"phrase";s:7:"terbang";s:4:"info";s:1:"n";s:3:"def";s:6:"rebana";s:4:"type";s:1:"r";}}}' );
		$this->KBBI->parseDefinitions ( $temporaryPair );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @test
	 */
	public function parseFinalize() {
		$definitions = unserialize ( 'a:3:{s:4:"type";s:1:"r";s:11:"definitions";a:27:{i:0;a:2:{s:4:"text";s:127:"bergerak atau melayang di udara dengan tenaga sayap (tentang burung dsb) atau dengan tenaga mesin (tentang pesawat terbang dsb)";s:6:"sample";s:102:"burung itu sanggup -- jauh sampai ke pantai; dengan lincahnya pesawat kecil itu -- berakrobat di udara";}i:1;a:2:{s:4:"text";s:74:"berhamburan atau melayang-layang di udara (tentang debu, daun-daunan, dsb)";s:6:"sample";s:121:"seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dari pasir yang --";}i:2;a:2:{s:4:"text";s:38:"mudah menjadi uap (gas); mudah menguap";s:6:"sample";s:17:"minyak --; zat --";}i:3;a:2:{s:4:"text";s:28:"hilang lenyap (dicuri orang)";s:6:"sample";s:62:"sepuluh peti berisi suku cadang mobil -- dari gudang pelabuhan";}i:4;a:1:{s:4:"text";s:13:"berlari cepat";}i:5;a:2:{s:4:"text";s:21:"melarikan diri; kabur";s:6:"sample";s:46:"mobil yang menabrak anak itu -- dan menghilang";}i:6;a:2:{s:4:"text";s:54:"naik pesawat terbang; bepergian dengan pesawat terbang";s:6:"sample";s:39:"pagi ini rombongan perutusan -- ke Bali";}i:7;a:1:{s:4:"text";s:61:"terbang semangat; pingsan; hilang ingatan; sangat terperanjat";}i:8;a:1:{s:4:"text";s:15:"sangat terkejut";}i:9;a:1:{s:4:"text";s:155:"terbang dengan pesawat buatan dari rangka kayu (kain parasut), yang mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang";}i:10;a:2:{s:4:"text";s:25:"melompat sambil menangkap";s:6:"sample";s:38:"penjaga gawang Brazil -- menerkam bola";}i:11;a:1:{s:4:"text";s:14:"hilang pikiran";}i:12;a:1:{s:4:"text";s:29:"tidak tetap tempat tinggalnya";}i:13;a:2:{s:4:"text";s:42:"terbang ke mana-mana (banyak yang terbang)";s:6:"sample";s:73:"burung-burung ~ keluar dari sangkar raksasa itu; daun-daun ~ ditiup angin";}i:14;a:1:{s:4:"text";s:68:"berhamburan atau melayang-layang di udara (tentang debu, kapuk, dsb)";}i:15;a:2:{s:4:"text";s:58:"terbang melalui ...; menempuh jarak dengan pesawat terbang";s:6:"sample";s:98:"sewaktu ~ Laut Merah, tampak laut yang biru dan gurun pasir yang kelabu, merah kuning, dan cokelat";}i:16;a:2:{s:4:"text";s:45:"membiarkan terbang; melepaskan supaya terbang";s:6:"sample";s:93:"pada upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati";}i:17;a:2:{s:4:"text";s:15:"membawa terbang";s:6:"sample";s:130:"burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yang dapat ~ kami ke daerah terpencil itu";}i:18;a:2:{s:4:"text";s:33:"mengangkut dengan pesawat terbang";s:6:"sample";s:67:"helikopter itu telah ~ orang yang luka-luka dari daerah pertempuran";}i:19;a:2:{s:4:"text";s:28:"mengemudikan pesawat terbang";s:6:"sample";s:70:"pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri";}i:20;a:2:{s:4:"text";s:24:"membawa kabur; melarikan";s:6:"sample";s:42:"orang yang ~ uang kas itu telah tertangkap";}i:21;a:2:{s:4:"text";s:39:"pengemudi pesawat terbang; juru terbang";s:6:"sample";s:57:"tamat dari akademi penerbangan itu, ia dilantik menjadi ~";}i:22;a:1:{s:4:"text";s:36:"proses, cara, perbuatan menerbangkan";}i:23;a:1:{s:4:"text";s:69:"perjalanan dengan pesawat terbang; lalu lintas dengan pesawat terbang";}i:24;a:2:{s:4:"text";s:54:"segala sesuatu yang bertalian dengan lalu lintas udara";s:6:"sample";s:47:"keberangkatan kapal dapat dilihat pada jadwal ~";}i:25;a:2:{s:4:"text";s:38:"perihal terbang dengan pesawat terbang";s:6:"sample";s:36:"~ yang dialaminya cukup menyenangkan";}i:26;a:1:{s:4:"text";s:6:"rebana";}}s:8:"proverbs";a:1:{i:0;a:2:{s:7:"proverb";s:26:" bertumpu hinggap mencekam";s:3:"def";s:86:"jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya";}}}' );
		$this->KBBI->parseFinalize ( $definitions );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @test
	 */
	public function getCurl() {
		$config = new stdClass ();
		$config->curl = new stdClass ();
		$config->curl->timeout = 5;
		$config->curl->proxy = 'proxyMock';
		$config->curl->proxyUserPwd = 'UserPwdMock';
		
		$stubConfigs = $this->getMock ( Configs::INTERFACE_NAME );
		$stubConfigs->expects ( $this->any () )->method ( 'get' )->will ( $this->returnValue ( $config ) );
		
		$mockCurl = $this->getMock ( CURL::$CLASS_NAME, array ('setUrl', 'setPost', 'setPostFields', 'setTimeout', 'setProxy', 'setProxyUserPwd', 'run', 'getResult' ) );
		$mockCurl->expects ( $this->once () )->method ( 'setUrl' )->with ( $this->equalTo ( 'some URL' ) );
		$mockCurl->expects ( $this->once () )->method ( 'setPost' )->with ( $this->equalTo ( true ) );
		$mockCurl->expects ( $this->once () )->method ( 'setPostFields' )->with ( $this->equalTo ( 'some data' ) );
		$mockCurl->expects ( $this->once () )->method ( 'setTimeout' )->with ( $this->equalTo ( 5 ) );
		$mockCurl->expects ( $this->once () )->method ( 'setProxy' )->with ( $this->equalTo ( 'proxyMock' ) );
		$mockCurl->expects ( $this->once () )->method ( 'setProxyUserPwd' )->with ( $this->equalTo ( 'UserPwdMock' ) );
		$mockCurl->expects ( $this->once () )->method ( 'run' )->will ( $this->returnValue ( 'nothing' ) );
		$mockCurl->expects ( $this->once () )->method ( 'getResult' )->will ( $this->returnValue ( 'mock result' ) );
		
		$this->KBBI->setConfigs ( $stubConfigs );
		$this->KBBI->setCurl ( $mockCurl );
		
		$this->assertEquals ( 'mock result', $this->KBBI->getCurl ( 'some URL', 'some data' ) );
	}

}

