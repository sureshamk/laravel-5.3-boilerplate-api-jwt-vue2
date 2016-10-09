<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Access\User\User;
use App\Repositories\Permission\PermissionRepositoryContract;
use App\Repositories\Role\RoleRepositoryContract;
use App\Repositories\User\UserContract;
use App\Transformers\UserTransformer;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class UserController extends Controller
{

    /**
     * @var UserContract
     */
    protected $users;

    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @param UserContract $users
     * @param RoleRepositoryContract $roles
     * @param PermissionRepositoryContract $permissions
     */
    public function __construct(
        UserContract $users,
        RoleRepositoryContract $roles,
        PermissionRepositoryContract $permissions
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response->paginator($this->users->get(), new UserTransformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        try {
            $this->users->create($request->except('roles', 'permissions'), $request->get('roles', []),
                $request->get('permissions', []));
            return $this->response->accepted();
        } catch (\Exception $e) {
            return $this->response->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        try {
            $this->users->update($id, $request->except('roles', 'permissions'), $request->get('roles', []),
                $request->get('permissions', []));
            return $this->response->accepted();
        } catch (\Exception $e) {
            return $this->response->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (User::destroy($id)) {
            return $this->response->noContent();
        } else {
            return $this->response->error('could_not_delete_user', 500);
        }


    }
}
