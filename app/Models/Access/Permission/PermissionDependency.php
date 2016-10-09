<?php namespace App\Models\Access\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionDependency
 * @package App\Models\Access\Permission
 */
class PermissionDependency extends Model
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
        'permission_id' => 'integer',
        'dependency_id' => 'integer',
        'id' => 'integer'
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->table = config('access.permission_dependencies_table');
    }
    /**
     * @return mixed
     */
    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'dependency_id');
    }
}