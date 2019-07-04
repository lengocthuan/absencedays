<?php

namespace App\Presenters;

use App\Transformers\TrackTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TrackPresenter.
 *
 * @package namespace App\Presenters;
 */
class TrackPresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'Track';
    protected $resourceKeyCollection = 'Track';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TrackTransformer();
    }
}
