<?php

namespace App\Transformers;

use App\Models\Registration;

/**
 * Class RegistrationTransformer.
 *
 * @package namespace App\Transformers;
 */
class RegistrationTransformer extends App\Transformers\BaseTransformer
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
