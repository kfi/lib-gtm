<?php

namespace Inkl\GoogleTagManager;


use Inkl\GoogleTagManager\GoogleTagManager\DataLayer;
use PHPUnit_Framework_TestCase;

class DataLayerTest extends PHPUnit_Framework_TestCase
{

	public function testRender()
	{
		$varName = 'testvar';
		$varValue = 'testvalue';

		$expectedResult = <<<EOF
<script>
	var dataLayer = [{"{$varName}":"{$varValue}"}];
</script>
EOF;

		$result = DataLayer::getInstance()->addVariable($varName, $varValue)->render();

		$this->assertSame($expectedResult, $result);
	}

}