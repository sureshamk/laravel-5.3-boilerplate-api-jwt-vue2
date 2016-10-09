<?php

namespace App\Transformers;

use App\Models\Access\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'roles','permissions'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    /**
     * Include Author
     *
     * @return League\Fractal\ItemResource
     */
    public function includeRoles(User $user)
    {
        $roles = $user->roles;
        return $this->collection($roles, new RoleTransformer,['data' => 'id']);
    }

    /**
     * Include Permissions
     *
     * @return League\Fractal\ItemResource
     */
    public function includePermissions(User $user)
    {
        $permissions = $user->permissions;
        return $this->collection($permissions, new PermissionTransformer);
    }
}