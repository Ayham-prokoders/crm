<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        return ResponseHelper::success(new UserResource($user));
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
        ]);

        // Update the user's details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => $request->password ? bcrypt($request->password) : $user->password,
            // 'company' => $request->company,
            'phone' => $request->phone,
            'middel_name' => $request->middel_name,
            'last_name' => $request->last_name,
        ]);

        return ResponseHelper::success(new UserResource($user));
    }

    /**
     * Remove the authenticated user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return ResponseHelper::success();
    }

     /**
     * Change the authenticated user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        // Verify the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return ResponseHelper::invalidData("The old password is Wrong,please try again");
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return ResponseHelper::success(new UserResource($user));
    }

    /**
     * Update the authenticated user's profile image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateImage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Store the new image
        $file = $request->file('image');
        $name = time() . $file->getClientOriginalName();
        $path=$file->move('profile_images', $name);
        // Update the user's image path
        $user->update([
            'image' => $path,
        ]);

        return ResponseHelper::success(new UserResource($user));

    }


    

}
