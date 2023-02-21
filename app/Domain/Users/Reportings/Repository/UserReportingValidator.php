<?php

namespace App\Domain\Users\Reportings\Repository;

use App\Core\CoreValidator;

/**
 * UserReportingValidator Class.
 */
class UserReportingValidator extends CoreValidator
{
    /**
     * Hold the commonrules.
     *
     * @var array
     */
    public $commonRules = [
        'user_id' => 'required',
        'reporting_id' => 'required|different:user_id',
        // 'comments' => 'required|min:1|max:100',
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
        parent::passes($action);

        return true;
    }
}
