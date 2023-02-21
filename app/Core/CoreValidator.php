<?php

namespace App\Core;

use Illuminate\Contracts\Validation\Factory;
use Prettus\Validator\LaravelValidator;

/**
 * Services.
 */
class CoreValidator extends LaravelValidator
{
    /**
     * Hold al the common  rules array.
     *
     * @var array
     */
    public $commonRules = [];

    /**
     * Construct.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator -
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
        $this->boot();
    }

    /**
     * Used to boot any other values.
     *
     * @return void
     */
    protected function boot()
    {
        // for childern implementation
    }

    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE.
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param string|null $action -
     *
     * @return array
     */
    public function getRules($action = null)
    {
        $rules = array_merge($this->commonRules, $this->rules);

        if (isset($this->rules[$action])) {
            $rules = array_merge_recursive($this->commonRules, $this->rules[$action]);
        }

        return $this->parserValidationRules($rules, $this->id);
    }

    /**
     * Pass the data and the rules to the validator.
     *
     * @param string $action -
     *
     * @return bool
     */
    public function passes($action = null)
    {
        $rules = $this->getRules($action);
        $messages = $this->getMessages();
        $attributes = $this->getAttributes();
        $validator = $this->validator->make($this->data, $rules, $messages, $attributes);

        $validator->validate();

        return true;
    }

    /**
     * Delete Validation.
     *
     * @param mixed $id
     *
     * @return boolean
     */
    public function validateDelete($id): bool
    {
        return true;
    }
}
