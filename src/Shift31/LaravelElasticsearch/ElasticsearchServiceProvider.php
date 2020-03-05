<?php
namespace Shift31\LaravelElasticsearch;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

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
            if (empty($app->config->get('elasticsearch.logger'))) {
                $config = $app->config->get('elasticsearch');

                $logPath = Arr::get($config, 'elasticsearch.logPath', storage_path('logs/elastic-search.log'));
                $logLevel = Arr::get($config, 'elasticsearch.logLevel', Logger::WARNING);
                $logger = ClientBuilder::defaultLogger($logPath, $logLevel);
                unset($config['logLevel']);
                unset($config['logPath']);
                $config['logger'] = $logger;
                $app->config->set('elasticsearch', $config);
            }

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
