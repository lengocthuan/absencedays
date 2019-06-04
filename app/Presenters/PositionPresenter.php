<?php

namespace App\Presenters;

use App\Transformers\PositionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PositionPresenter.
 *
 * @package namespace App\Presenters;
 */
class PositionPresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'Position';
    protected $resourceKeyCollection = 'Position';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PositionTransformer();
    }
}
