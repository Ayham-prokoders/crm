<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class IntegrationController extends Controller
{
    /**
     * Summary of updateProfile
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'nullable|string|max:255',
            'middel_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'unique:users,email,' . $request->id],
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|url',
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'name' => $request->name,
            'middel_name' => $request->middel_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $request->image,
        ]);

        return ResponseHelper::success($user);
    }

    /**
     * Summary of changePassword
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($request->id);
        $user->update(['password' => Hash::make($request->new_password)]);

        return ResponseHelper::success('Password updated successfully.');
    }
    
    /**
     * Summary of updateImage
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'image' => 'required|url',
        ]);

        $user = User::findOrFail($request->id);
        $user->update(['image' => $request->image]);

        return ResponseHelper::success($user);
    }
}
