<?php
namespace Girolando\Componentes\Cruzamento\Providers;

use Girolando\BaseComponent\Contracts\ComponentServiceContract;
use Girolando\BaseComponent\Providers\BaseComponentProvider;
use Girolando\Componentes\Cruzamento\Entities\Views\DatabaseEntity;
use Girolando\Componentes\Cruzamento\Facades\ComponenteCruzamento;
use Girolando\Componentes\Cruzamento\Services\ComponentService;
use Girolando\Componentes\Cruzamento\Services\Server\DatabaseEntityService;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class ComponentProvider extends BaseComponentProvider{

    public static $routeModal = '/vendor-girolando/componentes/cruzamento';
    public static $routeServer = '/vendor-girolando/server/componentes/cruzamento';
    public static $routeFindByClient = '/vendor-girolando/componentes/cruzamento-findby';
    public static $routeFindByServer = '/vendor-girolando/server/componentes/cruzamento-findby';
    public static $componentNamespace = 'ComponenteCruzamento';
    public static $entity = DatabaseEntity::class;
    public static $facade = ComponenteCruzamento::class;
    public static $databaseService = DatabaseEntityService::class;
    public static $componentService = ComponentService::class;
    public static $componente = 'Cruzamento';


    public function boot(Router $router)
    {
        Lang::addNamespace(self::$componentNamespace, __DIR__.'/../../resources/lang');
        View::addNamespace(self::$componentNamespace, __DIR__.'/../../resources/views');
        parent::boot($router);
    }


    public function map(Router $router)
    {
        $router->group(['namespace' => 'Girolando\Componentes\\'.self::$componente.'\Http\Controllers'], function() use($router){
            $router->resource(self::$routeModal, 'ClientController', ['only' => ['index']]);
            $router->resource(self::$routeServer, 'ServerController', ['only' => ['index']]);
            $router->get(self::$routeFindByClient, 'ClientController@findBy');
            $router->get(self::$routeFindByServer, 'ServerController@findBy');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Girolando.Componente.'.self::$componentNamespace, self::$componentService);
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias(self::$componentNamespace, self::$facade);

        $this->app->when(ServerController::class)->needs(ComponentServiceContract::class)->give(self::$databaseService);

    }
}