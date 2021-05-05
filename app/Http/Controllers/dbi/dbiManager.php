<?php

namespace App\Http\Controllers\dbi;

use Request;
use Auth;


class dbiManager {

    public function select ($user, $entity, $id) {

        $class = $this->checkEntity($entity);

        $provider = new dbiProvider(new $class());
        return $provider->select($user, $id);
    }

    // Helper Functions ---------------------------------------------------

    public function checkEntity ($entity) {
        $class = 'App\Http\Controllers\dbi\entities\\'.$entity;

        if (class_exists($class)) return $class;
        else die (abort(404, 'Call to unknown resource "'.$entity.'"!'));
    }
}


// Provider and Interface ------------------------------------------------

class dbiProvider {

    private $entity;

    function __construct (dbiInterface $entity) { $this->entity = $entity; }

    function select ($user, $input) { return $this->entity->select($user, $input); }
}


interface dbiInterface  {

    // Controller-Functions
    public function select ($user, $id);

    // Helper-Functions
    public function checkMatch ($input);
}
