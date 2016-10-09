<?php

namespace App\Transformers;

use App\Models\Access\Permission\PermissionGroup;
use League\Fractal\TransformerAbstract;

class PermissionGroupTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(PermissionGroup $role)
    {
        return [
            'id' => (int)$role->id,
            'name' => $role->name,
        ];
    }

}