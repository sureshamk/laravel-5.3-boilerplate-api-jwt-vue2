<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Permission\Group\PermissionGroupRepositoryContract;
use App\Transformers\PermissionGroupTransformer;
use Illuminate\Http\Request;

/**
 * Class PermissionGroupController.
 */
class PermissionGroupController extends Controller
{
    /**
     * @var PermissionGroupRepositoryContract
     */
    protected $groups;

    /**
     * @param PermissionGroupRepositoryContract $groups
     */
    public function __construct(PermissionGroupRepositoryContract $groups)
    {
        $this->groups = $groups;
    }

    public function index()
    {
        return $this->response->paginator($this->groups->getAllGroups(), new PermissionGroupTransformer());
    }

    /**
     * @param CreatePermissionGroupRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
    }

    /**
     * @param StorePermissionGroupRequest $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:permission_groups',
            ];
            $this->validateApiRequest($request->all(), $rules);

            $this->groups->store($request->all());

            return $this->response->noContent();
        } catch (GeneralException $e) {
            return $this->response->error($e->getMessage().'Could not store the permission group', 500);
        } catch (\ErrorException $e) {
            return $this->response->error($e->getMessage(), 500);
        }
    }

    /**
     * @param $id
     * @param EditPermissionGroupRequest $request
     *
     * @return mixed
     */
    public function edit($id, Request $request)
    {
        return view('backend.access.roles.permissions.groups.edit')
            ->withGroup($this->groups->find($id));
    }

    /**
     * @param $id
     * @param UpdatePermissionGroupRequest $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:permission_groups,id,'.$request->get('id'),
            ];
            $this->validateApiRequest($request->all(), $rules);

            $this->groups->update($id, $request->all());

            return $this->response->noContent();
        } catch (GeneralException $e) {
            return $this->response->error($e->getMessage().'Could not store the permission group', 500);
        } catch (\ErrorException $e) {
            return $this->response->error($e->getMessage(), 500);
        }
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        try {
            $this->groups->destroy($id);

            return $this->response->noContent();
        } catch (Exception $e) {
            return $this->response->error('Could not delete the group permission', 500);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Request $request)
    {
        $this->groups->updateSort($request->get('data'));

        return response()->json(['status' => 'OK']);
    }
}
