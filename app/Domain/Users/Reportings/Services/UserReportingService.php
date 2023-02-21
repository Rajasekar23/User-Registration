<?php

namespace App\Domain\Users\Reportings\Services;

use App\Core\CoreService;
use App\Core\Interfaces\CrudInterface;
use App\Core\OrderBySearchCriteria;
use App\Domain\Users\Reportings\Models\UserReporting;
use App\Domain\Users\Reportings\Models\UserReportingDTO;
use App\Domain\Users\Reportings\Notifications\UserReportingCreated;
use App\Domain\Users\Reportings\Repository\UserReportingRepository;
use App\Domain\Users\Reportings\Repository\UserReportingSearchCriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Leads  Severvic Class.
 */
class UserReportingService extends CoreService implements CrudInterface
{
    /**
     * Define the UserReportingRepository variable.
     *
     * @var UserReportingRepository
     */
    protected $userReportingRepository;

    /**
     * UserReportingRepository constructor.
     */
    public function __construct(UserReportingRepository $userReportingRepository)
    {
        $this->userReportingRepository = $userReportingRepository;
    }

    /**
     * Get all User Reporting.
     *
     * @return JsonResponse
     */
    public function getAll()
    {
        $result = $this->userReportingRepository->getAll();

        return parent::jsonresSuccess($result);
    }

    /**
     * Get all User Reporting  with paginate.
     *
     * @return JsonResponse
     */
    public function paginate()
    {
        $result = $this->userReportingRepository->getBasicFilterQuery()->with(['createdBy'])->paginate();

        return parent::jsonresSuccess($result);
    }

    /**
     * Get User Reporting  by id.
     *
     * @param integer $id userReporting id
     *
     * @return JsonResponse
     */
    public function getById($id)
    {
        $response = null;
        try {
            $result = $this->userReportingRepository->with(['createdBy', 'user', 'reportingTo'])->find($id);
            $response = parent::jsonresSuccess($result);
        } catch (ModelNotFoundException $mex) {
            $response = parent::jsonresError(['message' => getMsg('USER_REPORTING_DATA_NOT_FOUND_MSG')]);
        } catch (\Exception $ex) {
            $response = parent::jsonresServerError($ex);
        }

        return $response;
    }

    /**
     * Update User Reporting data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @param mixed $id
     *
     * @return JsonResponse
     */
    public function updateData($data, $id)
    {
        $response = null;
        try {
            $dto = UserReportingDTO::from($data);
            $oldreporting = UserReporting::find($id);
            DB::beginTransaction();
            $result = $this->userReportingRepository->update($dto->notNull(), $id);
            DB::commit();
            $message = getMsg('USER_MAPPING_UPDATE_MSG');
            if (($oldreporting->user_id != $result->user_id) || ($oldreporting->reporting_id != $result->reporting_id)) {
                if (Auth::user()->id != $result->user_id) {
                    if ($result->user) {
                        $user = $result->user;
                        $user->notify(new UserReportingCreated($result));
                    }
                }
            }
            $response = parent::jsonresSuccess($result, $message);
        } catch (ModelNotFoundException $mex) {
            DB::rollBack();
            $response = parent::jsonresError(['message' => getMsg('USER_REPORTING_NOT_FOUND_MSG')]);
        } catch (ValidationException $e) {
            DB::rollBack();
            $response = parent::jsonValidationError($e->errors());
        } catch (ValidatorException $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->getMessage());
        }

        return $response;
    }

    /**
     * Validate User Reporting data.
     * Store to DB if there are no errors.
     *
     * @param array $data User Reporting data
     *
     * @return JsonResponse
     */
    public function saveData($data)
    {
        $response = null;
        try {
            $dto = UserReportingDTO::from($data);
            DB::beginTransaction();
            $result = $this->userReportingRepository->createOrUpdate($dto->notNull());
            DB::commit();
            if ($result->created_by != $result->user_id) {
                if ($result->user) {
                    $user = $result->user;
                    $user->notify(new UserReportingCreated($result));
                }
            }
            $message = getMsg('USER_MAPPING_CREATE_MSG');
            $response = parent::jsonresSuccess($result, $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            $response = parent::jsonValidationError($e->errors());
        } catch (ValidatorException $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError($e);
        }

        return $response;
    }

    /**
     * Delete User Reporting by id.
     *
     * @param integer $id User Reporting id
     *
     * @return JsonResponse User Reporting details
     */
    public function deleteById($id)
    {
        $response = null;
        try {
            DB::beginTransaction();
            $leadStatus = $this->userReportingRepository->delete($id);
            DB::commit();
            $message = getMsg('USER_MAPPING_DELETE_MSG');
            $response = parent::jsonresSuccess($leadStatus, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->getMessage());
        }

        return $response;
    }

    /**
     * Search the User Reporting Request.
     *
     * @return JsonResponse
     */
    public function search()
    {
        $criteria = app(UserReportingSearchCriteria::class);
        $orderByCriteria = app(OrderBySearchCriteria::class);
        $this->userReportingRepository->pushCriteria($orderByCriteria);
        $this->userReportingRepository->pushCriteria($criteria);

        return parent::filter($this->userReportingRepository);
    }
}
