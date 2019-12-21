<?php

namespace Larapress\Dashboard\Middleware;

use Illuminate\Contracts\Container\BindingResolutionException;
use Larapress\CRUDRender\Base\BaseJSONRenderProvider;
use Larapress\CRUDRender\Base\ICRUDRenderProvider;
use Larapress\Dashboard\Base\JSONCRUDViewProvider;

class JSONCRUDRenderRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            app()->extend(ICRUDRenderProvider::class, function ($app, $params) {
                if (isset($params['metadata'])) {
                    $metadata = call_user_func([$params['metadata'], 'instance']);
                    if (!is_null($metadata)) {
                        $json = new JSONCRUDViewProvider($metadata);

                        $jsonRenderer = new BaseJSONRenderProvider();
                        $jsonRenderer->useViewDataProvider($json);

                        return $jsonRenderer;
                    }
                }

                throw new BindingResolutionException();
            });
        }

        return $next($request);
    }
}
