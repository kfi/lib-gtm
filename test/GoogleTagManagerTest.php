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
var dataLayer = [[]];
</script>

<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$id}');
</script>
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

		$expectedResult = <<<EOF
<script>
var dataLayer = [{
    "{$dataLayerVariableName}": "{$dataLayerVariableValue}"
}];
</script>
{$customScript}
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$id}');
</script>
EOF;

		$result = $this->googleTagManager
			->addDataLayerVariable($dataLayerVariableName, $dataLayerVariableValue)
			->addCustomScript('test', $customScript)
			->renderTag(new Id($id));

		$this->assertSame($expectedResult, $result);
	}

	public function testRenderNoScriptTag()
	{
		$id = 123;
		$dataLayerVariableName = 'testName';
		$dataLayerVariableValue = 'testValue';

		$expectedResult = <<<EOF
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$id}&amp;{$dataLayerVariableName}={$dataLayerVariableValue}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
EOF;

		$result = $this->googleTagManager
			->addDataLayerVariable($dataLayerVariableName, $dataLayerVariableValue)
			->renderNoScriptTag(new Id($id));

		$this->assertSame($expectedResult, $result);
	}

}
