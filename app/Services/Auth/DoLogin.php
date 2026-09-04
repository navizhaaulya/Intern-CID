<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\DB;
use App\CoreService\CoreException;
use App\CoreService\CoreService;
use App\CoreService\CustomException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Webpatser\Uuid\Uuid;

class DoLogin extends CoreService
{
    public $transaction = false;
    public $permission = null;
    public $noAuth = true;

    public function prepare($input)
    {
        return $input;
    }

    public function process($input, $originalInput)
    {
        #

        $credentials = [
            "identifier" => $input["identifier"],
            "password" => $input["password"]
        ];

        $loginAs = $input["login_as"];

        // CEK USER ID
        if ($loginAs == "siswa") {

            $user = DB::selectOne("SELECT users.*, roles.role_code, roles.role_name
                FROM users
                LEFT JOIN roles ON roles.id=users.role_id
                WHERE users.nisn=:identifier
                AND roles.role_code=:role_code", [
                    "identifier" => $input["identifier"],
                    "role_code" => $loginAs
                ]);

            $fieldType = "nisn";

        } else {

            $user = DB::selectOne("SELECT users.*, roles.role_code, roles.role_name
                FROM users
                LEFT JOIN roles ON roles.id=users.role_id
                WHERE (users.email=:identifier OR users.username=:identifier)
                AND roles.role_code=:role_code", [
                    "identifier" => $input["identifier"],
                    "role_code" => $loginAs
                ]);

            $fieldType = filter_var(
                $credentials["identifier"],
                FILTER_VALIDATE_EMAIL
            ) ? "email" : "username";
        }

        if (empty($user)) {
            throw new CoreException(
                __("message.userNotFound", [
                    "username" => $input["identifier"]
                ]),
                422
            );
        }

        $data_user_before_login["fullname"] = $user->fullname;
        $data_user_before_login["username"] = $user->username;
        $data_user_before_login["email"] = $user->email;
        $data_user_before_login["rel_role_id"] = $user->role_name;
        $data_user_before_login["status_code"] = $user->status_code;

        $response = [];

        if ($user->status_code == 'email_unverified') {

            $response["message"] = __("message.userEmailNotVerifiedYet", [
                'email' => $user->email
            ]);

            $response["data_user_before_login"] = $data_user_before_login;

            throw new CoreException(
                __("message.userEmailNotVerifiedYet", [
                    'email' => $user->email
                ]),
                422,
                $data_user_before_login
            );
        }

        if ($user->status_code == 'email_verified') {

            $response["message"] = __("message.userEmailVerifiedWaitingApproval", [
                'username' => $user->username
            ]);

            $response["data_user_before_login"] = $data_user_before_login;

            throw new CoreException(
                __("message.userEmailVerifiedWaitingApproval", [
                    'username' => $user->username
                ]),
                422,
                $data_user_before_login
            );
        }

        if ($user->status_code == 'user_rejected') {

            $response["message"] = __("message.userRejectedByAdmin", [
                'username' => $user->username
            ]);

            $response["data_user_before_login"] = $data_user_before_login;

            throw new CoreException(
                __("message.userRejectedByAdmin", [
                    'username' => $user->username
                ]),
                422,
                $data_user_before_login
            );
        }

        if ($user->status_code == 'user_nonactive') {

            $response["message"] = __("message.userNotActive", [
                'username' => $user->username
            ]);

            $response["data_user_before_login"] = $data_user_before_login;

            throw new CoreException(
                __("message.userNotActive", [
                    'username' => $user->username
                ]),
                422,
                $data_user_before_login
            );
        }

        if ($user->status_code != 'user_active') {

            $response["message"] = __("message.userNotActive", [
                'username' => $user->username
            ]);

            $response["data_user_before_login"] = $data_user_before_login;

            throw new CoreException(
                __("message.userNotActive", [
                    'username' => $user->username
                ]),
                422,
                $data_user_before_login
            );
        }

        // PROSES LOGIN
        if (Config::get("auth.defaults.guard") == "web") {

            if ($token = !Auth::attempt(array(
                $fieldType => $input["identifier"],
                "password" => $input["password"]
            ))) {

                // failed login attempt
                // DB::statement("UPDATE users SET failed_attempt=failed_attempt+1 WHERE username=:username", ["username" => $user->username]);

                throw new CoreException(
                    __("message.loginCredentialFalse"),
                    422
                );
            }

            Auth::loginUsingId($user->id);
            $token = null;

            // DB::statement("UPDATE users SET failed_attempt=0 WHERE username=:username", ["username" => $user->username]);

        } else {

            $session_token = Uuid::generate()->string;

            if ($token = Auth::claims([
                "session_id" => $session_token
            ])->attempt(array(
                $fieldType => $input["identifier"],
                "password" => $input["password"]
            ))) {

                JWTAuth::setToken($token);

                // DB::statement("UPDATE users SET failed_attempt=0, last_login_at=now() WHERE username=:username", ["username" => $user->username]);

            } else {

                // DB::statement("UPDATE users SET failed_attempt=0 WHERE username=:username", ["username" => $user->username]);

                throw new CoreException(
                    __("message.loginCredentialFalse"),
                    422
                );
            }
        }

        // REMOVE SOME PROPERTY OF OBJECT
        unset($user->password);
        unset($user->failed_attempt);
        // END REMOVE PROPERTY OF OBJECT

        return [
            "success" => true,
            "user" => $user,
            "token" => $token,
            "message" => __("message.loginSuccess"),
        ];
    }

    protected function validation()
    {
        return [
            "login_as" => "required|in:admin,guru,siswa",
            "identifier" => "required",
            "password" => "required",
            // "device" => "nullable|in:andoid,ios,web"
        ];
    }
}