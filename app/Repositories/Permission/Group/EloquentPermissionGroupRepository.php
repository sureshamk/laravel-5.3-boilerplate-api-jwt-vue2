<?php namespace App\Repositories\Permission\Group;

use App\Exceptions\GeneralException;
use App\Models\Access\Permission\PermissionGroup;

/**
 * Class EloquentPermissionGroupRepository
 * @package App\Repositories\Permission\Group
 */
class EloquentPermissionGroupRepository implements PermissionGroupRepositoryContract
{

    /**
     * @param int $limit
     * @return mixed
     */
    public function getGroupsPaginated($limit = 50)
    {
        return PermissionGroup::with('children', 'permissions')
            ->whereNull('parent_id')
            ->orderBy('sort', 'asc')->paginate($limit);
    }

    /**
     * @param bool $withChildren
     * @return mixed
     */
    public function getAllGroups($withChildren = false)
    {
        $query = PermissionGroup::query();
        return $query = $query->paginate();
    }

    /**
     * @param $input
     * @return static
     */
    public function store($input)
    {
        $group = new PermissionGroup;
        $group->name = $input['name'];
        $group->parent_id = @$input['parent_id'];
        return $group->save();
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $input)
    {
        $group = $this->find($id);
        return $group->update($input);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return PermissionGroup::findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        $group = $this->find($id);

        if ($group->children->count()) {
            throw new GeneralException("You can not delete this group because it has child groups.");
        }

        if ($group->permissions->count()) {
            throw new GeneralException("You can not delete this group because it has associated permissions.");
        }

        return $group->delete();
    }

    /**
     * @param $hierarchy
     * @return bool
     */
    public function updateSort($hierarchy)
    {
        $parent_sort = 1;
        $child_sort = 1;

        foreach ($hierarchy as $group) {
            $this->find((int)$group['id'])->update([
                'parent_id' => null,
                'sort' => $parent_sort
            ]);

            if (isset($group['children']) && count($group['children'])) {
                foreach ($group['children'] as $child) {
                    $this->find((int)$child['id'])->update([
                        'parent_id' => (int)$group['id'],
                        'sort' => $child_sort
                    ]);

                    $child_sort++;
                }
            }

            $parent_sort++;
        }

        return true;
    }
}
