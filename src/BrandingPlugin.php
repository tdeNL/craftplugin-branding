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
	const SITE_LOGO_NAME = 'site-logo.png';

	/**
	 * @return bool
	 * @throws \craft\errors\ImageException
	 * @throws \yii\base\ErrorException
	 */
	public function beforeInstall() : bool
	{
		return $this->saveSiteLogo();
	}

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
					background-position: right bottom;
					color: #fff;
				}
				#poweredby {
					display: none;
				}
			');
		}

		parent::init();
	}

	/**
	 * Save the site logo
	 *
	 * @return bool
	 * @throws \yii\base\ErrorException
	 * @throws \craft\errors\ImageException
	 */
	protected function saveSiteLogo()
	{
		$logoPath = __DIR__ . '/resources/' . self::SITE_LOGO_NAME;

		// check if site-logo exists
		if (!file_exists($logoPath)) {
			Craft::$app->session->setNotice(
				'Er is nog geen site logo in de plugin toegevoegd. Voeg deze toe aan de TDE Branding plugin-map: ' .
				'/resources/images/' . self::SITE_LOGO_NAME
			);

			return false;
		}

		$targetPath = Craft::$app->path->getRebrandPath() . '/logo/';
		if (!is_dir($targetPath)) {
			mkdir($targetPath);
		}

		$fs = new Filesystem();
		$fs->ensureDirectoryExists($targetPath);
		FileHelper::clearDirectory($targetPath);

		Craft::$app->images
			->loadImage($logoPath)
			->scaleToFit(300, 300, false)
			->saveAs($targetPath . self::SITE_LOGO_NAME);

		return true;
	}
}