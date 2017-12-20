<?php

namespace Craft;

/**
 * Class BrandingPlugin
 * @package Craft
 */
class BrandingPlugin extends BasePlugin
{
	const SITE_LOGO_NAME = 'site-logo.png';

	/**
	 * @return string
	 */
	public function getId()
	{
		return 'branding';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'TDE - Branding';
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return 'Brand the Craft Control Panel with TDE.';
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return '1.0.0';
	}

	/**
	 * @return string
	 */
	public function getDeveloper()
	{
		return 'TDE';
	}

	/**
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return 'http://www.tde.nl';
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if (craft()->request->isCpRequest() && !craft()->userSession->isLoggedIn()) {
			craft()->templates->includeCss('
				body {
					background-image: url('. UrlHelper::getResourceUrl($this->getId() . '/images/cp-bg.jpg') . ');
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
	}

	/**
	 * @inheritdoc
	 */
	public function onBeforeInstall()
	{
		if (!$this->saveSiteLogo()) {
			return false;
		}

		return true;
	}

	/**
	 * Save the site logo
	 *
	 * @return bool
	 */
	protected function saveSiteLogo()
	{
		$logoPath = craft()->path->getPluginsPath() . $this->getId() . '/resources/images/' . self::SITE_LOGO_NAME;

		// check if site-logo exists
		if (!IOHelper::fileExists($logoPath)) {
			craft()->userSession->setNotice(
				'Er is nog geen site logo in de plugin toegevoegd. Voeg deze toe in craft/plugins/' . $this->getId() .
				'/resources/images/' . self::SITE_LOGO_NAME
			);

			return false;
		}

		$targetPath = craft()->path->getRebrandPath() . 'logo/';

		IOHelper::ensureFolderExists($targetPath);
		IOHelper::clearFolder($targetPath);

		craft()->images
			->loadImage($logoPath)
			->scaleToFit(300, 300, false)
			->saveAs($targetPath . self::SITE_LOGO_NAME);

		return true;
	}
}