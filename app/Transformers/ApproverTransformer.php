<?php

namespace App\Transformers;

use App\Models\Approver;

/**
 * Class ApproverTransformer.
 *
 * @package namespace App\Transformers;
 */
class ApproverTransformer extends BaseTransformer
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
