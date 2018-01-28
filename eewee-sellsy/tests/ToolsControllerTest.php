<?php
//if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase'))
//    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
use PHPUnit\Framework\TestCase;

// Pour passer le "defined" :
define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );

class ToolsControllerTest extends TestCase
{
	/**
	 * @covers \fr\eewee\eewee_sellsy\controllers\ToolsController::isJson
	 */
	public function testIsJsonTrue()
	{
		$a = '{ "name":"John", "age":30, "car":null }';
		$this->assertTrue(\fr\eewee\eewee_sellsy\controllers\ToolsController::isJson($a));
	}

	/**
	 * @covers \fr\eewee\eewee_sellsy\controllers\ToolsController::isJson
	 */
	public function testIsJsonFalse()
	{
		$a = "lorem ipsum";
		$this->assertFalse(\fr\eewee\eewee_sellsy\controllers\ToolsController::isJson($a));
	}
}