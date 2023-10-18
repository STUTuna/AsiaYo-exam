<?php
namespace App\Services;

class CurrencyExchangeService
{
    // 匯率表
    private $currencies;

    /**
     * CurrencyExchangeService constructor.
     * @param array $currencies 匯率表
     */
    public function __construct(array $currencies)
    {
        // 將匯率表存入此service變數
        $this->currencies = $currencies;
    }

    /**
     * 轉換匯率
     * @param string $source 來源幣別
     * @param string $target 目標幣別
     * @param float $amount 金額
     * @return string 轉換後的金額
     */
    public function convert($source, $target, $amount)
    {
        // 檢查來源幣別與目標幣別是否存在
        if (!isset($this->currencies[$source]) || !isset($this->currencies[$source][$target])) {
            throw new \Exception('未定義的幣別，無法轉換');
        }

        // 移除千分位，並轉換成float
        $amount = floatval(str_replace(',', '', $amount));

        // 匯率換算 = 金額 * 匯率
        $convertedAmount = $amount * $this->currencies[$source][$target];

        // 回傳轉換後的金額(1. 四捨五入到小數點後兩位 2. 加上半形逗點作為千分位表示，每三個位數一點)
        return number_format($convertedAmount, 2, '.', ',');
    }

}
