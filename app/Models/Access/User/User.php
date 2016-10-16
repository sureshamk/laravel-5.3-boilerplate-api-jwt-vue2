<?php namespace App\Models\Access\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models\Access\User
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable,
        CanResetPassword,
        SoftDeletes;

    use HasApiTokens, Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token','confirmation_code'];

    /**
     * For soft deletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $casts = [
        'status' => 'integer',
        'confirmed' => 'boolean',
        'id' => 'integer'
    ];


    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * Checks to see if user has array of roles
     * All must return true
     * @param $roles
     * @param $needsAll
     * @return bool
     */
    public function hasRoles($roles, $needsAll)
    {
        //User has to possess all of the roles specified
        if ($needsAll) {
            $hasRoles = 0;
            $numRoles = count($roles);

            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    $hasRoles++;
                }
            }

            return $numRoles == $hasRoles;
        }

        //User has to possess one of the roles specified
        $hasRoles = 0;
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                $hasRoles++;
            }
        }

        return $hasRoles > 0;
    }

    /**
     * Checks if the user has a Role by its name or id.
     *
     * @param string $nameOrId Role name or id.
     *
     * @return bool
     */
    public function hasRole($nameOrId)
    {
        foreach ($this->roles as $role) {
            //First check to see if it's an ID
            if (is_numeric($nameOrId)) {
                if ($role->id == $nameOrId) {
                    return true;
                }
            }

            //Otherwise check by name
            if ($role->name == $nameOrId) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $nameOrId
     * @return bool
     */
    public function hasPermission($nameOrId)
    {
        return $this->can($nameOrId);
    }

    /**
     * Check if user has a permission by its name or id.
     *
     * @param string $nameOrId Permission name or id.
     *
     * @return bool
     */
    public function can($nameOrId)
    {
        foreach ($this->roles as $role) {
            dd($role);
            //See if role has all permissions
            if ($role->all) {
                return true;
            }

            // Validate against the Permission table
            foreach ($role->permissions as $perm) {

                //First check to see if it's an ID
                if (is_numeric($nameOrId)) {
                    if ($perm->id == $nameOrId) {
                        return true;
                    }
                }

                //Otherwise check by name
                if ($perm->name == $nameOrId) {
                    return true;
                }
            }
        }

        //Check permissions directly tied to user
        foreach ($this->permissions as $perm) {

            //First check to see if it's an ID
            if (is_numeric($nameOrId)) {
                if ($perm->id == $nameOrId) {
                    return true;
                }
            }

            //Otherwise check by name
            if ($perm->name == $nameOrId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $permissions
     * @param bool $needsAll
     * @return bool
     */
    public function hasPermissions($permissions, $needsAll = false)
    {
        return $this->canMultiple($permissions, $needsAll);
    }

    /**
     * Check an array of permissions and whether or not all are required to continue
     * @param $permissions
     * @param $needsAll
     * @return bool
     */
    public function canMultiple($permissions, $needsAll = false)
    {
        //User has to possess all of the permissions specified
        if ($needsAll) {
            $hasPermissions = 0;
            $numPermissions = count($permissions);

            foreach ($permissions as $perm) {
                if ($this->can($perm)) {
                    $hasPermissions++;
                }
            }

            return $numPermissions == $hasPermissions;
        }

        //User has to possess one of the permissions specified
        $hasPermissions = 0;
        foreach ($permissions as $perm) {
            if ($this->can($perm)) {
                $hasPermissions++;
            }
        }

        return $hasPermissions > 0;
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('access.role'), config('access.assigned_roles_table'), 'user_id', 'role_id');
    }



    /**
     * Many-to-Many relations with Permission.
     * ONLY GETS PERMISSIONS ARE NOT ASSOCIATED WITH A ROLE
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(config('access.permission'), config('access.permission_user_table'), 'user_id',
            'permission_id');
    }

    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(UserProvider::class);
    }
}
