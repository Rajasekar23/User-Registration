<?php

namespace App\Domain\Users\AssignedUsers\Models;

use App\Core\CoreModel;
use App\Domain\Company\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

/**
 * AssignedUser Model Class.
 */
class AssignedUser extends CoreModel
{
    use HasFactory;
    /**
     * Hold all the fillable fields for AssignedUser.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'assigned_id',
        'title',
        'comments',
        'from_date',
        'to_date',
        'status',
        'fk_company_id',
        'created_by',
        'created_at',
        'updated_by',
    ];

    /**
     * Company Relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'fk_company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

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
        $data['old_values']['assigned_name'] = null;
        $data['new_values']['assigned_name'] = null;

        if (Arr::has($data, 'old_values.user_id') && isset($data['old_values']['user_id'])) {
            $data['old_values']['user_name'] = User::find($this->getOriginal('user_id'))->name;
        }
        if (Arr::has($data, 'new_values.user_id')) {
            $data['new_values']['user_name'] = User::find($this->getAttribute('user_id'))->name;
        }

        if (Arr::has($data, 'old_values.assigned_id') && isset($data['old_values']['assigned_id'])) {
            $data['old_values']['assigned_name'] = User::find($this->getOriginal('assigned_id'))->name;
        }
        if (Arr::has($data, 'new_values.assigned_id')) {
            $data['new_values']['assigned_name'] = User::find($this->getAttribute('assigned_id'))->name;
        }
        return $data;
    }
}
