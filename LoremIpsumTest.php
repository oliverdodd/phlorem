<?php
require_once 'PHPUnit/Framework.php';
require_once 'LoremIpsum.php';

class LoremIpsumTest extends PHPUnit_Framework_TestCase {

	private $loremIpsum;
	private $lipsum = "Lorem ipsum dolor sit amet";

	protected function setUp() {
		$this->loremIpsum = new LoremIpsum();
	}

	private function checkString($s) {
		echo "\nDEBUG: $s\n";
		$this->assertNotNull($s);
		$this->assertNotSame("", $s);
	}

	private function countChunks($s, $d) {
		return count(preg_split($d,$s));
	}

	private function countWords($s) {
		return $this->countChunks($s, "/[\s]/");
	}

	private function countSentences($s) {
		return $this->countChunks($s, "/[.?]/") - 1;
	}

	private function countParagraphs($s) {
		return $this->countChunks($s, "/\n\n/m");
	}
	
	private function randomint() {
		 return rand(1,20);
	}

	/** @test */
	public function randomWord() {
		$this->checkString($this->loremIpsum->randomWord());
	}

	/** @test */
	public function randomPunctuation() {
		$this->checkString($this->loremIpsum->randomPunctuation());
	}

	/** @test */
	public function words() {
		$n = $this->randomint();
		$s = $this->loremIpsum->words($n);
		$this->checkString($s);
		$c = $this->countWords($s);
		$this->assertEquals($n, $c, "expecting " . $n . " words, counted " . $c);
	}

	/** @test */
	public function sentenceFragment() {
		$m = 3;
		$s = $this->loremIpsum->sentenceFragment();
		$this->checkString($s);
		$c = $this->countWords($s);
		$this->assertTrue($c >= $m, "expecting >= " . $m . " words, counted " . $c);
	}

	/** @test */
	public function sentence() {
		$m = 3;
		$s = $this->loremIpsum->sentence();
		$this->checkString($s);
		$c = $this->countWords($s);
		$this->assertTrue($c >= $m, "expecting >= " . $m . " words, counted " . $c);
		$this->assertTrue($s[0] == strtoupper($s[0]), "First letter uppercase");
		$e = $s[strlen($s) - 1];
		$this->assertTrue($e == "." || $e == "?", "Ends with punctuation ($e)");
	}

	/** @test */
	public function sentences_count() {
		$n = $this->randomint();
		$s = $this->loremIpsum->sentences($n);
		$this->checkString($s);
		$c = $this->countSentences($s);
		$this->assertEquals($n, $c, "expecting " . $n . " sentences, counted " . $c);
	}

	/** @test */
	public function paragraph_useStandard() {
		$m = 2;
		$s = $this->loremIpsum->paragraph(true);
		$this->checkString($s);
		$c = $this->countSentences($s);
		$this->assertTrue($c > $m, "expecting > " . $m . " sentences, counted " . $c);
		$this->assertTrue(substr($s,0,strlen($this->lipsum)) == $this->lipsum,
			"Starts with $this->lipsum");
	}

	/** @test */
	public function paragraph() {
		$m = 2;
		$s = $this->loremIpsum->paragraph();
		$this->checkString($s);
		$c = $this->countSentences($s);
		$this->assertTrue($c >= $m, "expecting >= " . $m . " sentences, counted " . $c);
	}

	/** @test */
	public function paragraphs_count_useStandard() {
		$n = $this->randomint();
		$s = $this->loremIpsum->paragraphs($n, true);
		$this->checkString($s);
		$c = $this->countParagraphs($s);
		$this->assertEquals($n, $c, "expecting " . $n . " paragraphs, counted " . $c);
		$this->assertTrue(substr($s,0,strlen($this->lipsum)) == $this->lipsum,
			"Starts with $this->lipsum");
	}

	/** @test */
	public function paragraphs_count() {
		$n = $this->randomint();
		$s = $this->loremIpsum->paragraphs($n);
		$this->checkString($s);
		$c = $this->countParagraphs($s);
		$this->assertEquals($n, $c, "expecting " . $n . " paragraphs, counted " . $c);
	}
}
?>