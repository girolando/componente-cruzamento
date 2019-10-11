<?php

namespace Girolando\Componentes\Cruzamento\Http\Controllers;

use Andersonef\ApiClientLayer\Services\ApiConnector;
use Girolando\Componentes\Cruzamento\Entities\Views\DatabaseEntity;
use Girolando\Componentes\Cruzamento\Providers\ComponentProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class ClientController extends Controller
{
    protected $apiConnector;

    /**
     * AnimalServiceController constructor.
     * @param $apiConnector
     */
    public function __construct(ApiConnector $apiConnector)
    {
        $this->apiConnector = $apiConnector;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if($request->has('_DataTableQuery')){
            $response = $this->apiConnector->get(ComponentProvider::$routeServer, $request->all());

            if($response->status == 'success'){
                return new JsonResponse($response->data, 200);
            }
            dd($response);
        }
        $all = $request->all();
        $filters = [];
        foreach($all as $attr => $val){
            if(substr($attr, 0, 7) != 'filter-') continue;
            $filters[substr($attr, 7)] = $val;
        }

        $request->merge(['_attrFilters' => $filters]);
        $request->merge(['tableName' => (new DatabaseEntity())->getTable()]);

        $sangues = $this->apiConnector->get('/vendor-girolando/server/componentes/animal/tiposangue');
        $sangues = collect($sangues->data)
            ->map(function($sangue) {
                if (is_null($sangue->ordemMapaTipoSangue)) {
                    $sangue->ordemMapaTipoSangue = 999;
                }
                return $sangue;
            })
            ->sortBy('ordemMapaTipoSangue');
            
        $request->merge(['sangues' => $sangues]);

        return view(ComponentProvider::$componentNamespace.'::ClientController.index', $request->all());
    }
}
