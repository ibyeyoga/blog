<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

use function Illuminate\Log\log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Class PostListCriteria.
 *
 * @package namespace App\Criteria;
 */
class PostListCriteria implements CriteriaInterface
{
     /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $keyword = $this->request->input('keyword', '');

        if(Gate::allows('super'))
        {
            $query = $model->allWithOwnerName();
        } else {
            $user = Auth::user();
            $query = $model->owner($user->id);
        }

        if (!empty($keyword))
        {
            $query->keyword($keyword);
        }

        return $query;
    }
}
