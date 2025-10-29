<?php
namespace App\Concerns;

use Tapp\FilamentGoogleAutocompleteField\Concerns\HasGooglePlaceApi;

class CustomGooglePlaceApi extends HasGooglePlaceApi
{
    // Override the method in HasGooglePlaceApi that corresponds to line 150
      protected function getFormattedApiResults($data): array
    {
        $response = $data->collect();

        dd($data);
        if ($this->placesApiNew) {
            $addressComponents = $response['addressComponents'];

            $latLngFields = [
                'latitude' => [
                    'long_name' => $response['location']['latitude'],
                    'short_name' => $response['location']['latitude'],
                ],
                'longitude' => [
                    'long_name' => $response['location']['longitude'],
                    'short_name' => $response['location']['longitude'],
                ],
            ];

            $extraFields = $response->toArray();
        } else {
            $result = $response['result'];

            $addressComponents = $result['address_components'];

            $latLngFields = $result['geometry']['location'];

            $latLngFields = [
                'latitude' => [
                    'long_name' => $latLngFields['lat'],
                    'short_name' => $latLngFields['lat'],
                ],
                'longitude' => [
                    'long_name' => $latLngFields['lng'],
                    'short_name' => $latLngFields['lng'],
                ],
            ];

            $extraFields = $result;
        }

        // array map with keys
        $addressFields = array_merge(...array_map(function ($key, $item) {
            return [
                $item['types'][0] => [
                    'long_name' => $item[$this->currentApiNamingConventions['longText']],
                    'short_name' => $item[$this->currentApiNamingConventions['shortText']],
                ],
            ];
        }, array_keys($addressComponents), $addressComponents));

        // array map with keys
        $extraFields = array_merge(...array_map(function ($key, $item) {
            if (in_array($key, $this->currentApiNamingConventions['googleAddressExtraFieldNames'])) {
                $item = $key === 'displayName' ? $item['text'] : $item;

                return [
                    $key => [
                        'long_name' => $item,
                        'short_name' => $item,
                    ],
                ];
            } else {
                return [];
            }
        }, array_keys($extraFields), $extraFields));

        return array_merge($addressFields, $extraFields, $latLngFields);
    }
}
