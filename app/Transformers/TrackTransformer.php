<?php

namespace App\Transformers;

use App\Models\Track;

/**
 * Class TrackTransformer.
 *
 * @package namespace App\Transformers;
 */
class TrackTransformer extends BaseTransformer
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

    public function customAttributes($model): array
    {
        return [
            'user' => $model->getInfoUser(),
            'registration' => $model->getRegistration(),
        ];
    }

}
