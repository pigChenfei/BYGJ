<?php
namespace App\Providers;

use App\Entities\CacheConstantPrefixDefine;
use App\Helpers\Caches\AgentInfoCacheHelper;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Models\CarrierAgentUser;
use App\Models\CarrierBackUpDomain;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        \Form::macro('required_pin',
            function () {
                return '<label style="color: red">&nbsp;*</label>';
            });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        if (\App::environment() == 'cli') {
            $this->mapCarrierAdminRoutes();
            $this->mapAdminRoutes();
            $this->mapAgentAdminRoutes();
            $this->mapWebRoutes();
            return;
        }
        $url = parse_url(\URL::current());
        if (! $url) {
            $this->mapWebRoutes();
            return;
        }
        if (\App::environment() != 'production') {
            if (! isset($url['path'])) {
                $this->mapWebRoutes();
                return;
            }
            $paths = explode('/', $url['path']);
            $system_lists = \Config::get('app.system_host_list', []);
            foreach ($paths as $path) {
                foreach ($system_lists as $system_key => $system_urls) {
                    if ($path == $system_key) {
                        $this->redirectToRoute($system_key);
                        return;
                    }
                }
            }
            $this->mapWebRoutes();
        } else {
            $host = $url['host'];
            $system_lists = \Config::get('app.system_host_list', []);
            foreach ($system_lists as $system_key => $system_urls) {
                $system_urls_array = explode(',', $system_urls);
                if (in_array($host, $system_urls_array)) {
                    $this->redirectToRoute($system_key);
                    return;
                }
            }
            $this->mapWebRoutes();
        }
    }

    private function redirectToRoute($path)
    {
        switch ($path) {
            case 'carrier':
                $this->mapCarrierAdminRoutes();
                return;
            case 'admin':
                $this->mapAdminRoutes();
                return;
            case 'agent':
                $this->mapAgentAdminRoutes();
                return;
            default:
                $this->mapWebRoutes();
        }
    }

    protected function mapAdminRoutes()
    {
        Route::group(
            [
                'middleware' => 'web',
                'namespace' => $this->namespace,
                'prefix' => \App::environment() != 'production' ? 'admin' : ''
            ], function ($router) {
                require base_path('routes/admin.php');
            });
        \App::instance('currentRole', 'admin');
    }

    protected function mapCarrierAdminRoutes()
    {
        $this->carrierWebsiteAttemptToAccess();
        Route::group(
            [
                'middleware' => 'web',
                'namespace' => $this->namespace,
                'prefix' => \App::environment() != 'production' ? 'carrier' : ''
            ], function ($router) {
                require base_path('routes/carrier.php');
            });
        \App::instance('currentRole', 'carrierAdmin');
    }

    protected function mapAgentAdminRoutes()
    {
        // $this->agentWebSiteAttemptToAccess();
        $this->carrierWebsiteAttemptToAccess();
        Route::group(
            [
                'middleware' => 'web',
                'namespace' => $this->namespace,
                'prefix' => \App::environment() != 'production' ? 'agent' : ''
            ], function ($router) {
                require base_path('routes/agent.php');
            });
        \App::instance('currentRole', 'agent');
    }

    protected function mapMemberAdminRoutes()
    {
        $this->carrierWebsiteAttemptToAccess();
        Route::group(
            [
                'middleware' => 'web',
                'namespace' => $this->namespace,
                'prefix' => \App::environment() != 'production' ? 'member' : ''
            ], function ($router) {
                require base_path('routes/member.php');
            });
        \App::instance('currentRole', 'memberAdmin');
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $this->carrierWebsiteAttemptToAccess();
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace
        ], function ($router) {
            require base_path('routes/web.php');
        });
        \App::instance('currentRole', 'web');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(
            [
                'middleware' => 'api',
                'namespace' => $this->namespace,
                'prefix' => 'api'
            ], function ($router) {
                require base_path('routes/api.php');
            });
    }

    private function carrierWebsiteAttemptToAccess()
    {
        $host = \Request::capture()->getHost();
        if ($host) {
            $info = CarrierInfoCacheHelper::getCachedCarrierInfoByHost($host);
            $carrier = $info['carrier'];
            $agent = $info['agent'];
            
            if ($carrier && $carrier->is_forbidden == false) {
                try {
                    // 检测运营商是否被封
                    $carrier->checkActive();
                    // 不使用运营商缓存
                    $carrier = Carrier::findOrFail($carrier->id);
                    \App::instance('currentWebCarrier', $carrier);
                } catch (\Exception $e) {
                    throw new NotFoundHttpException();
                }
                if ($agent) {
                    if ($agent->status == 1) {
                        try {
                            \App::instance('currentWebAgent', $agent);
                        } catch (\Exception $e) {
                            throw new NotFoundHttpException();
                        }
                    } else {
                        $carrierUrls[] = $carrier->site_url;
                        foreach ($carrier->carrierBackUpDomain as $backUpDomain) {
                            $carrierUrls[] = $backUpDomain->domain;
                        }
                        if (! in_array($agent->site_url, $carrierUrls)) {
                            abort(403);
                        } else {
                            $agent = new Collection();
                            \App::instance('currentWebAgent', $agent);
                        }
                    }
                } else {
                    $agent = new Collection();
                    \App::instance('currentWebAgent', $agent);
                }
            } else {
                abort(403);
            }
        }
    }

    private function agentWebSiteAttemptToAccess()
    {
        $host = \Request::capture()->getHost();
        if ($host) {
            // $agent = AgentInfoCacheHelper::getAgentInfoByDomain($host);
            $info = CarrierInfoCacheHelper::getCachedCarrierInfoByHost($host);
            $agent = $info['agent'];
            $carrier = $info['carrier'];
            if ($agent) {
                if ($agent->isActive() == true) {
                    try {
                        \App::instance('currentWebAgent', $agent);
                    } catch (\Exception $e) {
                        throw new NotFoundHttpException();
                    }
                } else {
                    abort(403);
                }
            } else if ($carrier) {
                if ($carrier->is_forbidden == false) {
                    try {
                        // 不使用运营商缓存
                        $carrier = Carrier::findOrFail($carrier->id);
                        \App::instance('currentWebAgent', $carrier);
                    } catch (\Exception $e) {
                        throw new NotFoundHttpException();
                    }
                } else {
                    abort(403);
                }
            } else {
                abort(403);
            }
        }
    }
}
