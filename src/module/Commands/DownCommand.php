<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 04/08/2016
 * Time: 10:28
 */

namespace Girolando\Componentes\Cruzamento\Commands;


use Illuminate\Console\Command;

class DownCommand extends Command
{
    protected $signature = 'girolando:componentepessoa:down';
    protected $description = 'Remove a view comp.VPessoa';

    public function handle()
    {
        try {
            $this->info('Dropando view');
            $this->migrate();
            $this->info('Migration Executada!');
        } catch (\Exception $e) {
            $this->error('Houve uma falha: '. $e->getMessage());
        }
    }


    private function migrate()
    {
        \DB::statement("DROP VIEW comp.VPessoa;");
    }
}