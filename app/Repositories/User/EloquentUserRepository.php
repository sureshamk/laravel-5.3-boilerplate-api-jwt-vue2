<?php

namespace App\Repositories\User;

use App\Exceptions\Access\User\UserNeedsRolesException;
use App\Exceptions\GeneralException;
use App\Models\Access\User\User;
use App\Models\Access\User\UserProvider;
use App\Repositories\Authentication\AuthenticationContract;
use App\Repositories\Role\RoleRepositoryContract;
use Hash;
use Mail;

/**
 * Class EloquentUserRepository.
 */
class EloquentUserRepository implements UserContract
{
    /**
     * @var RoleRepositoryContract
     */
    protected $role;

    /**
     * @var AuthenticationContract
     */
    protected $auth;

    /**
     * @param RoleRepositoryContract $role
     * @param AuthenticationContract $auth
     */
    public function __construct(RoleRepositoryContract $role)
    {
        $this->role = $role;
        //  $this->auth = $auth;
    }

    /**
     * @param string $order_by
     * @param string $sort
     *
     * @return mixed
     */
    public function get()
    {
        $query = User::query();

        return $query = $query->paginate();
    }

    /**
     * @param $input
     * @param $roles
     * @param $permissions
     *
     * @throws GeneralException
     * @throws UserNeedsRolesException
     *
     * @return User
     */
    public function create($input, $roles = [], $permissions = [])
    {
        $user = $this->createUserStub($input);

        if ($user->save()) {
            $this->updateUserDependencies($roles, $permissions, $user);
            //Send confirmation email if requested
            if (isset($input['confirmation_email']) && $user->confirmed == 0) {
                $this->auth->resendConfirmationEmail($user->id);
            }

            return $user;
        }

        throw new GeneralException('There was a problem creating this user. Please try again.');
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    private function createUserStub($input)
    {
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->status = isset($input['status']) ? 1 : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = isset($input['confirmed']) ? 1 : 0;

        return $user;
    }

    /**
     * @param $roles
     * @param $permissions
     * @param $user
     */
    public function updateUserDependencies($roles, $permissions, $user)
    {
        $user->roles()->sync($roles);
        $user->permissions()->sync($permissions);
    }

    public function findByUserNameOrCreate($data, $provider)
    {
        $user = User::where('email', $data->email)->first();
        $providerData = [
            'avatar'      => $data->avatar,
            'provider'    => $provider,
            'provider_id' => $data->id,
        ];

        if (!$user) {
            $user = $this->createFromFront([
                'name'  => $data->name,
                'email' => $data->email,
            ], true);
        }

        if ($this->hasProvider($user, $provider)) {
            $this->checkIfUserNeedsUpdating($provider, $data, $user);
        } else {
            $user->providers()->save(new UserProvider($providerData));
        }

        return $user;
    }

    public function createFromFront($data, $provider = false)
    {
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => $provider ? null : $data['password'],
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed'         => config('access.users.confirm_email') ? 0 : 1,
        ]);
        $user->attachRole($this->role->getDefaultUserRole());

        if (config('access.users.confirm_email') and $provider === false) {
            $this->sendConfirmationEmail($user);
        } else {
            $user->confirmed = 1;
        }

        return $user;
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendConfirmationEmail($user)
    {
        //$user can be user instance or id
        if (!$user instanceof User) {
            $user = User::findOrFail($user);
        }

        return Mail::send('emails.confirm', ['token' => $user->confirmation_code], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject(app_name().': Confirm your account!');
        });
    }

    public function hasProvider($user, $provider)
    {
        foreach ($user->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $provider
     * @param $providerData
     * @param $user
     */
    public function checkIfUserNeedsUpdating($provider, $providerData, $user)
    {
        //Have to first check to see if name and email have to be updated
        $userData = [
            'email' => $providerData->email,
            'name'  => $providerData->name,
        ];
        $dbData = [
            'email' => $user->email,
            'name'  => $user->name,
        ];
        $differences = array_diff($userData, $dbData);
        if (!empty($differences)) {
            $user->email = $providerData->email;
            $user->name = $providerData->name;
            $user->save();
        }

        //Then have to check to see if avatar for specific provider has changed
        $p = $user->providers()->where('provider', $provider)->first();
        if ($p->avatar != $providerData->avatar) {
            $p->avatar = $providerData->avatar;
            $p->save();
        }
    }

    public function updateProfile($input)
    {
        $user = access()->user();
        $user->name = $input['name'];

        if ($user->canChangeEmail()) {
            //Address is not current address
            if ($user->email != $input['email']) {
                //Emails have to be unique
                if (User::where('email', $input['email'])->first()) {
                    throw new GeneralException('That e-mail address is already taken.');
                }

                $user->email = $input['email'];
            }
        }

        return $user->save();
    }

    /**
     * @param $input
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function changePassword($input)
    {
        $user = $this->findOrThrowException(auth()->id());

        if (Hash::check($input['old_password'], $user->password)) {
            //Passwords are hashed on the model
            $user->password = $input['password'];

            return $user->save();
        }

        throw new GeneralException('That is not your old password.');
    }

    /**
     * @param $id
     * @param bool $withRoles
     *
     * @throws GeneralException
     *
     * @return mixed
     */
    public function findOrThrowException($id, $withRoles = false)
    {
        if ($withRoles) {
            $user = User::with('roles')->with('permissions')->withTrashed()->find($id);
        } else {
            $user = User::withTrashed()->find($id);
        }

        if (!is_null($user)) {
            return $user;
        }

        throw new GeneralException('That user does not exist.');
    }

    /**
     * @param $token
     *
     * @throws GeneralException
     */
    public function confirmAccount($token)
    {
        $user = User::where('confirmation_code', $token)->first();

        if ($user) {
            if ($user->confirmed == 1) {
                throw new GeneralException('Your account is already confirmed.');
            }

            if ($user->confirmation_code == $token) {
                $user->confirmed = 1;

                return $user->save();
            }

            throw new GeneralException('Your confirmation code does not match.');
        }

        throw new GeneralException('That confirmation code does not exist.');
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update($id, $input, $roles, $permissions)
    {
        $user = $this->findOrThrowException($id);
        $this->checkUserByEmail($input, $user);

        if ($user->update($input)) {
            //For whatever reason this just wont work in the above call, so a second is needed for now
            $user->status = isset($input['status']) ? 1 : 0;
            $user->confirmed = isset($input['confirmed']) ? 1 : 0;
            $user->save();
            $this->updateUserDependencies($roles, $permissions, $user);

            return true;
        }

        throw new GeneralException('There was a problem updating this user. Please try again.');
    }

    /**
     * @param $input
     * @param $user
     *
     * @throws GeneralException
     */
    private function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if (!empty($input['email']) && $user->email != $input['email']) {
            //Check to see if email exists
            if (User::where('email', '=', $input['email'])->first()) {
                throw new GeneralException('That email address belongs to a different user.');
            }
        }
    }

    /**
     * @param $id
     * @param $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function updatePassword($id, $input)
    {
        $user = $this->findOrThrowException($id);

        //Passwords are hashed on the model
        $user->password = $input['password'];
        if ($user->save()) {
            return true;
        }

        throw new GeneralException('There was a problem changing this users password. Please try again.');
    }

    /**
     * @param $id
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function destroy($id)
    {
        if (auth()->id() == $id) {
            throw new GeneralException('You can not delete yourself.');
        }

        $user = $this->findOrThrowException($id);
        if ($user->delete()) {
            return true;
        }

        throw new GeneralException('There was a problem deleting this user. Please try again.');
    }

    /**
     * @param $id
     *
     * @throws GeneralException
     *
     * @return bool|null
     */
    public function delete($id)
    {
        $user = $this->findOrThrowException($id, true);

        //Detach all roles & permissions
        $user->detachRoles($user->roles);
        $user->detachPermissions($user->permissions);

        try {
            $user->forceDelete();
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param $id
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore($id)
    {
        $user = $this->findOrThrowException($id);

        if ($user->restore()) {
            return true;
        }

        throw new GeneralException('There was a problem restoring this user. Please try again.');
    }

    /**
     * @param $id
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function mark($id, $status)
    {
        if (auth()->id() == $id && ($status == 0 || $status == 2)) {
            throw new GeneralException('You can not do that to yourself.');
        }

        $user = $this->findOrThrowException($id);
        $user->status = $status;

        if ($user->save()) {
            return true;
        }

        throw new GeneralException('There was a problem updating this user. Please try again.');
    }

    /**
     * @param $roles
     *
     * @throws GeneralException
     */
    private function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['roles']) == 0) {
            throw new GeneralException('You must choose at least one role.');
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    private function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['roles']);
    }

    /**
     * @param $permissions
     * @param $user
     */
    private function flushPermissions($permissions, $user)
    {
        //Flush permissions out, then add array of new ones if any
        $user->detachPermissions($user->permissions);
        if (count($permissions['permissions']) > 0) {
            $user->attachPermissions($permissions['permissions']);
        }
    }
}
