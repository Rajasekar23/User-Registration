<?php

namespace App\Domain\Users\Services;

use League\Fractal;
use App\Core\CoreService;
use Illuminate\Support\Str;
use League\Fractal\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Domain\Users\Models\UserDTO;
use App\Core\Interfaces\CrudInterface;
use Illuminate\Validation\ValidationException;
use App\Domain\Users\Repository\UserRepository;
use App\Domain\Users\Transformers\UserTransformer;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

/**
 * User  Severvic Class.
 */
class UserService extends CoreService implements CrudInterface
{
    /**
     * Define the UserRepository variable.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserRepository constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all Users.
     */
    public function getAll()
    {
        $result = $this->userRepository->getBasicFilterQuery()->all();
        $result = new Fractal\Resource\Collection($result, new UserTransformer());
        $manager = new Manager();
        $result = $manager->createData($result);

        return parent::jsonresSuccess($result);
    }

    /**
     * Get all User  with paginate.
     */
    public function paginate()
    {
        $result = $this->userRepository->getBasicFilterQuery()->paginate();
        $result = new Fractal\Resource\Collection($result, new UserTransformer());
        $manager = new Manager();
        $result = $manager->createData($result);

        return parent::jsonresSuccess($result);
    }

    /**
     * Get User  by id.
     *
     * @param integer $id User id
     */
    public function getById($id)
    {
        $response = null;
        try {
            $result = $this->userRepository->find($id);
            $result = new Fractal\Resource\Item($result, new UserTransformer());
            $manager = new Manager();
            $result = $manager->createData($result);
            $response = parent::jsonresSuccess($result);
        } catch (ModelNotFoundException $mex) {
            $response = parent::jsonresError(['message' => 'User data not found!']);
        } catch (\Exception $ex) {
            $response = parent::jsonresServerError($ex);
        }

        return $response;
    }

    /**
     * Update User data
     * Store to DB if there are no errors.
     *
     * @param array   $data
     * @param integer $user update the record
     * @param mixed   $id
     */
    public function updateData($data, $id)
    {
        $response = null;
        try {
            $dto = UserDTO::from($data);
            DB::beginTransaction();
            $result = $this->userRepository->update($dto->notNull(), $id);
            DB::commit();
            $response = parent::jsonresSuccess($result, 'User Updated Successflly');
        } catch (ModelNotFoundException $mex) {
            DB::rollBack();
            $response = parent::jsonresError(['message' => 'User data not found']);
        } catch (ValidationException $e) {
            $response = parent::jsonValidationError($e->errors());
        } catch (ValidatorException $e) {
            $response = parent::jsonresError($e->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            // dump($e->getMessage());
            $response = parent::jsonresError('Unable to update user');
        }//end try

        return $response;
    }

    /**
     * Validate User data.
     * Store to DB if there are no errors.
     *
     * @param array $data user data
     */
    public function saveData($data)
    {
        $response = null;
        try {

            DB::beginTransaction();
            if(array_key_exists('password', $data)){
                $data['password'] = Hash::make($data['password']);
            }
            $dto = UserDTO::from($data);
            $result = $this->userRepository->create($dto->notNull());
            DB::commit();
            $message = 'User created successfully';
            $response = parent::jsonresSuccess($result, $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            $response = parent::jsonValidationError($e->errors());
        } catch (ValidatorException $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError($e->getMessage());
        }//end try

        return $response;
    }

    /**
     * Delete User by id.
     *
     * @param integer $id User id
     */
    public function deleteById($id)
    {
        DB::beginTransaction();
        $response = null;
        try {
            $leadStatus = $this->userRepository->delete($id);
            DB::commit();
            $message = 'User deleted successfully';
            $response = parent::jsonresSuccess($leadStatus, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = parent::jsonresError('Unable to delete the user');
        }

        return $response;
    }



}
