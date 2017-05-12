<?php

namespace App\Transformers;

use App\Models\Access\Role\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id'   => (int) $role->id,
            'name' => $role->name,
        ];
    }
}
