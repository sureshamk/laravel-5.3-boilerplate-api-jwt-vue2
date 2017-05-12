<?php

namespace App\Repositories\User;

/**
 * Interface UserContract.
 */
interface UserContract
{
    /**
     * @param string $order_by
     * @param string $sort
     *
     * @return mixed
     */
    public function get();

    /**
     * @param $input
     * @param $roles
     *
     * @return mixed
     */
    public function create($input, $roles, $permissions);

    /**
     * @param $id
     * @param $input
     * @param $roles
     *
     * @return mixed
     */
    public function update($id, $input, $roles, $permissions);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function restore($id);

    /**
     * @param $id
     * @param $status
     *
     * @return mixed
     */
    public function mark($id, $status);

    /**
     * @param $id
     * @param $input
     *
     * @return mixed
     */
    public function updatePassword($id, $input);

    /**
     * @param $data
     *
     * @return mixed
     */
    public function createFromFront($data);

    /**
     * @param $data
     *
     * @return mixed
     */
    public function findByUserNameOrCreate($data, $provider);

    /**
     * @param $provider
     * @param $providerData
     * @param $user
     *
     * @return mixed
     */
    public function checkIfUserNeedsUpdating($provider, $providerData, $user);

    /**
     * @param $input
     *
     * @return mixed
     */
    public function updateProfile($input);

    /**
     * @param $input
     *
     * @return mixed
     */
    public function changePassword($input);

    /**
     * @param $token
     *
     * @return mixed
     */
    public function confirmAccount($token);

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendConfirmationEmail($user);
}
