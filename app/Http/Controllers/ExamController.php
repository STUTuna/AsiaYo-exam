<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyExchangeRequest;

class ExamController extends Controller
{
    // 匯率換算
    public function currencyExchange(CurrencyExchangeRequest $request)
    {
        // 用formRequest進行驗證
        $request->validated();
        return response()->json([
            'message' => 'success',
        ]);
    }
}
