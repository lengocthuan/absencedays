<?php

namespace App\Presenters;

use App\Transformers\TypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TypePresenter.
 *
 * @package namespace App\Presenters;
 */
class TypePresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'Type';
    protected $resourceKeyCollection = 'Type';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TypeTransformer();
    }
}
