<?php

namespace App\Transformers;

use App\Models\Access\Permission\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Permission $role)
    {
        return [
            'id' => (int)$role->id,
            'name' => $role->name,
        ];
    }

}