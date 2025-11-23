<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Twilio\Rest\Client;
use App\Mail\SendOtpEmail;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OtpService
{
    /**
     * The duration in minutes for which the OTP will be valid.
     * @var int
     */
    protected int $otpExpirationMinutes=5;

    /**
     * send otp code to user's email
     * @param array $data
     * @return array{message: string, status_code: int, success: bool}
     */
    public function sendOtp(array $data){
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return ['success' => false, 'message' => 'user not found', 'status_code' => 400];
        }
        $otp = (string)random_int(100000, 999999);
        $otpKey = 'email_verification_otp_' . $user->id;

        Cache::put($otpKey, $otp, now()->addMinutes($this->otpExpirationMinutes));
        try {
            Mail::to($user->email)->queue(new SendOtpEmail($otp, $user->name));
            return ['success' => true, 'message' => 'OTP code sended successfully', 'status_code' => 200];
        } catch (Exception $e) {
            logger()->error('Failed to send OTP email for user ID ' . $user->id . ': ' . $e->getMessage());
            return ['success' => false,'message' => 'Failed to send OTP email', 'status_code' => 200];
        }
    }

    /**
     * verify otp logic and return the user information
     * @param array $data
     * @return array{data: array{expires_at: string|null, first_login: mixed, instructor_id: null, token: string, user: UserResource, message: string, status_code: int, success: bool}|array{message: string, status_code: int, success: bool}|\Illuminate\Http\JsonResponse}
     */
    public function verifyOtp(array $data){
        $user = User::where('email', $data['email'])->first();

        $otpKey = 'email_verification_otp_' . $user->id;
        $cachedOtp = Cache::get($otpKey);

        if (!$cachedOtp) {
            return ['success' => false, 'message' => 'The verification code is invalid or expired.', 'status_code' => 400];
        }
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);


        $instructor_id = null;

        
        if ($cachedOtp === $data['otp']) {
            Cache::forget($otpKey);
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            $expiration = now()->addMinutes(config('sanctum.expiration'))->toISOString();
            
            return [
                'data' => [
                    'user'        => new UserResource($user),
                    'token'       => $token,
                    'expires_at' => $expiration,
                    'first_login' => $user->first_login,
                    'instructor_id' => $instructor_id,
                ],
                'success' => true,
                'message' => 'otp verified successfully',
                'status_code' => 200
            ];
        } else {
            return ['success' => false, 'message' => 'wrong code', 'status_code' => 400];
        }
    }


    /**
     * send sms otp
     * @param array $data
     * @return array{message: string, status_code: int, success: bool}
     */
    public function sendOtpBySms(array $data) {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !$user->phone) {
            return ['success' => false, 'message' => 'Phone number not found for this user', 'status_code' => 400];
        }
        $phoneNumber = $user->phone;
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        $otp = (string)random_int(100000, 999999);
        $otpKey = 'email_verification_otp_' . $user->id;

        Cache::put($otpKey, $otp, now()->addMinutes($this->otpExpirationMinutes));

        try {
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $twilio->messages->create(
                $phoneNumber,
                [
                    "from" => config('services.twilio.from'),
                    "body" => "Your verification code is: {$otp}"
                ]
            );

            return ['success' => true, 'message' => 'OTP sent successfully via SMS', 'status_code' => 200];
        } catch (Exception $e) {
            logger()->error("Failed to send OTP SMS for user ID {$user->id}: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to send OTP SMS', 'status_code' => 500];
        }
    }

}
