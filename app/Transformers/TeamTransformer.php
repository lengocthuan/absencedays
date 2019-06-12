<?php

namespace App\Transformers;

use App\Models\Team;

/**
 * Class TeamTransformer.
 *
 * @package namespace App\Transformers;
 */
class TeamTransformer extends BaseTransformer
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
