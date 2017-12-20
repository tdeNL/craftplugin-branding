<?php

namespace tde\branding;

use craft\web\AssetBundle;

/**
 * Class BrandingPluginAssets
 * @package tde\branding
 */
class BrandingPluginAssets extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = '@tde/branding/resources';

		parent::init();
	}
}