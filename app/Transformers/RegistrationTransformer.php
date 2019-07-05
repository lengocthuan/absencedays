<?php

namespace App\Transformers;

use App\Models\Registration;

/**
 * Class RegistrationTransformer.
 *
 * @package namespace App\Transformers;
 */
class RegistrationTransformer extends BaseTransformer
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
            'user' => $model->getUser,
            'type' => $model->getType,
            'time' => $model->getTimeAbsence,
            'total' => $model->getTotalTime(),
            'mailto' =>$model->getMailto(),
            'mailcc' =>$model->getMailcc(),
        ];
    }
}
