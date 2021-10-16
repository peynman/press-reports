<?php

namespace Larapress\Reports\Services\Reports;

use Larapress\CRUD\Services\CRUD\CRUDController;
use Illuminate\Http\Response;

class ReportsVerbController extends CRUDController {
    /**
     * Undocumented function
     *
     * @param ReportQueryRequest $request
     *
     * @return Response
     */
    public function reports (ReportQueryRequest $request) {
        return $this->crudService->handle(ReportsVerb::REPORTS, $request);
    }
}

