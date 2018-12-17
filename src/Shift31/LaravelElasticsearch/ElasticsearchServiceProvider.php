<?php
namespace Shift31\LaravelElasticsearch;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    const VERSION = '5.0.0';

    /**
     * @inheritdoc
     */
    public function boot()
    {
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/elasticsearch.php'), 'elasticsearch');
        $this->app->singleton('elasticsearch', function ($app) {
            return ClientBuilder::fromConfig($app->config->get('elasticsearch'));
        });
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['elasticsearch'];
    }
}
