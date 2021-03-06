<?php
use PHPUnit\Framework\TestCase;
define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );

class SellsyCustomFieldsControllerTest extends TestCase
{
	/**
	 * @group cfSimpleText
	 */
	public function testCheckSimpleTextWithMinMaxEmpty()
	{
		// INIT
		$name       = "Test";
		$default    = "Lorem ipsum";
		$min        = "";
		$max        = "";
		$f_value      = "abc";
		$f_value_size = strlen($f_value);




		// 1
		$a = new \fr\eewee\eewee_sellsy\controllers\SellsyCustomFieldsController();
		$b = $a->checkSimpleText([
			"api" => [
				"label"     => $name,
				"default"   => $default,
				"min"       => $min,
				"max"       => $max,
			],
			"form" => [
				"value" => ""
			]
		]);
		$this->assertSame(["success", $default], $b);




		// 2
		$a = new \fr\eewee\eewee_sellsy\controllers\SellsyCustomFieldsController();
		$b = $a->checkSimpleText([
			"api" => [
				"label"     => $name,
				"default"   => $default,
				"min"       => $min,
				"max"       => $max,
			],
			"form" => [
				"value" => "abc"
			]
		]);
		$this->assertSame(["success", $f_value], $b);
	}

	/**
	 * @group cfSimpleText
	 */
	public function testCheckSimpleTextWithValueOk()
	{
		// INIT
		$name       = "Test";
		$default    = "Lorem ipsum";
		$min        = 5;
		$max        = 20;
		$f_value      = "abcabc";
		$f_value_size = strlen($f_value);

		$a = new \fr\eewee\eewee_sellsy\controllers\SellsyCustomFieldsController();
		$b = $a->checkSimpleText([
			"api" => [
				"label"     => $name,
				"default"   => $default,
				"min"       => $min,
				"max"       => $max,
			],
			"form" => [
				"value" => $f_value
			]
		]);
		$this->assertGreaterThanOrEqual($min, $f_value_size);
		$this->assertLessThanOrEqual($max, $f_value_size);
		$this->assertSame(["success", $f_value], $b);
	}

	/**
	 * @group cfSimpleText
	 */
	public function testCheckSimpleTextWithValueTooSmall()
	{
		// INIT
		$name       = "Test";
		$default    = "Lorem ipsum";
		$min        = 5;
		$max        = 20;
		$f_value      = "abc";
		$f_value_size = strlen($f_value);

		$a = new \fr\eewee\eewee_sellsy\controllers\SellsyCustomFieldsController();
		$b = $a->checkSimpleText([
			"api" => [
				"label"     => $name,
				"default"   => $default,
				"min"       => $min,
				"max"       => $max,
			],
			"form" => [
				"value" => $f_value
			]
		]);
		$this->assertLessThanOrEqual($min, $f_value_size);
		$this->assertSame(["error", "Your value for ".$name." is too small."], $b);
	}

	/**
	 * @group cfSimpleText
	 */
	public function testCheckSimpleTextWithValueTooBig()
	{
		// INIT
		$name       = "Test";
		$default    = "Lorem ipsum";
		$min        = 5;
		$max        = 20;
		$f_value      = "012345678901234567890123456789";
		$f_value_size = strlen($f_value);

		$a = new \fr\eewee\eewee_sellsy\controllers\SellsyCustomFieldsController();
		$b = $a->checkSimpleText([
			"api" => [
				"label"     => $name,
				"default"   => $default,
				"min"       => $min,
				"max"       => $max,
			],
			"form" => [
				"value" => $f_value
			]
		]);
		$this->assertGreaterThanOrEqual($max, $f_value_size);
		$this->assertSame(["error", "Your value for " . $name . " is too big."], $b);
	}
}