<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\VerifyEmailOtpRequest;
use App\Http\Requests\SendEmailVerificationOtpRequest;

class OtpController extends Controller
{
    /**
     * otpService
     * @var 
     */
    protected $otpService;

    /**
     *  get the Otp service for verification
     * @param \App\Services\OtpService $otpService
     */
    public function __construct(OtpService $otpService){
        $this->otpService = $otpService;
    }
    
    /**
     * resend the otp code
     * @param \App\Http\Requests\SendEmailVerificationOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(SendEmailVerificationOtpRequest $request)
    {
        $result = $this->otpService->sendOtp($request->validated());

        if ($result['success']) {
            return ResponseHelper::success();
        }
        
        return  ResponseHelper::operationFail($result['message']);
    }
    
    /**
     * verify the otp code within 5 minutes
     * @param \App\Http\Requests\VerifyEmailOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(VerifyEmailOtpRequest $request)
    {
        $result = $this->otpService->verifyOtp($request->validated());
        if ($result['success']) {
            return  ResponseHelper::success($result['data']);
        }
        return  ResponseHelper::operationFail($result['message']);
    }

    /**
     * send otp code by sms
     * @param \App\Http\Requests\SendEmailVerificationOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtpBySms(SendEmailVerificationOtpRequest $request)
    {
        $result = $this->otpService->sendOtpBySms($request->validated());

        if ($result['success']) {
            return ResponseHelper::success();
        }

        return ResponseHelper::operationFail($result['message']);
    }


}
