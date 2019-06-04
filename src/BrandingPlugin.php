<?php

namespace tde\branding;

use Composer\Util\Filesystem;
use Craft;
use craft\base\Plugin;
use craft\helpers\FileHelper;

/**
 * Class BrandingPlugin
 * @package tde\branding
 */
class BrandingPlugin extends Plugin
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if (Craft::$app->request->isCpRequest && Craft::$app->user->isGuest) {
			Craft::$app->view->registerAssetBundle(BrandingPluginAssets::class);
			Craft::$app->view->registerCss('
				body.login {
					background-image: url(' . Craft::$app->assetManager->getPublishedUrl('@tde/branding/resources/cp-bg.jpg', true) . ');
					-webkit-background-size: cover;
					-moz-background-size: cover;
					-o-background-size: cover;
					background-size: cover;
					background-repeat: no-repeat;
					background-position: top left;
					color: #fff;
				}
				#poweredby {
					display: none;
				}
			');
		}

		parent::init();
	}
}
