<?php

namespace Larapress\Reports\InfluxDB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Larapress\Reports\Services\Reports\IReportsService;
use InfluxDB2\Client;
use InfluxDB2\Point;
use InfluxDB2\WriteType as WriteType;
use InfluxDB2\FluxRecord;
use InfluxDB2\DefaultApi;

class InfluxDBReportService implements IReportsService
{
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
    public function pushMeasurement(String $name, int $value, array $tags, array $fields, int $timestamp)
    {
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
    public function batchReportMeasurements(int $max)
    {
        $redis = Redis::connection(config('larapress.reports.batch.connection'));

        $records = $redis->lrange(config('larapress.reports.batch.key'), 0, -1);

        if (!is_null($records) && is_array($records)) {
            // remove grabbed records from redis
            $redis->ltrim(config('larapress.reports.batch.key'), count($records), -1);

            $client = $this->getClient();
            $wApi = $client->createWriteApi(
                [
                    "writeType" => WriteType::BATCHING,
                    'batchSize' => config('larapress.reports.influxdb.max_batch_size'),
                    "flushInterval" => config('larapress.reports.influxdb.batch_interval')
                ]
            );

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

            $wApi->close();
        }
    }


    /**
     * Undocumented function
     *
     * @param String $name
     * @param array $tags
     * @param Carbon $from
     * @param Carbon $to
     * @param [type] $function
     * @return array
     */
    public function queryMeasurement(String $name, array $filters, array $groups, array $columns, Carbon $from, $to, $function)
    {
        $client = $this->getClient();
        $query = $client->createQueryApi();

        $queryString = 'from(bucket:"' . config('larapress.reports.influxdb.database') . '") |> range(start: ' . $from->format('Y-m-d\TH:i:s.u\Z');
        if (!is_null($to)) {
            $queryString .= ', stop: ' . $to->format('Y-m-d\TH:i:s.u\Z') . ') ';
        } else {
            $queryString .= ') ';
        }
        $queryString .= '|> filter(fn: (r) => r._measurement == "' . $name . '") ';
        foreach ($filters as $filter => $val) {
            if (is_array($val)) {
                if (count($val) > 0) {
                    $vals = [];
                    foreach ($val as $vv) {
                        $vals[] = 'r.' . $filter . ' == "' . $vv . '"';
                    }
                    $queryString .= '|> filter(fn: (r) => ' . implode(' or ', $vals) . ') ';
                }
            } else {
                if ($val === '$exists') {
                    $queryString .= '|> filter(fn: (r) => exists r.' . $filter . ') ';
                } else {
                    $queryString .= '|> filter(fn: (r) => r.' . $filter . ' == "' . $val . '") ';
                }
            }
        }
        if (count($groups) > 0) {
            $queryString .= '|> group(columns: ' . json_encode($groups) . ')';
        }
        $queryString .= '|> ' . $function;

        $resultsets = $query->query($queryString);

        $output = [];
        foreach ($resultsets as $resultset) {
            /** @var FluxRecord $record */
            foreach ($resultset->records as $record) {
                $output[] = array_filter($record->values, function ($key) use ($columns) {
                    return in_array($key, $columns);
                }, ARRAY_FILTER_USE_KEY);
            }
        }

        return $output;
    }

    /** @var Client */
    protected $client = null;
    protected function getClient()
    {
        if (is_null($this->client)) {
            $dsn =  sprintf(
                '%s://%s:%s',
                config('larapress.reports.influxdb.schema'),
                config('larapress.reports.influxdb.host'),
                config('larapress.reports.influxdb.port'),
            );
            $this->client = new Client([
                "url" => $dsn,
                "bucket" => config('larapress.reports.influxdb.database'),
                "precision" => \InfluxDB2\Model\WritePrecision::S,
                "org" => config('larapress.reports.influxdb.org'),
                "token" => config('larapress.reports.influxdb.token'),
            ]);
        }

        return $this->client;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function batchReportPurge()
    {
        $redis = Redis::connection(config('larapress.reports.batch.connection'));
        $redis->del(config('larapress.reports.batch.key'));
    }


    /**
     * Undocumented function
     *
     * @param String $name
     * @param array $filters
     * @return bool
     */

    public function removeMeasurement(String $name, array $filters)
    {
        // $client = $this->getClient();
        // /** @var DefaultApi */
        // $api = new DefaultApi($client->options);

        // $result = $api->post(json_encode(array_merge([
        //     "_measurement" => $name,
        //     "start" => '1970-01-01T00:00:00.00Z',
        //     "stop" => Carbon::now()->toIso8601String(),
        // ], $filters)), "/api/v2/delete", [
        //     "bucket" => config('larapress.reports.influxdb.database'),
        //     "org" => config('larapress.reports.influxdb.org'),
        // ]);

        //return $result->getStatusCode() == 200;

        return false;
    }
}
