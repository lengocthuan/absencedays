<?php

namespace App\Presenters;

use App\Transformers\TimeAbsenceTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TimeAbsencePresenter.
 *
 * @package namespace App\Presenters;
 */
class TimeAbsencePresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'TimeAbsence';
    protected $resourceKeyCollection = 'TimeAbsence';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TimeAbsenceTransformer();
    }
}
