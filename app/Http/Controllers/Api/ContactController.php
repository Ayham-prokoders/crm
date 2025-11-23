<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Modules\DealManagement\Models\Invoice;

class ContactController extends Controller
{
    /**
     * get trainee's deals
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deals($id)
    {
        $user = User::findOrFail($id);
        $count = $user->deals()->count();
        $deals = $user->deals()->with('company')->get();
        $data = [
            'count' => $count,
            'data' => $deals
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's courses
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function courses($id)
    {
        $user = User::findOrFail($id);
        $courses = $user->trainees()->with('course')->get();
        $data = [
            'count' => $courses->count(),
            'data' => $courses
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's invoices
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoices($id)
    {
        $user = User::findOrFail($id);
        $invoices = Invoice::whereHas('deal.users', function ($q) use ($id) {
            $q->where('users.id', $id);
        })
        ->with('deal')
        ->get();
        $data = [
            'count' => $invoices->count(),
            'data' => $invoices
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's certificates
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function certificates($id)
    {
        $user = User::findOrFail($id);
        $certificates = $user->certificates()->get();
        $data = [
            'count' => $certificates->count(),
            'data' => $certificates
        ];
        return ResponseHelper::success($data);
    }
    



}
