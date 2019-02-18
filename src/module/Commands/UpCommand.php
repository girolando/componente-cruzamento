<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 04/08/2016
 * Time: 10:28
 */

namespace Girolando\Componentes\Cruzamento\Commands;


use Illuminate\Console\Command;

class UpCommand extends Command
{
    protected $signature = 'girolando:componentecruzamento:up';
    protected $description = 'Instala a view necessária para o componente funcionar. Nesse caso a view é comp.vCruzamento';

    public function handle()
    {
        try {
            $this->info('Rodando migration da view');
            $this->migrate();
            $this->info('Migration Executada!');
        } catch (\Exception $e) {
            $this->error('Houve uma falha: '. $e->getMessage());
        }
    }


    private function migrate()
    {
        \DB::statement("
            create view [comp].[Cruzamento]
              as
            select
                cr.codigoCruzamento as id,
                cr.codigoCruzamento,
                cr.codigoComunicacao,
                com.numeroComunicacao,
                codigoMatriz,
                mat.nomeAnimal as nomeMatriz,
                mat.mascaraRegistro as registroMatriz,
                mat.siglaTipoSangue as sangueMatriz,
                codigoReprodutor,
                rep.nomeAnimal as nomeReprodutor,
                rep.mascaraRegistro as registroReprodutor,
                rep.siglaTipoSangue as sangueReprodutor,
                codigoSecreto,
                dataCruzamento,
                statusCruzamento,
                ca.codigoCruzamentoAutorizado,
                ca.statusCruzamentoAutorizado,
                com.tipoComunicacao
            from
            dbo.cruzamento cr
            join AnimalConsulta mat on mat.codigoAnimal = cr.codigoMatriz
            join AnimalConsulta rep on rep.codigoAnimal = cr.codigoReprodutor
            left join CruzamentoAutorizado ca on ca.codigoCruzamento = cr.codigoCruzamento
            left join vComunicacao com on com.codigoComunicacao = cr.codigoComunicacao
        ");
    }
}