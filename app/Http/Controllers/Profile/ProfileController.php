<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        $roles = Role::all();

        $roles_ids = Role::rolesUser($user);

        return view('profile.index', compact('user', 'roles', 'roles_ids'));
    }


    public function updateProfile(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating profile!', 'danger');
            return redirect()->route('profile');
        }

        $user->update($request->all());

        $this->saveLog("profile updated : " . json_encode($request->all()), User::tableName());

        $this->flashMessage('check', 'Profile updated successfully!', 'success');

        return redirect()->route('profile');
    }

    public function updatePassword(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating password!', 'danger');
            return redirect()->route('profile');
        }

        $request->merge(['password' => bcrypt($request->get('password'))]);

        $user->update($request->all());

        $this->saveLog("password updated : " . json_encode($request->all()), User::tableName());


        $this->flashMessage('check', 'Password updated successfully!', 'success');

        return redirect()->route('profile');
    }

    public function updateAvatar(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::find((int)$id);

        if (!$user) {
            $this->flashMessage('warning', 'User not found!', 'danger');
            return redirect()->route('user');
        }

        if ($user != Auth::user()) {
            $this->flashMessage('warning', 'Error updating avatar!', 'danger');
            return redirect()->route('profile');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
        ]);

        if ($request->file('avatar')) {
            $file = $request->file('avatar');
            $ext = $file->guessClientExtension();
            $file->move("profiles/$id", "avatar.{$ext}");
            User::where('user_id', (int)$id)->update(['avatar' => "profiles/$id/avatar.{$ext}"]);

            $this->saveLog("avatar updated : " . "profiles/$id/avatar.{$ext}", User::tableName());
        }

        $this->flashMessage('check', 'Avatar updated successfully!', 'success');

        return redirect()->route('profile');
    }
}
