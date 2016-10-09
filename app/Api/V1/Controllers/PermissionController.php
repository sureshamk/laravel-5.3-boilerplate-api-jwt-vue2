<?php
namespace App\Api\V1\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Access\Permission\Permission;
use App\Repositories\Permission\Group\PermissionGroupRepositoryContract;
use App\Repositories\Permission\PermissionRepositoryContract;
use App\Repositories\Role\RoleRepositoryContract;
use App\Transformers\PermissionTransformer;
use Dingo\Api\Contract\Http\Request;
use Exception;

/**
 * Class PermissionController
 * @package App\Http\Controllers\Access
 */
class PermissionController extends Controller
{

    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @var PermissionGroupRepositoryContract
     */
    protected $groups;

    /**
     * @param RoleRepositoryContract $roles
     * @param PermissionRepositoryContract $permissions
     * @param PermissionGroupRepositoryContract $groups
     */
    public function __construct(
        RoleRepositoryContract $roles,
        PermissionRepositoryContract $permissions,
        PermissionGroupRepositoryContract $groups
    ) {
        $this->roles = $roles;
        $this->permissions = $permissions;
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->response->paginator($this->permissions->getAllPermissions(), new PermissionTransformer);
    }

    public function show($id)
    {
        return $permission = $this->permissions->findOrThrowException($id, true);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:permissions',
                'display_name' => 'required',
            ];
            $this->validateApiRequest($request->all(), $rules);
            $this->permissions->create($request->except('roles'), $request->get('roles', []));
            return $this->response->noContent();
        } catch (GeneralException $e) {
            return $this->response->error('Could not store the permission', 500);
        }

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function edit($id, Request $request)
    {

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'display_name' => 'required'
            ];
            $this->validateApiRequest($request->all(), $rules);

            $this->permissions->update($id, $request->except('roles'), $request->only('roles'));
            return $this->response->noContent();
        } catch (GeneralException $e) {
            return $this->response->error('Could not update the permission', 500);
        }

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        try {
            $this->permissions->destroy($id);
            return $this->response->noContent();
        } catch (Exception $e) {
            return $this->response->error('Could not delete the permission', 500);
        }
    }
}
