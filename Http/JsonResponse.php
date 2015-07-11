<?php

namespace Proyecto404\UtilBundle\Http;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

/**
 * Class JsonResponse
 */
class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    protected $originalData;

    /**
     * Sets the data to be sent as json.
     *
     * @param mixed $data
     *
     * @return JsonResponse
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
     * Returns the original data to be encoded in json
     *
     * @return array
     */
    public function getOriginalData()
    {
        return $this->originalData;
    }
}
