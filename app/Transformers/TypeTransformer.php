<?php

namespace App\Transformers;

use App\Models\Type;

/**
 * Class TypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class TypeTransformer extends BaseTransformer
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
