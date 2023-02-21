<?php

namespace App\Domain\Users\Reportings\Repository;

use App\Core\CoreRepository;
use App\Domain\Users\Reportings\Models\UserReporting;
use Illuminate\Validation\ValidationException;

/**
 * UserReporting Repository Class.
 */
class UserReportingRepository extends CoreRepository
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
        return UserReporting::class;
    }

    /**
     * Post validator:class.
     *
     * @return string
     */
    public function validator()
    {
        return UserReportingValidator::class;
    }

    /**
     * Get all UserReporting.
     *
     * @return UserReporting $Leads
     */
    public function getAll()
    {
        return $this->model->get();
    }

    public function createOrUpdate($data)
    {
        $userReporting = null;
        if (array_key_exists('user_id', $data) && array_key_exists('reporting_id', $data)) {
            if ($data['user_id'] == $data['reporting_id']) {
                $response = ['message' => 'The user and reporting user must be different'];
                throw ValidationException::withMessages($response);
            }
        }
        if (array_key_exists('user_id', $data)) {
            $userId = $data['user_id'];
            $userReporting = UserReporting::where('user_id', $userId)->whereNull('deleted_at')->where('status', 1)->first();
        }

        if (!$userReporting) {
            $userReporting = parent::create($data);
        } else {
            $userReporting = parent::update($data, $userReporting->id);
        }

        return $userReporting;
    }

    public function update($data, $id)
    {
        if (array_key_exists('user_id', $data) && array_key_exists('reporting_id', $data)) {
            if ($data['user_id'] == $data['reporting_id']) {
                $response = ['message' => 'The user and reporting user must be different'];
                throw ValidationException::withMessages($response);
            }
        }

        return parent::update($data, $id);
    }

    public function getReportingUserIds($user)
    {
        $userIds = [];
        if (count($user->reportings) > 0) {
            foreach ($user->reportings as $reporting) {
                $reportingUser = $reporting->user;
                $userIds[] = $reportingUser->id;
                if (count($reportingUser->reportings) > 0) {
                    $userId = $this->getReportingUserIds($reportingUser);
                    $userIds = array_merge($userIds, $userId);
                }
            }
        } else {
            $userIds[] = $user->id;
        }

        return $userIds;
    }
}
