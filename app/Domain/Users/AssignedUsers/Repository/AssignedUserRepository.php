<?php

namespace App\Domain\Users\AssignedUsers\Repository;

use App\Core\CoreRepository;
use App\Domain\Users\AssignedUsers\Models\AssignedUser;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * AssignedUserRepository Class.
 */
class AssignedUserRepository extends CoreRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
    ];

    public $fieldOrderable = [
        'title' => 'string',
    ];

    /**
     * Defind the Model Class
     * {@inheritdoc}
     *
     * @return string
     */
    public function model()
    {
        return AssignedUser::class;
    }

    /**
     * Post validator:class.
     *
     * @return string
     */
    public function validator()
    {
        return AssignedUserValidator::class;
    }

    /**
     * Get all AssignedUser.
     *
     * @return AssignedUser
     */
    public function getAll()
    {
        return $this->model->get();
    }

    public function update($input, $id)
    {
        if (array_key_exists('user_id', $input) && array_key_exists('assigned_id', $input)) {
            if ($input['user_id'] == $input['assigned_id']) {
                $response = ['message' => 'The user and assigned to user must be different'];
                throw ValidationException::withMessages($response);
            }
        }

        return parent::update($input, $id);
    }

    public function createOrUpdate($data)
    {
        $assignedUser = null;
        if (array_key_exists('user_id', $data) && array_key_exists('assigned_id', $data)) {
            if ($data['user_id'] == $data['assigned_id']) {
                $response = ['message' => 'The user and assigned to user must be different'];
                throw ValidationException::withMessages($response);
            }
        }
        if (array_key_exists('user_id', $data)) {
            $userId = $data['user_id'];
            $assignedUser = AssignedUser::where('user_id', $userId)->where('assigned_id', $data['assigned_id'])
                ->whereNull('deleted_at')
                ->whereDate('assigned_users.from_date', $data['from_date'])
                ->whereDate('assigned_users.to_date', '>=', $data['to_date'])
                ->where('status', 1)->first();
        }
        if (!$assignedUser) {
            $assignedUser = parent::create($data);
        } else {
            $assignedUser = parent::update($data, $assignedUser->id);
        }

        return $assignedUser;
    }

    public function getAssigments($data)
    {
        $userAssigenedRecords = [];
        if ($this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE)) {
            $userId = $data['user_id'];
            $fromDate = $data['from_date'];
            $toDate = $data['to_date'];
            $userAssigenedRecords = AssignedUser::with(['user', 'assignedTo'])->where('assigned_users.assigned_id', $data['assigned_id'])
                ->where('assigned_users.user_id', '!=', $userId)
                                                // ->whereDate('assigned_users.from_date', $data['from_date'])
                                                // ->whereDate('assigned_users.to_date','>=',$data['to_date'])
                ->where(function ($query) use ($fromDate, $toDate) {
                    $query->where(function ($query) use ($fromDate) {
                        $query->whereDate('assigned_users.from_date', '<=', $fromDate)
                            ->whereDate('assigned_users.to_date', '>=', $fromDate)
                        ;
                    })->orWhere(function ($query) use ($toDate) {
                        $query->whereDate('assigned_users.from_date', '<=', $toDate)
                            ->whereDate('assigned_users.to_date', '>=', $toDate)
                        ;
                    })->orWhere(function ($query) use ($fromDate, $toDate) {
                        $query->whereDate('assigned_users.from_date', '>=', $fromDate)
                            ->whereDate('assigned_users.to_date', '<=', $toDate)
                        ;
                    });
                })
                ->whereNull('assigned_users.deleted_at')
                ->where('assigned_users.status', 1)->limit(5)
                ->get()
            ;
        }

        return $userAssigenedRecords;
    }

    public function getAssignedUserIds($user)
    {
        return AssignedUser::where('assigned_id', $user->id)->where('status', 1)
            ->whereDate('from_date', '<=', Carbon::now())
            ->whereDate('to_date', '>=', Carbon::now())
            ->pluck('user_id')->toArray();
    }
}
