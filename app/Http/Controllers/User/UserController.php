<?php

namespace App\Http\Controllers\User;

use App\Helpers\DynamicEnvironment;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdatePasswordUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('show-user', User::class);

        $users = User::paginate(15);

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $this->authorize('show-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        $roles = Role::all();

        $roles_ids = Role::rolesUser($user);

        return view('users.show', compact('user', 'roles', 'roles_ids'));
    }

    public function create()
    {
        $this->authorize('create-user', User::class);

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $max_id = User::getLastId();
        $this->authorize('create-user', User::class);

        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user = User::create($request->all() + ['user_id' => $max_id]);

        $this->saveLog("created user : ".json_encode($request->all() + ['user_id' => $max_id]), User::tableName());

        $roles = $request->input('roles') ? $request->input('roles') : [];

        $user->roles()->sync($roles);

        $this->flashMessage('check', 'User successfully added!', 'success');

        return redirect()->route('user.create');
    }

    public function edit($id)
    {
        $this->authorize('edit-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        $roles = Role::all();

        $roles_ids = Role::rolesUser($user);

        return view('users.edit', compact('user', 'roles', 'roles_ids'));
    }

    public function update(UpdateUserRequest $request, $id): RedirectResponse
    {
        $this->authorize('edit-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        $user->update($request->all());

        $this->saveLog("updated user : " . json_encode($request->all()), User::tableName());

        $roles = $request->input('roles') ? $request->input('roles') : [];

        $user->roles()->sync($roles);

        $this->flashMessage('check', 'User updated successfully!', 'success');

        return redirect()->route('user');
    }

    public function updatePassword(UpdatePasswordUserRequest $request, $id): RedirectResponse
    {
        $this->authorize('edit-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user->update($request->all());

        $this->saveLog("updated password : " . json_encode($request->all()), User::tableName());

        $this->flashMessage('check', 'User password updated successfully!', 'success');

        return redirect()->route('user');
    }

    public function editPassword($id)
    {
        $this->authorize('edit-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        return view('users.edit_password', compact('user'));
    }

    public function destroy($id): RedirectResponse
    {
        $this->authorize('destroy-user', User::class);

        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        $user->delete();

        $this->saveLog("deleted data (reference id): " . $id, User::tableName());

        $this->flashMessage('check', 'User successfully deleted!', 'success');

        return redirect()->route('user');
    }

    /**
     * update default brand
     * @param $id
     * @return JsonResponse
     */
    public function updateBrand($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array(
                'default_brand' => strval($id)
            ));
            $condition = array('user_id' => (int)Auth::user()->user_id);
            User::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $brandData = Brand::raw()->findOne(["brandid" => strval($id)]);
            if ($brandData !== NULL) {
                $brandName = $brandData["brandident"];
                $databaseName = DB::connection("mongodb")->getDatabaseName();

                DB::disconnect();
                Config::set("database.connections.mongodb.database", $brandName);
                DynamicEnvironment::setEnvValue("DB_DATABASE", $brandName);
                DB::purge('mongodb');
                DB::reconnect();
                $databaseName = DB::connection("mongodb")->getDatabaseName();
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => "doesn't exist brand"
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     *  add Brand permissions,
     * @param $id
     * @return JsonResponse
     */
    public function addBrand(Request $request): JsonResponse
    {
        try {
            $brand_data = $request->post();
            $ops = array(

                array(
                    '$group' => array('_id' => null, 'maxid' => array('$max' => array(
                        '$toDouble' => '$brandid'
                    )))
                )

            );
            $data = Brand::raw()->aggregate($ops)->toArray();
//        get max brand id
            if (count($data) === 0) {
                $brand_data["brandid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $brand_data["brandid"] = strval(++$max_id);
            }
            $brand_ident = preg_replace('/\s*/', '', $brand_data["brandname"]);
            // convert the string to all lowercase
            $brand_ident = strtolower($brand_ident);
            $brand_data["brandident"] = $brand_ident;
            $document = Brand::raw()->findOne(['brandident' => $brand_ident]);
            if ($document === NULL || count($document) == 0) {
                //      insert brand data to brands table
                Brand::Create($brand_data);
                $brandPermissions = explode(",", Auth::user()->brand_permissions);
                if (!in_array($brand_data["brandid"], $brandPermissions)) {
                    array_push($brandPermissions, $brand_data["brandid"]);
                    $update_data = array('$set' => array(
                        'brand_permissions' => implode(",", $brandPermissions)
                    ));
                    $condition = array('user_id' => (int)Auth::user()->user_id);
                    User::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    $collectionList = DB::connection('mongodb')->listCollections();
                    $originDatabase = DB::connection('mongodb')->getDatabaseName();
                    DB::disconnect();
                    Config::set("database.connections.mongodb.database", $brand_ident);
                    DB::purge('mongodb');
                    DB::reconnect();
                    foreach ($collectionList as $item) {
                        DB::connection('mongodb')->createCollection($item->getName());
                    }
                    DB::disconnect();
                    Config::set("database.connections.mongodb.database", $originDatabase);
                    DB::purge('mongodb');
                    DB::reconnect();
                }
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'already exist same brands, please input other name'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
