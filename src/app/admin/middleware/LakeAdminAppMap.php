<?php

namespace app\admin\middleware;

use Closure;
use think\App;

/**
 * lake-admin 中间件
 *
 * @create 2020-4-7
 * @author deatil
 */
class LakeAdminAppMap
{
    
    /** @var App */
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app  = $app;
    }
    
    /**
     * 中间件
     * @access public
     * @param Request $request
     * @param Closure $next
     * @return Response
     *
     * @create 2020-4-7
     * @author deatil
     */
    public function handle($request, Closure $next)
    {
        $appMaps = app()->config->get('app.app_map');
        $appMaps = array_merge($appMaps, [
            'admin' => function($app) {
                $app->http->path(dirname(__DIR__));
            },
            'api' => 'api',
        ]);
        $this->app->config->set([
            'app_map' => $appMaps,
        ], 'app');
        
        return $next($request);
    }
    
}
