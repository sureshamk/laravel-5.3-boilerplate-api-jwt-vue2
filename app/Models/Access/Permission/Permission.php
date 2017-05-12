<?php

namespace App\Models\Access\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission.
 */
class Permission extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $casts = [
        //'all' => 'integer',
        'group_id' => 'integer',
        'system'   => 'boolean',
        'sort'     => 'integer',
        'id'       => 'integer',

    ];

    public function __construct()
    {
        $this->table = config('access.permissions_table');
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(config('access.role'), config('access.permission_role_table'), 'permission_id',
            'role_id');
    }

    /**
     * @return mixed
     */
    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.model'), config('access.permission_user_table'), 'permission_id',
            'user_id');
    }

    /**
     * @return mixed
     */
    public function dependencies()
    {
        return $this->hasMany(PermissionDependency::class, 'permission_id', 'id');
    }
}
