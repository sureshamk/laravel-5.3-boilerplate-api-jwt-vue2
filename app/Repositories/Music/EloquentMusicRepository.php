<?php namespace App\Repositories\Music;

use App\Exceptions\GeneralException;
use App\Models\Access\Role\Role;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * Class EloquentRoleRepository
 * @package App\Repositories\Role
 */
class EloquentMusicRepository implements MusicRepositoryContract
{

    public function getPaginate($items, $count, $limit, $page)
    {
        return $paginator = new Paginator($items, $count, $limit, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }


    /**
     * @param $id
     * @param bool $withPermissions
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     * @throws GeneralException
     */


    public function findOrThrowException($id, $withPermissions = false)
    {
        if (!is_null(Role::find($id))) {
            if ($withPermissions) {
                return Role::with('permissions')->find($id);
            }

            return Role::find($id);
        }

        throw new GeneralException('That role does not exist.');
    }

    /**
     * @param $per_page
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getRolesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        $order_by = request()->has('sortBy') ? request()->get('sortBy') : $order_by;
        $sort = request()->has('sortOrder') ? request()->get('sortOrder') : $sort;
        $search = request()->has('search') ? request()->get('search') : '';


        $data = Role::with('permissions')
            ->orderBy($order_by, $sort)
            ->search($search)
            ->paginate($per_page);

        return $data;
    }

    /**
     * @param string $order_by
     * @param string $sort
     * @param bool $withPermissions
     * @return mixed
     */
    public function getAllRoles($order_by = 'sort', $sort = 'asc', $withPermissions = false)
    {
        if ($withPermissions) {
            return Role::with('permissions')->orderBy($order_by, $sort)->get();
        }

        return Role::orderBy($order_by, $sort)->get();
    }

    /**
     * @param $input
     * @return bool
     * @throws GeneralException
     */
    public function create($input)
    {
        //See if the role has all access
        $all = $input['associated_permissions'] == 1 ? true : false;

        if (!isset($input['permissions'])) {
            $input['permissions'] = [];
        }

        $role = new Role;
        $role->name = $input['name'];
        $role->sort = isset($input['sort']) && strlen($input['sort']) > 0 && is_numeric($input['sort']) ? (int)$input['sort'] : 0;

        //See if this role has all permissions and set the flag on the role
        $role->all = $all;

        if ($role->save()) {
            if (!$all) {
                $permissions = $input['permissions'];
                if ($permissions) {
                    $role->attachPermissions($permissions);
                }
            }

            return $role;
        }
        return false;
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     * @throws GeneralException
     */
    public function update($id, $input)
    {
        $role = $this->findOrThrowException($id);

        //See if the role has all access, administrator always has all access
        if ($role->id == 1) {
            $all = true;
        } else {
            $all = $input['associated_permissions'] == 1 ? true : false;
        }

        //This config is only required if all is false
        if (!$all) //See if the role must contain a permission as per config
        {
            if (config('access.roles.role_must_contain_permission') && count($input['permissions']) == 0) {
                throw new GeneralException('You must select at least one permission for this role.');
            }
        }

        $role->name = $input['name'];
        $role->sort = isset($input['sort']) && strlen($input['sort']) > 0 && is_numeric($input['sort']) ? (int)$input['sort'] : 0;

        //See if this role has all permissions and set the flag on the role
        $role->all = $all;

        if ($role->save()) {
            //If role has all access detach all permissions because they're not needed
            if ($all) {
                $role->permissions()->sync([]);
            } else {
                //Remove all roles first
                $role->permissions()->sync([]);

                $permissions = $input['permissions'];
                if ($permissions) {
                    $role->attachPermissions($permissions);
                }

            }

            return $role;
        }

        throw new GeneralException('There was a problem updating this role. Please try again.');
    }

    /**
     * @param $id
     * @return bool
     * @throws GeneralException
     */
    public function destroy($id)
    {
        //Would be stupid to delete the administrator role
        if ($id == 1) {
            return "You can not delete the Administrator role.";
        }//id is 1 because of the seeder


        $role = $this->findOrThrowException($id, true);

        //Don't delete the role is there are users associated
        if ($role->users()->count() > 0) {
            return "You can not delete a role with associated users.";
        }

        //Detach all associated roles
        $role->permissions()->sync([]);

        if ($role->delete()) {
            return true;
        }

        return "There was a problem deleting this role. Please try again.";
    }

    /**
     * @return mixed
     */
    public function getDefaultUserRole()
    {
        if (is_numeric(config('access.users.default_role'))) {
            return Role::where('id', (int)config('access.users.default_role'))->first();
        }
        return Role::where('name', config('access.users.default_role'))->first();
    }
}
