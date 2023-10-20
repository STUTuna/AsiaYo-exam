<?php

namespace Tests\Feature;

use Tests\TestCase;

class CurrencyExchangeServiceTest extends TestCase
{
    /**
     * 測試匯率換算
     */
    public function test_currencyExchange(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'USD',
            'target' => 'JPY',
            'amount' => 1000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                'amount' => '111,801.00',
            ]);
    }
    /**
     * 若輸入的金額為非數字或無法辨認
     */
    public function test_currencyExchange_with_nonNumeric_amount(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'TWD',
            'target' => 'USD',
            'amount' => '10000a',
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(422)
            ->assertJson([
                'msg' => 'error',
                'errors' => [
                    'amount' => [
                        'The amount field must be a number.',
                    ],
                ],
            ]);
    }

    /**
     * 測試匯率換算，amount為小數點後兩位
     */
    public function test_currencyExchange_with_amount_has_two_decimal(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'USD',
            'target' => 'JPY',
            'amount' => 1000.12,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                'amount' => "111,814.42",
            ]);
    }

    /**
     * 測試匯率換算，amount為小數點後三位, 且第三位數被捨去
     */
    public function test_currencyExchange_with_amount_has_three_decimal_and_third_decimal_is_lower(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'USD',
            'target' => 'JPY',
            'amount' => 1000.123,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                "amount" => "111,814.42",
            ]);
    }

    /**
     * 測試匯率換算，amount為小數點後三位, 且第三位數被進位
     */
    public function test_currencyExchange_with_amount_has_three_decimal_and_third_decimal_is_higher(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'USD',
            'target' => 'JPY',
            'amount' => 1000.125,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                'amount' => '111,815.53',
            ]);
    }

    /**
     * 測試匯率換算，但是source並不提供時
     */
    public function test_currencyExchange_without_source(): void
    {
        // 定義測試資料
        $getData = [
            'target' => 'USD',
            'amount' => 10000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(422)
            ->assertJson([
                'msg' => 'error',
                'errors' => [
                    'source' => [
                        'The source field is required.',
                    ],
                ],
            ]);
    }

    /**
     * 測試匯率換算，但使用的source沒提供時
     */
    public function test_currencyExchange_with_error_source(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'ASIA_YO',
            'target' => 'USD',
            'amount' => 10000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);
        $response->assertStatus(422)
            ->assertJson([
                'msg' => 'error',
                'errors' => [
                    'source' => [
                        '未定義的幣別，無法轉換',
                    ],
                ],
            ]);
    }

    /**
     * 測試匯率換算，但是target並不提供時
     */
    public function test_currencyExchange_without_target(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'TWD',
            'amount' => 10000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(422)
            ->assertJson([
                'msg' => 'error',
                'errors' => [
                    'target' => [
                        'The target field is required.',
                    ],
                ],
            ]);
    }

    /**
     * 測試匯率換算，但使用的target沒提供時
     */
    public function test_currencyExchange_with_error_target(): void
    {
        // 定義測試資料
        $getData = [
            'source' => 'TWD',
            'target' => 'ASIA_YO',
            'amount' => 10000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);
        $response->assertStatus(422)
            ->assertJson([
                'msg' => 'error',
                'errors' => [
                    'target' => [
                        '未定義的幣別，無法轉換',
                    ],
                ],
            ]);
    }
}
