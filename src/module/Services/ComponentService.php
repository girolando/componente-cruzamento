<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 29/03/2016
 * Time: 14:34
 */
namespace Girolando\Componentes\Cruzamento\Services;


use Girolando\BaseComponent\Services\BaseComponentService;
use Girolando\Componentes\Cruzamento\Providers\ComponentProvider;

class ComponentService extends BaseComponentService
{

    public function _init($params = [])
    {
        return view(ComponentProvider::$componentNamespace.'::Services.Component._init', $params);
    }
}