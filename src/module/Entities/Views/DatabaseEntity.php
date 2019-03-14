<?php
namespace Girolando\Componentes\Cruzamento\Entities\Views;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VPessoa
 * @package Girolando\Componentes\Entities\Views
 */
class DatabaseEntity extends Model
{
    /**
     * @var string
     */
    protected $table = "comp.Cruzamento";

    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var bool
     */
    public static $snakeAttributes = false;


    protected $fillable = [
        'id',
        'codigoCruzamento',
        'codigoComunicacao',
        'numeroComunicacao',
        'codigoMatriz',
        'nomeMatriz',
        'registroMatriz',
        'sangueMatriz',
        'codigoReprodutor',
        'nomeReprodutor',
        'registroReprodutor',
        'sangueReprodutor',
        'codigoSecreto',
        'dataCruzamento',
        'statusCruzamento',
        'codigoCruzamentoAutorizado',
        'statusCruzamentoAutorizado',
        'tipoComunicacao',
        'codigoPessoaComunicacao',
        'codigoPessoaAutorizada',
        'qtdFilhos',
        'qtdViavelEmbriao',
        'qtdImplantada',
    ];
}
