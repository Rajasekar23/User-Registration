<?php

namespace App\Domain\Users\Repository;

use App\Core\CoreValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * User Validation Class.
 */
class UserValidator extends CoreValidator
{
    /**
     * Hold the commonrules.
     *
     * @var array
     */
    public $commonRules = [
        'firstname' => 'required',
        'lastname' => ['required', 'alpha_num'],
        'email' => 'required',
        // 'password'=> 'required'
        // 'dob' => ['required', 'date_format']
    ];

    /**
     * Boot function.
     *
     * @return void
     */
    protected function boot()
    {
        $this->rules = [];
    }

    /**
     * Custom validaiton goes here.
     *
     * @param mixed $action string
     *
     * @return bool
     */
    public function passes($action = null)
    {
        $companyId = null;
        $user = Auth::user();

        $this->rules = [
            ValidatorInterface::RULE_CREATE => [
                'email' => [
                    'sometimes',
                    Rule::unique('users', 'email')->whereNull('deleted_at'),
                ],
                'password'=> 'required'
            ],
            ValidatorInterface::RULE_UPDATE => [
                'email' => [
                    'sometimes',
                    Rule::unique('users', 'email')->ignore(request('id'))->whereNull('deleted_at'),
                ]
            ],
        ];

        parent::passes($action);

        return true;
    }
}
