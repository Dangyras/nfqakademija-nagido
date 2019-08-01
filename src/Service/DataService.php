<?php
/**
 * Created by PhpStorm.
 * User: dangis
 * Date: 18.6.2
 * Time: 14.12
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DataService
{
    /**
     * @param $data
     * @return JsonResponse
     */
    public function processData($data)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setIgnoredAttributes(['user', 'files']);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array(new JsonEncoder()));
        $jsonContent = $serializer->serialize($data, 'json');
        return new JsonResponse(array('data' => $jsonContent));
    }
}
