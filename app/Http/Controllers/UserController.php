<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Domain\Users\Services\UserService;

class UserController extends Controller
{
    /**
     * Define the UserService variable.
     *
     * @var UserService
     */
    protected $userService;

    /**
     * UserController Constructor.
     *
     * @param $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display listing of the Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->userService->paginate($request->all());
    }

    /**
     * Store a newly created User.
     *
     * @param $request pass the api data
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'firstname',
            'lastname',
            'password',
            'dob',
            'email',
        ]);
        return $this->userService->saveData($data);
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id show the User details
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->userService->getById($id);
    }

    /**
     * Update user.
     *
     * @param $request pass the api data
     * @param integer $user used update the record
     * @param mixed   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'firstname',
            'lastname',
            'password',
            'dob',
            'email',
        ]);


        return $this->userService->updateData($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id Delete the record
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->userService->deleteById($id);
    }

    /**
     * Search the User Request.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return $this->userService->search();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }




}
