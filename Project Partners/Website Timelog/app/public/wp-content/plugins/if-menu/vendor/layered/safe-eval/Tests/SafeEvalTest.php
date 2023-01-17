<?php
namespace Layered\SafeEval\Tests;

use PHPUnit\Framework\TestCase;
use Layered\SafeEval\SafeEval;

class SafeEvalTest extends TestCase {

	public function testSimpleMath() {
		$safeEval = new SafeEval();

		$this->assertEquals(3, $safeEval->evaluate('1 + 2'));
		$this->assertEquals(-1, $safeEval->evaluate('1 - 2'));
		$this->assertEquals(2, $safeEval->evaluate('1 * 2'));
		$this->assertEquals(0.5, $safeEval->evaluate('1 / 2'));

		$this->assertEquals(6, $safeEval->evaluate('1 + 2 + 3'));
		$this->assertEquals(2, $safeEval->evaluate('1 - 2 + 3'));
		$this->assertEquals(0, $safeEval->evaluate('1 + 2 - 3'));
	}

	public function testBooleans() {
		$safeEval = new SafeEval();

		$this->assertTrue($safeEval->evaluate('1 or 1'));
		$this->assertTrue($safeEval->evaluate('1 and 1'));
		$this->assertTrue($safeEval->evaluate('1 and 1 or 0'));
		$this->assertTrue($safeEval->evaluate('1 and 1 or 1'));
		$this->assertTrue($safeEval->evaluate('false and 1 or 1'));
	}

}
