<?php

namespace App\Repositories\Eloquent;

use App\Models\Image;
use App\Repositories\Contracts\UserRepository;
use App\Services\RoleService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function presenter()
    {
        return \App\Presenters\UserPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Override method create to add owners
     * @param  array  $attributes attributes from request
     * @return object
     */
    public function create(array $attributes)
    {
        $attributes['password'] = bcrypt($attributes['password']);
        $user = parent::create(array_except($attributes, 'role'));

        // find or create role admin
        if (!empty($attributes['role'])) {
            RoleService::add($user, $attributes['role']);
        }

        return $user;
    }

    /**
     * Override method create to add owners
     * @param  array  $attributes attributes from request
     * @return object
     */
    public function update(array $attributes, $id)
    {
        $oldEmail = $this->model->whereNotIn('id', [$id])->get();

        for ($i=0; $i < count($oldEmail); $i++) { 
            if ($oldEmail[$i]['email'] == $attributes['email']) {
                return User::UNIQUE_EMAIL;
            }
            if ($oldEmail[$i]['phone'] == $attributes['phone']) {
                return User::UNIQUE_PHONE;
            }
        }

        if (!empty($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }
        $user = parent::update(array_except($attributes, 'role', 'photo'), $id);

        if (!empty($attributes['role'])) {
            RoleService::sync($user, $attributes['role']);
        }

        if (!empty($attributes['photo'])) {
            if ($user->image) {
                Storage::delete($user->image->pathname);
                Storage::delete('thumbnails/' . $user->image->filename);
                $user->image->delete();
            }
            Image::where('id', $attributes['photo'])->update([
                'object_id' => $user->id,
                'object_type' => User::IMAGE_TYPE,
            ]);
        }

        $user = $user->refresh();

        return $user;
    }

    public function getMail()
    {
        $email = Auth::user()->email;
        $emailExcept = User::whereIn('id', ['1', '2', '3', '4'])->select('email')->get();
        foreach ($emailExcept as $value) {
            $arrayEmailExcept[] = $value->email;
        }
        $emailExceptForTo = array_merge($arrayEmailExcept, (array) $email);
        $mail = User::whereNotIn('email', $emailExceptForTo)->select('email')->get();
        for ($i = 0; $i < count($mail); $i++) {
            $emailSend[] = $mail[$i]->email;
        }
        $data = ['data' => $emailSend];

        return $data;
    }
}
