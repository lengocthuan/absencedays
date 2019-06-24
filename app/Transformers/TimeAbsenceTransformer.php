<?php

namespace App\Transformers;

use App\Models\TimeAbsence;

/**
 * Class TimeAbsenceTransformer.
 *
 * @package namespace App\Transformers;
 */
class TimeAbsenceTransformer extends BaseTransformer
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


    // public function customAttributes($model): array
    // {
    //     return [
    //        'time' => $model->getTimeAbsence,
    //     ];
    // }
}
