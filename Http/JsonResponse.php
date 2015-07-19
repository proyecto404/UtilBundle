<?php

namespace Proyecto404\UtilBundle\Http;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

/**
 * Represents a Json http response.
 *
 * Extends Symfony JsonResponse class with JMS Serializer and exposing data before converting to Json
 * to improve controller testability
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    protected $originalData;

    /**
     * {@inheritdoc}
     */
    public function setData($data = array())
    {
        $this->originalData = $data;

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = SerializerBuilder::create()->build();
        $this->data = $serializer->serialize($data, 'json', $context);

        return $this->update();
    }

    /**
     * Returns the original data to be encoded in json.
     *
     * @return array
     */
    public function getOriginalData()
    {
        return $this->originalData;
    }
}
