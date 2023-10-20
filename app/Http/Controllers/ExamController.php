<?php

namespace App\Http\Controllers;

use App\Exceptions\UndefinedCurrencyException;
use App\Http\Requests\CurrencyExchangeRequest;
use App\Services\CurrencyExchangeService;

class ExamController extends Controller
{

    private $currencyExchangeService;
    public function __construct(CurrencyExchangeService $currencyExchangeService)
    {
        // 注入CurrencyExchangeService
        $this->currencyExchangeService = $currencyExchangeService;
    }
    /**
     * 匯率換算
     * @param CurrencyExchangeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function currencyExchange(CurrencyExchangeRequest $request)
    {
        // 用formRequest進行驗證
        $validated = $request->validated();

        try {
            // 呼叫CurrencyExchangeService的convert方法進行匯率換算
            $exchangedAmount = $this->currencyExchangeService->convert(
                $validated['source'],
                $validated['target'],
                $validated['amount']
            );
        } catch (UndefinedCurrencyException $e) {
            return response()->json([
                'msg' => 'error',
                'errors' => [
                    $e->getField() => [$e->getMessage()],
                ],
            ], $e->getCode());
        }

        return response()->json([
            'msg' => 'success',
            'amount' => $exchangedAmount,
        ]);
    }
}
