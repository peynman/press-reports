<?php

namespace Larapress\Reports\Services\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Larapress\CRUD\Exceptions\ValidationException;
use Larapress\CRUD\ICRUDUser;
use Larapress\CRUD\Services\CRUD\ICRUDService;
use Larapress\CRUD\Services\CRUD\ICRUDVerb;

class ReportsVerb implements ICRUDVerb
{

    const REPORTS = 'reports';

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getVerbName(): string
    {
        return self::REPORTS;
    }

    /**
     * Undocumented function
     *
     * @param ICRUDService $service
     * @param Request $request
     * @param ...$args
     *
     * @return mixed
     */
    public function handle(ICRUDService $service, Request $request, ...$args)
    {
        $crudProvider = $service->getCompositeProvider();

        /** @var ICRUDUser */
        $user = Auth::user();
        /** @var ICRUDReportSource[] */
        $reports = $crudProvider->getReportSources();

        $names = array_keys($reports);

        $validate = Validator::make($request->all('name'), [
            'name' => 'required|in:' . implode(',', $names)
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        /** @var string */
        $reportClass = $reports[$request->get('name')];
        app()->bind(ICRUDReportSource::class, $reportClass);
        /** @var ICRUDReportSource */
        $report = app(ICRUDReportSource::class);

        return $report->getReport($user, $request);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return array
     */
    public static function controllerVerb($name): array
    {
        return [
            'methods' => ['POST'],
            'url' => $name.'/reports',
            'uses' => '\\'.ReportsVerbController::class.'@reports',
        ];
    }
}
