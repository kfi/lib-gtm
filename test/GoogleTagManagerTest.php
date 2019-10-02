<?php

namespace Inkl\GoogleTagManager;

use Inkl\GoogleTagManager\Schema\Id;

class GoogleTagManagerTest extends \PHPUnit_Framework_TestCase
{
	/** @var GoogleTagManager */
	private $googleTagManager;

	protected function setUp()
	{
		parent::setUp();

		$this->googleTagManager = GoogleTagManager::getInstance();
	}

	protected function tearDown()
	{
		parent::tearDown();

		$this->googleTagManager->clearDataLayerVariables();
	}

	public function testRenderTag()
	{
		$id = 123;

		$expectedResult = <<<EOF
<script>
var dataLayer = window.dataLayer = window.dataLayer || [];
false && dataLayer.push([]);
</script>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$id}');</script>
EOF;

		$result = $this->googleTagManager
			->renderTag(new Id($id));

		$this->assertSame($expectedResult, $result);
	}

	public function testRenderTagWithAllFeatures()
	{
		$id = 123;
		$customScript = "<script>console.log('custom_script')</script>";
		$dataLayerVariableName = 'testName';
		$dataLayerVariableValue = 'testValue';
		$dataLayerVariableEventName = 'testEvent';
		$dataLayerVariableEventValue = 'testEventValue';

		$expectedResult = <<<EOF
<script>
var dataLayer = window.dataLayer = window.dataLayer || [];
true && dataLayer.push({
    "{$dataLayerVariableName}": "{$dataLayerVariableValue}",
    "{$dataLayerVariableEventName}": "{$dataLayerVariableEventValue}"
});
</script>
{$customScript}
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$id}');</script>
EOF;

		$result = $this->googleTagManager
			->addDataLayerVariable($dataLayerVariableName, $dataLayerVariableValue)
			->addDataLayerVariable($dataLayerVariableEventName, $dataLayerVariableEventValue)
			->addCustomScript($customScript)
			->renderTag(new Id($id));

		$this->assertSame($expectedResult, $result);
	}

}
