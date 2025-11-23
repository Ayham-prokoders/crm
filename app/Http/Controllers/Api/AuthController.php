<?php

namespace App\Http\Controllers\Api;

use App\Models\{User};
use App\Models\Invitation;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Events\UserAssignedToClass;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\TelegramService;
use Illuminate\Validation\ValidationException;
use Modules\TrainerManagement\Models\Instructor;
use App\Notifications\AssignedToClassNotification;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService){
        $this->otpService = $otpService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        DB::beginTransaction();
        try {
            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => [__('message.user_not_found')],
                ]);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => [__('message.credentials_incorrect')],
                ]);
            }

            $role = Role::find($user->current_role_id);
            $blockedRoles = ['trainer', 'pre_trainer', 'trainee'];
            if ($role && in_array($role->name, $blockedRoles)) {
                return ResponseHelper::invalidData(__('message.unauthorized'));
            }

            $otpResult = $this->otpService->sendOtp(['email' => $user->email]);
            if (!$otpResult['success']) {
                throw new \Exception($otpResult['message']);
            }

            app(TelegramService::class)->sendMessage(
                "<b>Login Successful</b>\n {$user->name} ({$user->email})\n " . now()->toDateTimeString()
            );

            DB::commit();
            return ResponseHelper::success();
        } catch (ValidationException $ve) {
            DB::rollBack();
            return ResponseHelper::invalidData($ve->errors());
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Login Error: '.$e->getMessage(), ['exception' => $e]);
            return ResponseHelper::operationFail();
        }
    }
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return ResponseHelper::success('Logout successful');
    }

    public function register(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'middel_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ]);

        $invitation_token = $request->input('invitation_token');
        $class_id = $request->input('class_id');

        // Retrieve the invitation by token
        $invitation = Invitation::where('token', $invitation_token)->first();

        // Check if the invitation exists and is not expired
        if (!$invitation || !$this->isValidInvitation($invitation)) {
            return ResponseHelper::invalidData(__('message.invalid_token'));
        }

        // Check if the user already exists
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'middel_name' => $data['middel_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'company_id' => $data['company'],
                'phone' => $data['phone'],
            ]);
        }

        // Generate token for the user
        $token = $user->createToken('AuthToken')->plainTextToken;
        $expiration = now()->addMinutes(config('sanctum.expiration'))->toISOString();

        // Register user in class
        $user->trainees()->attach($class_id);

        $user->assignRole('trainee');
        $role = Role::where('name', 'trainee')->first();
        $user->update(['current_role_id' => $role->id, 'role' => $role->id]);
        $user->notify(new AssignedToClassNotification($user->id));
        event(new UserAssignedToClass($user));
        return ResponseHelper::success(['token' => $token, 'expires_at' => $expiration, 'user' => new UserResource($user)]);
    }

    /**
     * Check if the invitation is valid
     */
    protected function isValidInvitation(Invitation $invitation)
    {
        return $invitation->created_at->diffInHours(now()) <= 24;
    }
}
