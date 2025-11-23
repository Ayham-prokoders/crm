<?php

namespace Modules\DealManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use Illuminate\Http\Request;
use Modules\DealManagement\Http\Requests\BankRequest;
use Modules\DealManagement\Models\Bank;
use Modules\DealManagement\Transformers\BankResource;

class BankController extends Controller
{
    public function store(BankRequest $request)
    {
        $bank = Bank::create($request->validated());

        return ResponseHelper::success(new BankResource($bank));
    }

    public function index(Request $request)
    {
        $banks = Bank::paginate($request->per_page);
        return ResponseHelper::success($banks);
    }

    public function list(Request $request)
    {
        $banks = Bank::all();
        return ResponseHelper::success($banks);
    }

    public function update(BankRequest $request, Bank $bank)
    {
        $bank->update($request->validated());
        return ResponseHelper::success(new BankResource($bank));
    }

    public function show(Bank $bank)
    {
        return new BankResource($bank);
    }
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return ResponseHelper::success();
    }
}
