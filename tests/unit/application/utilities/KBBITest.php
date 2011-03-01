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
use kateglo\application\utilities\CURL;
use kateglo\application\configs\Configs;
use kateglo\application\models\Entry;
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
		$this->KBBI->setConfigs ( new Configs () );
		$this->KBBI->setCurl ( new CURL () );
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated KBBITest::tearDown()
		

		$this->KBBI = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
		
	public function testParsePairs(){
		
		$lines = unserialize ( 'a:18:{i:0;s:923:"<b><sup>1</sup>ter&#183;bang</b> <i>v</i> <b>1</b> bergerak atau melayang di udara dng tenaga sayap (tt burung dsb) atau dng tenaga mesin (tt pesawat terbang dsb): <i>burung itu sanggup -- jauh sampai ke pantai;</i> <i>dng lincahnya pesawat kecil itu -- berakrobat di udara;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, daun-daunan, dsb): <i>seng dan atap rumah -- ditiup angin puyuh; pekerja itu mengenakan topeng muka untuk menghindarkan mata dr pasir yg --;</i> <b>3</b> <i>Fis</i> mudah menjadi uap (gas); mudah menguap: <i>minyak --; zat --;</i> <b>4</b> <i>ki</i> hilang lenyap (dicuri orang): <i>sepuluh peti berisi suku cadang mobil -- dr gudang pelabuhan;</i> <b>5</b> <i>ki</i> berlari cepat; <b>6</b> melarikan diri; kabur: <i>mobil yg menabrak anak itu -- dan menghilang;</i> <b>7</b> <i>ki</i> naik pesawat terbang; bepergian dng pesawat terbang: <i>pagi ini rombongan perutusan -- ke Bali;";i:1;s:0:"";i:2;s:127:"--<i> bertumpu hinggap mencekam, pb</i> jika merantau hendaklah menghubungi (mencari) kaum kerabat tempat menumpangkan dirinya;";i:3;s:0:"";i:4;s:418:"--<b> arwah</b> terbang semangat; pingsan; hilang ingatan; sangat terperanjat; --<b> darahnya</b> <i>ki</i> sangat terkejut; --<b> layang</b> terbang dng pesawat buatan dr rangka kayu (kain parasut), yg mula-mula dinaikkan (ditarik) ke atas oleh pesawat, kemudian dilepaskan hingga melayang; --<b> menerkam</b> melompat sambil menangkap: <i>penjaga gawang Brazil -- menerkam bola</i>; --<b> pikiran</b> hilang pikiran;";i:5;s:0:"";i:6;s:76:"<b>ter&#183;bang-ter&#183;bang, ~ hinggap</b> tidak tetap tempat tinggalnya;";i:7;s:0:"";i:8;s:250:"<b>be&#183;ter&#183;bang&#183;an</b> <i>v</i> <b>1</b> terbang ke mana-mana (banyak yg terbang): <i>burung-burung ~ keluar dr sangkar raksasa itu; daun-daun ~ ditiup angin;</i> <b>2</b> berhamburan atau melayang-layang di udara (tt debu, kapuk, dsb);";i:9;s:0:"";i:10;s:194:"<b>me&#183;ner&#183;bangi</b> <i>v</i> terbang melalui ...; menempuh jarak dng pesawat terbang: <i>sewaktu ~ Laut Merah, tampak laut yg biru dan gurun pasir yg kelabu, merah kuning, dan cokelat;";i:11;s:0:"";i:12;s:686:"<b>me&#183;ner&#183;bang&#183;kan</b> <i>v</i> <b>1</b> membiarkan terbang; melepaskan supaya terbang: <i>pd upacara pemakaman itu, selain melepaskan ayam mereka juga ~ beberapa ekor burung merpati;</i> <b>2</b> membawa terbang: <i>burung rajawali itu mencekam anak kambing dan ~ nya ke tengah hutan; hanya pesawat kecil yg dapat ~ kami ke daerah terpencil itu;</i> <b>3</b> mengangkut dng pesawat terbang: <i>helikopter itu telah ~ orang yg luka-luka dr daerah pertempuran;</i> <b>4</b> mengemudikan pesawat terbang: <i>pangeran bersama permaisuri akan pesiar dan ~ kapal terbangnya sendiri;</i> <b>5</b> <i>ki</i> membawa kabur; melarikan: <i>orang yg ~ uang kas itu telah tertangkap;";i:13;s:0:"";i:14;s:138:"<b>pe&#183;ner&#183;bang</b> <i>n</i> pengemudi pesawat terbang; juru terbang: <i>tamat dr akademi penerbangan itu, ia dilantik menjadi ~;";i:15;s:0:"";i:16;s:364:"<b>pe&#183;ner&#183;bang&#183;an</b> <i>n</i> <b>1</b> proses, cara, perbuatan menerbangkan; <b>2</b> perjalanan dng pesawat terbang; lalu lintas dng pesawat terbang; <b>3</b> segala sesuatu yg bertalian dng lalu lintas udara: <i>keberangkatan kapal dapat dilihat pd jadwal ~;</i> <b>4</b> perihal terbang dng pesawat terbang: ~<i> yg dialaminya cukup menyenangkan";i:17;s:48:"<b><sup>2</sup>ter&#183;bang</b> <i>n</i> rebana";}' );
		$result = $this->KBBI->parsePairs( $lines);
		$newResult = $this->KBBI->parseCleansing($result);
		$this->KBBI->parseStandard($newResult, new Entry());
		var_dump($result);
	}
	

}

