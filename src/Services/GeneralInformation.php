<?php

namespace Aslam\Bca\Services;

use Aslam\Bca\Bca;

class GeneralInformation extends Bca
{
    /**
     * ForexRate
     *
     * @param  string $CurrencyCode
     * @param  string $RateType
     * @return \Aslam\Bca\Response
     */
    public function ForexRate(string $CurrencyCode, string $RateType)
    {
        $requestUrl = "/general/rate/forex?CurrencyCode={$CurrencyCode}&RateType={$RateType}";
        return $this->sendRequest('GET', $requestUrl);
    }

    /**
     * NearestBranchLocation
     *
     * @return void
     */
    public function NearestBranchLocation()
    {

    }

    /**
     * NearestATMLocation
     *
     * @param  string $Latitude
     * @param  string $Longitude
     * @param  string $Count
     * @param  string $Radius
     * @return \Aslam\Bca\Response
     */
    public function NearestATMLocation(string $Latitude, string $Longitude, string $Count = '10', string $Radius = '500')
    {
        $requestUrl = sprintf(
            '/general/info-bca/atm?SearchBy=%s&Latitude=%s&Longitude=%s&Count=%s&Radius=%s',
            'Distance',
            $Latitude,
            $Longitude,
            $Count,
            $Radius
        );

        return $this->sendRequest('GET', $requestUrl);
    }

    /**
     * Get all list RateType
     *
     * @return array
     */
    public function RateType()
    {
        return [
            'erate' => 'Electronic Rate',
            'tt' => 'Telegrafic Transfer',
            'tc' => 'Travellers Cheque',
            'bn' => 'Bank Notes',
        ];
    }

    /**
     * Get all list CurrencyCode
     *
     * @return array
     */
    public function CurrencyCode()
    {
        return [
            'AUD' => ' Australia Dollar',
            'BND' => 'Bruneian Dollar',
            'CAD' => 'Canadian Dollar',
            'CHF' => 'Francs',
            'CNY' => 'China Yuan',
            'DKK' => 'Danish Krone',
            'EUR' => 'Euro',
            'GBP' => 'Great Britain Poundsterling',
            'HKD' => 'Hongkong Dollar',
            'JPY' => 'Japan Yen',
            'KRW' => 'Korea Won',
            'NOK' => 'Norwegian Krone',
            'NZD' => 'New Zealand Dollar',
            'PHP' => 'Phillipine Peso',
            'SAR' => 'Saudi Riyal',
            'SEK' => 'Swedish Krona',
            'SGD' => 'Singapore Dollar',
            'THB' => 'Thailand Baht',
            'TWD' => 'Taiwan Dollar',
            'USD' => 'US Dollar',
        ];
    }
}
