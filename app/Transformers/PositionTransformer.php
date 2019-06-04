<?php

namespace App\Transformers;

use App\Models\Position;

/**
 * Class PositionTransformer.
 *
 * @package namespace App\Transformers;
 */
class PositionTransformer extends App\Transformers\BaseTransformer
{
    /**
     * Array attribute doesn't parse.
     */
    public $ignoreAttributes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [];
}
