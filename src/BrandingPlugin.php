<?php

namespace tde\branding;

use Craft;
use craft\base\Plugin;
use yii\base\Event;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;
use tde\branding\widgets\TdeRssFeed;

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
        if (!Craft::$app->request->isCpRequest) {
            return;
        }

		if (Craft::$app->user->isGuest) {
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

        if (!Craft::$app->user->isGuest) {
            // Define branded widgets
            $widgets = [TdeRssFeed::class];

            Event::on(
                Dashboard::class,
                Dashboard::EVENT_REGISTER_WIDGET_TYPES,
                function (RegisterComponentTypesEvent $event) use ($widgets) {
                    foreach ($widgets as $widget) {
                        $event->types[] = $widget;

                        if (\Craft::$app->getRequest()->getIsCpRequest()
                            && !\Craft::$app->getDashboard()->doesUserHaveWidget($widget)
                        ) {
                            \Craft::$app->getDashboard()->saveWidget(\Craft::$app->getDashboard()->createWidget($widget));
                        }
                    }
                }
            );
        }

		parent::init();
	}
}
