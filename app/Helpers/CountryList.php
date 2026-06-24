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
        // Find ISO code by name first
        $match = collect(countries())->first(fn($c) => $c['name'] === $countryName);

        if (!$match) return 'Other';

        $code = $match['iso_3166_1_alpha2'] ?? null;
        if (!$code) return 'Other';

        try {
            $c = country(strtolower($code));
            $subregion = $c->getSubregion() ?? '';
        } catch (\Exception $e) {
            return 'Other';
        }

        return match($subregion) {
            'South-Eastern Asia'                     => 'Southeast Asia',
            'Eastern Asia'                           => 'East Asia',
            'Southern Asia'                          => 'South Asia',
            'Western Asia'                           => 'Middle East',
            'Northern Africa',
            'Sub-Saharan Africa',
            'Eastern Africa',
            'Western Africa',
            'Middle Africa',
            'Southern Africa'                        => 'Africa',
            'Northern Europe',
            'Southern Europe',
            'Western Europe',
            'Eastern Europe'                         => 'Europe',
            'Northern America',
            'Latin America and the Caribbean',
            'South America',
            'Central America',
            'Caribbean'                              => 'Americas',
            'Australia and New Zealand',
            'Melanesia',
            'Micronesia',
            'Polynesia'                              => 'Oceania',
            'Central Asia'                           => 'Central Asia',
            default                                  => 'Other',
        };
    }
}
