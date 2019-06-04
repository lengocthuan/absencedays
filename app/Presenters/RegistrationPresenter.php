<?php

namespace App\Presenters;

use App\Transformers\RegistrationTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RegistrationPresenter.
 *
 * @package namespace App\Presenters;
 */
class RegistrationPresenter extends FractalPresenter
{
    protected $resourceKeyItem = 'Registration';
    protected $resourceKeyCollection = 'Registration';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RegistrationTransformer();
    }
}
