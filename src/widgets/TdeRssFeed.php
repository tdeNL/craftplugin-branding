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
     * @var string The feed URL
     */
    public $url = 'https://www.tde.nl/feed.rss';

    /**
     * @var string The feed title
     */
    public $title = 'TDE nieuws';

    /**
     * @var int The maximum number of feed items to display
     */
    public $limit = 5;

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
        // See if it's already cached
        $data = \Craft::$app->getCache()->get("feed:$this->url");

        if ($data) {
            $data['items'] = array_slice($data['items'] ?? [], 0, $this->limit);
        } else {
            // Fake it for now and fetch it later
            $data = [
                'direction' => 'ltr',
                'items' => [],
            ];

            for ($i = 0; $i < $this->limit; $i++) {
                $data['items'][] = [];
            }

            $view = \Craft::$app->getView();
            $view->registerAssetBundle(FeedAsset::class);
            $view->registerJs(
                "new Craft.FeedWidget({$this->id}, " .
                Json::encode($this->url) . ', ' .
                Json::encode($this->limit) . ');'
            );
        }

        return \Craft::$app->getView()->renderTemplate('_components/widgets/Feed/body', [
            'feed' => $data,
            'limit' => $this->limit,
        ]);
    }
}
