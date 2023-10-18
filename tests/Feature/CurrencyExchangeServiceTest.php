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
            'source' => 'TWD',
            'target' => 'USD',
            'amount' => 10000,
        ];
        // 透過http_build_query將陣列轉換成query string
        $appendUrl = '?' . http_build_query($getData);
        // 呼叫currencyExchange API
        $response = $this->get('/currencyExchange' . $appendUrl);

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                'amount' => 328.1,
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
