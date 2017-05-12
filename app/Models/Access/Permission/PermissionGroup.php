<?php

namespace App\Models\Access\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionGroup.
 */
class PermissionGroup extends Model
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

    protected $fillable = ['name', 'sort', 'parent_id'];

    public function __construct()
    {
        $this->table = config('access.permission_group_table');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('sort', 'asc');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id')->orderBy('sort', 'asc');
    }
}
