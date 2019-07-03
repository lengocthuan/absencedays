<?php

namespace App\Presenters;

use App\Transformers\ApproverTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ApproverPresenter.
 *
 * @package namespace App\Presenters;
 */
class ApproverPresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'Approver';
    protected $resourceKeyCollection = 'Approver';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ApproverTransformer();
    }
}
