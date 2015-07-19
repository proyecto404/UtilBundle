<?php

namespace Proyecto404\UtilBundle\Repository;

use Proyecto404\UtilBundle\Lib\Enum;

/**
 * Enum class with ordering directions.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class OrderDirections extends Enum
{
    const ASCENDING = 'ASC';
    const DESCENDING = 'DESC';
}
