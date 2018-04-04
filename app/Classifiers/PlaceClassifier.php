<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 4/2/18
 * Time: 3:38 PM
 */

namespace App\Classifiers;

use SKAgarwal\GoogleApi\PlacesApi;

class PlaceClassifier extends BaseClassifier
{
    private $placeApiKeys = [
        'AIzaSyBCawTXydOcoQA4uzw_OFchbrWqgMV1O_U',
    ];

    public function process($name)
    {
        $place = $this->find($name);

        if(!$place) {
            return null;
        }

        $first = array_first($place);

        $photoReference = array_get(array_first(array_get($first, 'photos')), 'photo_reference');

        $photoSrc = $photoReference ? 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$photoReference.'&sensor=false&key='.$this->getApiKey() : null;

        return [
            'name' => array_get($first, 'name'),
            'id' => array_get($first, 'place_id'),
            'photo_reference' => $photoReference,
            'photo_src' => $photoSrc,
            'types' => array_get($first, 'types'),
            'original' => $place,
        ];
    }

    private function find($name)
    {
        $googlePlaces = new PlacesApi($this->getApiKey());

        try {
            return $googlePlaces->textSearch($name)->get('results');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getApiKey()
    {
        return array_random($this->placeApiKeys);
    }
}