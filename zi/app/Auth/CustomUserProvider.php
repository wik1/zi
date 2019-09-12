<?php namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App;
use Illuminate\Support\Facades\DB;
use App\Services\UsersService;

class CustomUserProvider implements UserProvider {

    protected $model;
    protected $usersService;

    public function __construct(Authenticatable $model, UsersService $usersService)
    {
        $this->model = $model;
        $this->usersService = $usersService;
    }

    public function retrieveById($identifier)
    {
        $user = $this->usersService->getUserById($identifier);
        return new App\User([
            'name' => $user->name,
            'email' => $user->mail,
            'ktrh' => $user->ktrh,
            'is_netto' => $user->is_netto
        ]);
    }

    public function retrieveByToken($identifier, $token)
    {
        return new App\User([
            'name' => 'JanToken',
            'email' => 'test_xyz@unifactor.pl',
            'ktrh' => 'testktrh'
        ]);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }


    public function retrieveByCredentials(array $credentials)
    {
        $users = $this->usersService->getUsersByEmailAndPassword($credentials['email'], $credentials['password']);

        if (count($users) > 0) {
            return $this->getGenericUser($users[0]);
        } else {
            return null;
        }
    }

    protected function getGenericUser($user)
    {
        if (! is_null($user)) {
            return new App\Auth\MyGenericUser((array) $user);
        }
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $user = $this->usersService->getUsersByEmailAndPassword($credentials['email'], $credentials['password']);
        return $user != NULL && $user[0] != NULL && $user[0]->KTRH != NULL;
    }

}