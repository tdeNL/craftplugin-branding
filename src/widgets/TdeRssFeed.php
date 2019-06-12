<?php

namespace tde\branding\widgets;

use craft\base\Widget;
use craft\helpers\Json;
use craft\web\assets\feed\FeedAsset;

/**
 * @package modules\sitemodule\widgets
 */
class TdeRssFeed extends Widget
{
    /**
     * @return string
     */
    public static function displayName(): string
    {
        return 'TDE Nieuws';
    }

    /**
     * @return bool
     */
    protected static function allowMultipleInstances(): bool
    {
        return false;
    }

    /**
     * @return false|string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    public function getBodyHtml()
    {
        $view = \Craft::$app->getView();
        $view->registerAssetBundle(FeedAsset::class);
        $view->registerJs(
            "new Craft.FeedWidget({$this->id}, " .
            Json::encode('https://www.tde.nl/feed.rss') . ', ' .
            Json::encode(5) . ');'
        );

        return \Craft::$app->getView()->renderTemplate('_components/widgets/Feed/body', [
            'limit' => 5,
        ]);
    }
}
