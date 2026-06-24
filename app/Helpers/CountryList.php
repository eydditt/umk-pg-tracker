<?php

namespace App\Helpers;

class CountryList
{
    public static function options(): array
    {
        return collect(countries())
            ->mapWithKeys(fn($country, $code) => [
                $country['name'] => ($country['emoji'] ?? '') . ' ' . $country['name']
            ])
            ->sortKeys()
            ->toArray();
    }

    public static function region(string $countryName): string
    {
        $match = collect(countries())
            ->first(fn($c) => $c['name'] === $countryName);

        return $match['region'] ?? 'Other';
    }
}