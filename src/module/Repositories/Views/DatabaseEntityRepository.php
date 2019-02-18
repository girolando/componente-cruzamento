<?php
namespace Girolando\Componentes\Cruzamento\Repositories\Views;

use Andersonef\Repositories\Abstracts\RepositoryAbstract;
use Girolando\Componentes\Cruzamento\Providers\ComponentProvider;

/**
 * Data repository to work with entity DatabaseEntity.
 *
 * Class DatabaseEntityRepository
 */
class DatabaseEntityRepository extends RepositoryAbstract{


    public function entity()
    {
        return ComponentProvider::$entity;
    }

}