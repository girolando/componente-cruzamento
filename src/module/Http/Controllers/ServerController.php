<?php
namespace Girolando\Componentes\Pessoa\Http\Controllers;

use Andersonef\Repositories\Abstracts\ServiceAbstract;
use Girolando\BaseComponent\Contracts\ComponentServiceContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ServerController extends Controller
{
    private $service;

    /**
     * constructor.
     * @param $service
     */
    public function __construct(ComponentServiceContract $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->service->getJsonDataset('_dataTableQuery'.$request->get('name'));
    }

}
