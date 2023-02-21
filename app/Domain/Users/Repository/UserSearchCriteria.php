<?php

namespace App\Domain\Users\Repository;

use App\Core\CoreQueryBuilder;
use App\Domain\Roles\Enums\RoleTypeEnum;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class UserSearchCriteria implements CriteriaInterface
{
    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Generic Criteria.
     *
     * @param CoreQueryBuilder|CoreModel $model      -
     * @param RepositoryInterface        $repository -
     *
     * @return CoreQueryBuilder
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model
            ->with(['languageClient'])
        ;

        $user = Auth::user();
        if ($user) {
            $role = $user->role;
            if ($role) {
                if ($user->is_internalUser == 0) {
                    $model = $model->where('fk_company_id', $user->fk_company_id);
                }
                if ($role->type == RoleTypeEnum::MANAGER) {
                    $model = $model->whereHas('role', function ($query) {
                        $query->where('type', '!=', RoleTypeEnum::ADMIN);
                    });
                } elseif ($role->type == RoleTypeEnum::USER) {
                    $model = $model->whereHas('role', function ($query) {
                        $query->where('type', RoleTypeEnum::USER);
                    });
                }
            }
        }

        return $model->with('role');
    }

    /**
     * Generic Criteria. With Internal USers work around
     * Need to test.
     *
     * @param CoreQueryBuilder|CoreModel $model      -
     * @param RepositoryInterface        $repository -
     *
     * @return CoreQueryBuilder
     */
    public function applyOld($model, RepositoryInterface $repository)
    {
        $user = Auth::user();
        if ($user) {
            $role = $user->role;
            if ($role) {
                if ($user->is_internalUser == 0) {
                    $model = $model->where('fk_company_id', $user->fk_company_id);
                }
                // Commented Internal user concepts
                if ($role->type == RoleTypeEnum::ADMIN && $user->is_internalUser == 0) {
                    $model = $model->whereHas('role', function ($query) {
                        $query->where('type', '!=', RoleTypeEnum::ADMIN);
                        $query->orwhere(
                            function ($query) {
                                return $query
                                    ->where('is_internalUser', 0)
                                    ->where('type', '=', RoleTypeEnum::ADMIN)
                                ;
                            });
                    });
                } elseif ($role->type == RoleTypeEnum::MANAGER) {
                    $model = $model->whereHas('role', function ($query) use ($user) {
                        $query->where('type', '!=', RoleTypeEnum::ADMIN);
                        if ($user->is_internalUser == 0) {
                            $query->orwhere(
                                function ($query) {
                                    return $query
                                        ->where('is_internalUser', 0)
                                        ->where('type', '=', RoleTypeEnum::MANAGER)
                                    ;
                                });
                        }
                    });
                } elseif ($role->type == RoleTypeEnum::USER) {
                    $model = $model->whereHas('role', function ($query) use ($user) {
                        $query->where('type', RoleTypeEnum::USER);
                        if ($user->is_internalUser == 0) {
                            $query->where('is_internalUser', 0);
                        }
                    });
                }
            }
        }
        $model = $model->with('role');
        $orderby = $this->request->sortdata ?? null;
        if (empty($orderby)) {
            return $model; // ->orderBy('id');
        }

        return $model->orderBy($orderby['active'], $orderby['direction'])
        ;
    }
}
