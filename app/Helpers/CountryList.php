<?php

namespace App\Helpers;

class CountryList
{
    public static function options(): array
    {
        return [
            // Southeast Asia
            'Malaysia'              => '🇲🇾 Malaysia',
            'Indonesia'             => '🇮🇩 Indonesia',
            'Thailand'              => '🇹🇭 Thailand',
            'Singapore'             => '🇸🇬 Singapore',
            'Philippines'           => '🇵🇭 Philippines',
            'Vietnam'               => '🇻🇳 Vietnam',
            'Myanmar'               => '🇲🇲 Myanmar',
            'Cambodia'              => '🇰🇭 Cambodia',
            'Laos'                  => '🇱🇦 Laos',
            'Brunei'                => '🇧🇳 Brunei',
            // East Asia
            'China'                 => '🇨🇳 China',
            'Japan'                 => '🇯🇵 Japan',
            'South Korea'           => '🇰🇷 South Korea',
            // South Asia
            'India'                 => '🇮🇳 India',
            'Pakistan'              => '🇵🇰 Pakistan',
            'Bangladesh'            => '🇧🇩 Bangladesh',
            'Sri Lanka'             => '🇱🇰 Sri Lanka',
            'Nepal'                 => '🇳🇵 Nepal',
            // Middle East
            'Iran'                  => '🇮🇷 Iran',
            'Iraq'                  => '🇮🇶 Iraq',
            'Jordan'                => '🇯🇴 Jordan',
            'Saudi Arabia'          => '🇸🇦 Saudi Arabia',
            'Yemen'                 => '🇾🇪 Yemen',
            'Turkey'                => '🇹🇷 Turkey',
            // Africa
            'Nigeria'               => '🇳🇬 Nigeria',
            'Ghana'                 => '🇬🇭 Ghana',
            'Kenya'                 => '🇰🇪 Kenya',
            'Egypt'                 => '🇪🇬 Egypt',
            'Libya'                 => '🇱🇾 Libya',
            'Sudan'                 => '🇸🇩 Sudan',
            'Somalia'               => '🇸🇴 Somalia',
            'Tanzania'              => '🇹🇿 Tanzania',
            'Ethiopia'              => '🇪🇹 Ethiopia',
            'Cameroon'              => '🇨🇲 Cameroon',
            'Zimbabwe'              => '🇿🇼 Zimbabwe',
            'South Africa'          => '🇿🇦 South Africa',
            'Algeria'               => '🇩🇿 Algeria',
            'Morocco'               => '🇲🇦 Morocco',
            'Tunisia'               => '🇹🇳 Tunisia',
            // Europe
            'United Kingdom'        => '🇬🇧 United Kingdom',
            'France'                => '🇫🇷 France',
            'Germany'               => '🇩🇪 Germany',
            'Netherlands'           => '🇳🇱 Netherlands',
            'Russia'                => '🇷🇺 Russia',
            // Americas
            'United States'         => '🇺🇸 United States',
            'Canada'                => '🇨🇦 Canada',
            'Brazil'                => '🇧🇷 Brazil',
            // Oceania
            'Australia'             => '🇦🇺 Australia',
            'New Zealand'           => '🇳🇿 New Zealand',
            // Other
            'Other'                 => '🌍 Other',
        ];
    }

    public static function region(string $country): string
    {
        $regions = [
            'Southeast Asia' => ['Malaysia','Indonesia','Thailand','Singapore','Philippines','Vietnam','Myanmar','Cambodia','Laos','Brunei'],
            'East Asia'      => ['China','Japan','South Korea'],
            'South Asia'     => ['India','Pakistan','Bangladesh','Sri Lanka','Nepal'],
            'Middle East'    => ['Iran','Iraq','Jordan','Saudi Arabia','Yemen','Turkey'],
            'Africa'         => ['Nigeria','Ghana','Kenya','Egypt','Libya','Sudan','Somalia','Tanzania','Ethiopia','Cameroon','Zimbabwe','South Africa','Algeria','Morocco','Tunisia'],
            'Europe'         => ['United Kingdom','France','Germany','Netherlands','Russia'],
            'Americas'       => ['United States','Canada','Brazil'],
            'Oceania'        => ['Australia','New Zealand'],
        ];

        foreach ($regions as $region => $countries) {
            if (in_array($country, $countries)) return $region;
        }

        return 'Other';
    }
}
