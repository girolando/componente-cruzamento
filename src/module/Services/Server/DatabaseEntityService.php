<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 30/03/2016
 * Time: 16:36
 */
namespace Girolando\Componentes\Cruzamento\Services\Server;


use Girolando\BaseComponent\Contracts\ComponentServiceContract;
use Girolando\BaseComponent\Engines\DatasetEngine;
use Girolando\Componentes\Cruzamento\Entities\Views\DatabaseEntity;
use Girolando\Componentes\Cruzamento\Repositories\Views\DatabaseEntityRepository;
use Andersonef\Repositories\Abstracts\ServiceAbstract;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;

class DatabaseEntityService extends ServiceAbstract implements ComponentServiceContract
{
    protected $datasetEngine;
    /**
     * This constructor will receive by dependency injection a instance of DatabaseEntityRepository and DatabaseManager.
     *
     * @param DatabaseEntityRepository $repository
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseEntityRepository $repository, DatabaseManager $db)
    {
        parent::__construct($repository, $db);
        $this->datasetEngine = new DatasetEngine($this);

    }

    public function getDataset($dataTableQueryName)
    {
        $dataset = $this
            ->datasetEngine
            ->usingDataTableQuery($dataTableQueryName)
            ->createDataset((new DatabaseEntity())->getFillable());
        //como não tem nenhuma validação, nenhum tipo de filtro especial pra fazer nesse querybuilder... então já retorno o dataset.(que é um querybuilder... só add nele outros wheres q eu possa precisar)
        $dataset->whereIn('tipoComunicacao', [1,2,3,4,5]);

        /*
         * Adicionado para não permitir mostrar cruzamentos duplicados devido a alteração na view comp.Cruzamento 
         * que agora mostra cruzamento duplicado caso tenha receptoras liberadas parcialmente, o que faz ter mais de um codigoPessoaAutorizada
        */
      	$dataset->select('id','codigoCruzamento','numeroComunicacao', 'tipoComunicacao', 'registroReprodutor','nomeReprodutor', 'registroMatriz','nomeMatriz', 'dataCruzamento')->distinct();
	return $dataset;
    }

    public function getJsonDataset($datasetName)
    {
        $dataset = $this->getDataset($datasetName);

        return Datatables::of($dataset)
            ->make(true);
    }


}
