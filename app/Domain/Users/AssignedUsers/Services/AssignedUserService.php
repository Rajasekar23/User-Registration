<?php

namespace App\Domain\Users\AssignedUsers\Services;

use App\Core\CoreService;
use App\Core\Interfaces\CrudInterface;
use App\Core\OrderBySearchCriteria;
use App\Domain\Users\AssignedUsers\Models\AssignedUser;
use App\Domain\Users\AssignedUsers\Models\AssignedUserDTO;
use App\Domain\Users\AssignedUsers\Notifications\UserAllocationCreated;
use App\Domain\Users\AssignedUsers\Repository\AssignedUserRepository;
use App\Domain\Users\AssignedUsers\Repository\AssignedUserSearchCriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Leads  Severvic Class.
 */
class AssignedUserService extends CoreService implements CrudInterface
{
    /**
     * Define the AssignedUserRepository variable.
     *
     * @var AssignedUserRepository
     */
    protected $assignedUserRepository;

    /**
     * AssignedUserRepository constructor.
     */
    public function __construct(AssignedUserRepository $assignedUserRepository)
    {
        $this->assignedUserRepository = $assignedUserRepository;
    }

    /**
     * Get all Assigned Users.
     *
     * @return JsonResponse
     */
    public function getAll()
    {
        $result = $this->assignedUserRepository->getAll();

        return parent::jsonresSuccess($result);
    }

    /**
     * Get all Assigned Users  with paginate.
     *
     * @return JsonResponse
     */
    public function paginate()
    {
        $result = $this->assignedUserRepository->getBasicFilterQuery()->with(['createdBy'])->paginate();

        return parent::jsonresSuccess($result);
    }

    /**
     * Get Assigned User  by id.
     *
     * @param integer $id assignedUser id
     *
     * @return JsonResponse
     */
    public function getById($id)
    {
        $response = null;
        try {
            $result = $this->assignedUserRepository->with(['createdBy', 'user', 'assignedTo'])->find($id);
            $response = parent::jsonresSuccess($result);
        } catch (ModelNotFoundException $mex) {
            $response = parent::jsonresError(['message' => 'Assigned User data not found']);
        } catch (\Exception $ex) {
            $response = parent::jsonresServerError($ex);
        }

        return $response;
    }

    /**
     * Update Assigned User data
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
            $dto = AssignedUserDTO::from($data);
            $oldreporting = AssignedUser::find($id);

            DB::beginTransaction();
            $result = $this->assignedUserRepository->update($dto->notNull(), $id);
            DB::commit();
            Cache::forget($data['assigned_id'].'.assigned_reporting_userIds');

            if (($oldreporting->user_id != $result->user_id) || ($oldreporting->assigned_id != $result->assigned_id)) {
                if (Auth::user()->id != $result->assigned_id) {
                    if ($result->assignedTo) {
                        $user = $result->assignedTo;
                        $user->notify(new UserAllocationCreated($result));
                    }
                }
            }
            $message = getMsg('USER_ALLOCATION_UPDATE_MSG');
            $response = parent::jsonresSuccess($result, $message);
        } catch (ModelNotFoundException $mex) {
            DB::rollBack();
            $response = parent::jsonresError(['message' => getMsg('USER_ASSIGNED_DATA_NOT_FOUND_MSG')]);
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
     * Validate Assigned User data.
     * Store to DB if there are no errors.
     *
     * @param array $data Assigned User data
     *
     * @return JsonResponse
     */
    public function saveData($data)
    {
        $response = null;
        try {
            $dto = AssignedUserDTO::from($data);
            DB::beginTransaction();
            $result = $this->assignedUserRepository->createOrUpdate($dto->notNull());
            DB::commit();
            Cache::forget($data['assigned_id'].'.assigned_reporting_userIds');
            if ($result->created_by != $result->assigned_id) {
                if ($result->assignedTo) {
                    $user = $result->assignedTo;
                    $user->notify(new UserAllocationCreated($result));
                }
            }
            $message = getMsg('USER_ALLOCATION_CREATE_MSG');
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
     * Delete Assigned User  by id.
     *
     * @param integer $id Assigned User id
     *
     * @return JsonResponse Assigned User details
     */
    public function deleteById($id)
    {
        $response = null;
        try {
            DB::beginTransaction();
            $leadStatus = $this->assignedUserRepository->delete($id);
            DB::commit();
            $message = getMsg('USER_ALLOCATION_DELETE_MSG');
            $response = parent::jsonresSuccess($leadStatus, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->getMessage());
        }

        return $response;
    }

    /**
     * Search the Assigned Users Request.
     *
     * @return JsonResponse
     */
    public function search()
    {
        $criteria = app(AssignedUserSearchCriteria::class);
        $orderByCriteria = app(OrderBySearchCriteria::class);
        $this->assignedUserRepository->pushCriteria($orderByCriteria);
        $this->assignedUserRepository->pushCriteria($criteria);

        return parent::filter($this->assignedUserRepository);
    }

    public function getAssigments($data)
    {
        $response = [];
        try {
            $response = $this->assignedUserRepository->getAssigments($data);
            $response = parent::jsonresSuccess($response);
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
}
