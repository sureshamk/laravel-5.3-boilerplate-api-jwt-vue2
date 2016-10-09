<?php
namespace App\Api\V1\Controllers;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Access\Role\Role;
use App\Repositories\Permission\PermissionRepositoryContract;
use App\Repositories\Role\RoleRepositoryContract;
use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;

/**
 * Class RoleController
 * @package App\Http\Controllers\Access
 */
class RoleController extends Controller
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
     * @param RoleRepositoryContract $roles
     * @param PermissionRepositoryContract $permissions
     */
    public function __construct(RoleRepositoryContract $roles, PermissionRepositoryContract $permissions)
    {
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->response->paginator($this->roles->getAllRoles(), new RoleTransformer);
    }

    public function show($id)
    {
        try {
            return $role = $this->roles->findOrThrowException($id, true);
        } catch (GeneralException $e) {
            return $this->response->error($e->getMessage(), 500);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:roles',
        ];
        $this->validateApiRequest($request->all(), $rules);
        if ($data = $this->roles->create($request->all())) {
            return $this->response->noContent();
        } else {
            return $this->response->error('Could not store the role', 500);
        }

    }


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
        $rules = [
            'name' => 'required',
        ];
        $this->validateApiRequest($request->all(), $rules);
        if ($data = $this->roles->update($id, $request->all())) {
            return $this->response->noContent();
        } else {
            return $this->response->error('could_not_update_book', 500);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        if (Role::destroy($id)) {
            return $this->response->noContent();
        } else {
            return $this->response->error('could_not_delete_role', 500);
        }
    }
}
