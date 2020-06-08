<?php

namespace Larapress\Reports\InfluxDB;

use Illuminate\Support\Facades\Redis;
use Larapress\Reports\Services\IReportsService;
use InfluxDB2\Client;
use InfluxDB2\Point;
use InfluxDB2\WriteType as WriteType;

class InfluxDBReportService implements IReportsService {
     /**
     * Undocumented function
     *
     * @param String $name
     * @param integer $value
     * @param array $tags
     * @param array $fields
     * @param integer $timestamp
     * @return bool
     */
    public function pushMeasurement(String $name, int $value, array $tags, array $fields, int $timestamp) {
        $redis = Redis::connection(config('larapress.reports.batch.connection'));
        $redis->rpush(config('larapress.reports.batch.key'), json_encode([
            'name' => $name,
            'timestamp' => $timestamp,
            'tags' => $tags,
            'fields' => $fields,
            'value' => $value
        ]));
    }

    /**
     * Undocumented function
     *
     * @param int $max
     * @return void
     */
    public function batchReportMeasurements(int $max) {
        $redis = Redis::connection(config('larapress.reports.batch.connection'));
        $records = $redis->lrange(config('larapress.reports.batch.key'), 0, -1);

        $points = [];

        $dsn =  sprintf(
            '%s://%s:%s',
            config('larapress.reports.influxdb.schema'),
            config('larapress.reports.influxdb.host'),
            config('larapress.reports.influxdb.port'),
        );
        $client = new Client([
            "url" => $dsn,
            "bucket" => config('larapress.reports.influxdb.database'),
            "precision" => \InfluxDB2\Model\WritePrecision::S,
            "org" => config('larapress.reports.influxdb.org'),
            "token" => config('larapress.reports.influxdb.token')
        ]);
        $wApi = $client->createWriteApi(
            [
                "writeType" => WriteType::BATCHING,
                'batchSize' => config('larapress.reports.influxdb.max_batch_size'),
                "flushInterval" => config('larapress.reports.influxdb.batch_interval')
            ]
        );

        if ($records) {
            foreach ($records as $record) {
                $rec = json_decode($record, true);
                $mes = Point::measurement($rec['name']);
                if ($rec['tags']) {
                    foreach ($rec['tags'] as $tag => $val) {
                        $mes->addTag($tag, $val);
                    }
                }
                if ($rec['fields']) {
                    foreach ($rec['fields'] as $tag => $val) {
                        $mes->addField($tag, $val);
                    }
                }
                if ($rec['value']) {
                    $mes->addField("value", $rec['value']);
                }
                $mes->time($rec['timestamp']);
                $wApi->write($mes);
            }
        }

        $wApi->close();
    }
}
