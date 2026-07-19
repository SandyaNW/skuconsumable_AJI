<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\DetailDepartement;
use Spatie\Permission\Models\Role;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;


class UsersController extends Controller
{
    /**
     * Display all users
     * 
     * @return \Illuminate\Http\Response
     */
    // public function index() 
    public function index(Request $request)
    {
        // $users = User::latest()->paginate(10);
        // return view('users.index', compact('users'));

        // $roles = Role::all();

        // with softdeletes
        $users = User::latest();
        if($request->get('status') == 'archived') {
            $users = $users->onlyTrashed();
            // $users = $users->withTrashed();
        }
        $users = $users->paginate(10);
        return view('users.index', compact('users'));
        // return view('users.index', compact('users','roles'));
    }

    /**
     * Show form for creating user
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $data['departments']=Department::select('id','name')->orderBy('name')->get();
        $data['detail_departments']=DetailDepartement::select('detail_dept_id','name')->orderBy('name')->get();
        $data['positions']=Position::select('id','position')->orderBy('position')->get();
        return view('users.create', $data);
    }

    /**
     * Store a newly created user
     * 
     * @param User $user
     * @param StoreUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, StoreUserRequest $request) 
    {
        //For demo purposes only. When creating user or inviting a user
        // you should create a generated random password and email it to the user
        // dr4: pass default admin123

        User::create(array_merge($request->only('name', 'email','position_id','detail_dept_id', 'username', 'password', 'dept_id','npk')));

        // $user->create(array_merge($request->validated(), [
        //     'password' => 'test' 
        //     'password' => Hash::make($request->password) 
        // ]));

        // $request->user()->fill([
        //     'password' => Hash::make($request->newPassword)
        // ])->save();

        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    /**
     * Show user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) 
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) 
    {
        return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
        ]);
    }

    /**
     * Update user data
     * 
     * @param User $user
     * @param UpdateUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UpdateUserRequest $request) 
    {
        
        $user->update($request->validated());

        $user->syncRoles($request->get('role'));

        return redirect()->route('users.index')
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Delete user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) 
    {
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }

    /**
     *  Restore user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function restore($id) 
    {
        User::where('id', $id)->withTrashed()->restore();

        return redirect()->route('users.index', ['status' => 'archived'])
            ->withSuccess(__('User restored successfully.'));
    }

    /**
     * Force delete user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id) 
    {
        User::where('id', $id)->withTrashed()->forceDelete();

        return redirect()->route('users.index', ['status' => 'archived'])
            ->withSuccess(__('User force deleted successfully.'));
    }

    /**
     * Restore all archived users
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function restoreAll() 
    {
        User::onlyTrashed()->restore();

        return redirect()->route('users.index')->withSuccess(__('All users restored successfully.'));
    }

    public function toPortal(Request $request){

        $request->session()->invalidate();


        $request->session()->regenerateToken();

        Auth::logout();

        return redirect(env('API_BASE_URL'));

    }
}


/*

git clone https://github.com/codeanddeploy/laravel8-authentication-example.git

if your using my previous tutorial navigate your project folder and run composer update



install packages

composer require spatie/laravel-permission
composer require laravelcollective/html

then run php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate

php artisan make:migration create_posts_table

php artisan migrate

models
php artisan make:model Post

middleware
- create custom middleware
php artisan make:middleware PermissionMiddleware

register middleware
- 

routes

controllers

- php artisan make:controller UsersController
- php artisan make:controller PostsController
- php artisan make:controller RolesController
- php artisan make:controller PermissionsController

requests
- php artisan make:request StoreUserRequest
- php artisan make:request UpdateUserRequest

blade files

create command to lookup all routes
- php artisan make:command CreateRoutePermissionsCommand
- php artisan permission:create-permission-routes

seeder for default roles and create admin user
php artisan make:seeder CreateAdminUserSeeder
php artisan db:seed --class=CreateAdminUserSeeder



*/