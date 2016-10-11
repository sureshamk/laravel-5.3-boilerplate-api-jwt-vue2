<?php
namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Repositories\Permission\PermissionRepositoryContract;
use App\Repositories\Role\RoleRepositoryContract;
use App\Repositories\User\UserContract;
use Config;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthController extends Controller
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

    public function __construct(
        UserContract $users,
        RoleRepositoryContract $roles,
        PermissionRepositoryContract $permissions
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    public function signup(Request $request)
    {
        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:3'
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        try {
            $user = $this->users->create($request->except('roles', 'permissions'));
            if (!$user->id) {
                return $this->response->error('could_not_create_user', 500);
            }

            $hasToReleaseToken = Config::get('boilerplate.signup_token_release');
            if ($hasToReleaseToken) {
                return $this->login($request);
            }

            return $this->response->created();
        } catch (\Exception $e) {
            return $this->response->error($e->getMessage(), 500);
        }

    }

    public function login(Request $request)
    {

        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }
        return response()->json(compact('token'));
    }

    public function recovery(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }

    public function reset(Request $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        switch ($response) {
            case Password::PASSWORD_RESET:
                if (Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return $this->response->noContent();
            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }
}