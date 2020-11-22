<?php

namespace Larapress\Reports\Services\LaravelEcho;

use Illuminate\Support\Facades\Log;
use Larapress\Reports\Services\IReportsService;

class LaravelEchoMetrics implements ILaravelEchoMetrics {
    /**
     * Undocumented function
     *
     * @return void
     */
    public function pushEchoMeasurements() {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);

        $subscription_count = 0;
        $users_count = 0;
        $users_list = [];

        [$httpCode, $data] = $this->callEchoApiEndpoint("channels/presence-website");
        if ($httpCode === 200) {
            if (isset($data['subscription_count'])) {
                $subscription_count = intval($data['subscription_count']);
            }
            if (isset($data['user_count'])) {
                $users_count = intval($data['user_count']);
                if ($users_count > 0) {
                    [$httpCode, $data] = $this->callEchoApiEndpoint("channels/presence-website/users");
                    if ($httpCode === 200 && isset($data['users'])) {
                        foreach ($data['users'] as $user) {
                            $users_list[] = $user['id'];
                        }
                    }
                }
            }
        }

        /** @var IReportsService */
        $service = app(IReportsService::class);
        $service->pushMeasurement("website.online_tabs", $subscription_count, [], [], time());
        $service->pushMeasurement("website.online_users", $users_count, [], [], time());
    }

    /**
     * Undocumented function
     *
     * @param string $endPiont
     * @return array
     */
    protected function callEchoApiEndpoint($endPiont) {
        $protocol = config('broadcasting.connections.pusher.options.scheme');
        $host = config('broadcasting.connections.pusher.options.host');
        $port = config('broadcasting.connections.pusher.options.port');
        $appId = config('broadcasting.connections.pusher.app_id');
        $appKey = config('broadcasting.connections.pusher.key');
        $url = "$protocol://$host:$port/apps/$appId/$endPiont?auth_key=$appKey";
        // we are the parent
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        try {
            $data = json_decode($data, true);
        } catch (\Exception $e) {
            Log::critical('echo metrics error', [$e->getMessage()]);
        }

        return [$httpCode, $data];
    }
}
