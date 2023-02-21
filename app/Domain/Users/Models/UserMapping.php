<?php

namespace App\Domain\Users\Models;

use App\Core\CoreModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class UserMapping extends CoreModel
{
    use HasFactory;

    protected $fillable = [
        'innomaint_user_id',
        'innomaint_company_id',
        'internal_user_id',
        'internal_company_id',
        'created_by',
        'created_at',
        'updated_by',
    ];

    /**
     * Transforming the Auditing content.
     *
     * @param array $data -
     * 
     * @return array
     */
    public function transformAudit(array $data): array
    {
        $data['old_values']['user_name'] = null;
        $data['new_values']['user_name'] = null;
        $data['old_values']['reporting_name'] = null;
        $data['new_values']['reporting_name'] = null;

        if (Arr::has($data, 'old_values.user_id') && isset($data['old_values']['user_id'])) {
            $data['old_values']['user_name'] = User::find($this->getOriginal('user_id'))->name;
        }
        if (Arr::has($data, 'new_values.user_id')) {
            $data['new_values']['user_name'] = User::find($this->getAttribute('user_id'))->name;
        }

        if (Arr::has($data, 'old_values.reporting_id') && isset($data['old_values']['reporting_id'])) {
            $data['old_values']['reporting_name'] = User::find($this->getOriginal('reporting_id'))->name;
        }
        if (Arr::has($data, 'new_values.reporting_id')) {
            $data['new_values']['reporting_name'] = User::find($this->getAttribute('reporting_id'))->name;
        }
        return $data;
    }
}
