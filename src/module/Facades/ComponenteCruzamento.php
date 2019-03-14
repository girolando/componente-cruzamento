<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 14/07/2016
 * Time: 15:26
 */

namespace Girolando\Componentes\Cruzamento\Facades;

use Girolando\Componentes\Cruzamento\Providers\ComponentProvider;
use Illuminate\Support\Facades\Facade;

class ComponenteCruzamento extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Girolando.Componente.'.ComponentProvider::$componentNamespace;
    }

}