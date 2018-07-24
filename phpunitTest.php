<?php

require "phpunit.php";

use PHPUnit\Framework\TestCase;

final class HelloTest extends TestCase
{
	public function testHelloWorld(): void
    	{
		$hello = new Hello();
		$expected = 'Hello World !';
        	$this->assertEquals($expected, $hello->hello_world());
    	}
}