<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\Dashboard\Base\SingleSourceBaseMetaData;
use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Models\Settings;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\Dashboard\Base\ITestableCRUD;
use Larapress\Dashboard\CRUD\DashboardCRUDProvider;
use Larapress\Dashboard\IDashboardUser;
use Larapress\Dashboard\Metrics\IMetricsReportMetaData;
use Larapress\Dashboard\Rendering\Menu\IMenuItemMetaData;

class DashboardMetaData extends SingleSourceBaseMetaData implements
    IMenuItemMetaData,
    ITestableCRUD,
    IMetricsReportMetaData
{
    public function title()
    {
        return trans('sidebar.title.dashboard');
    }
    public function icon()
    {
        return 'dashboard';
    }
    public function url()
    {
        return route('dashboard.any');
    }
    public function key()
    {
        return 'dashboard';
    }

    /**
     * @return array
     */
    public function viewPermissions()
    {
        return [];
    }

    public function viewRoles()
    {
        return [];
    }

    /**
     * @return ICRUDProvider
     */
    function getCRUDProvider()
    {
        return new DashboardCRUDProvider();
    }

    function reportsUrl()
    {
        return route('home');
    }

    function getReportPages()
    {
        return [];
    }

    function getCustomParams()
    {
        return [];
    }

    public function plural()
    {
        return 'dashboard';
    }
    public function singular()
    {
        return 'dashboard';
    }

    function getReportMetrics()
    {
        /** @var IDashboardUser $user */
        $user = Auth::user();
        /** @var Settings[] $dashboard_metrics */
        $dashboard_metrics = Settings::query()
                                     ->where('user_id', $user->getUID())
                                     ->where('key', 'LIKE', 'metrics.%.on_dashboard')
                                     ->where('val', '!=', 'false')
                                     ->get();

        $metrics = [];
        foreach ($dashboard_metrics as $metric) {
            $desc = json_decode($metric->val, true);
            /** @var IMetricsReportMetaData $meta */
            $meta = call_user_func([$desc['metadata'], 'instance']);
            if (!($meta instanceof DashboardMetaData)) {
                $meta_metrics = $meta->getReportMetrics();
                foreach ($meta_metrics as $meta_metric) {
                    if ($meta_metric['name'] === $desc['name']) {
                        $metrics[] = $meta_metric;
                        break;
                    }
                }
            }
        }

        foreach ($metrics as &$metric) {
            $metric['id'] = 'dashboard_'.$metric['id'];
            $metric['name'] = 'dashboard_'.$metric['name'];
        }

        return $metrics;
    }

    function reportMetricsUrl()
    {
        return route('dashboard.any');
    }
}
