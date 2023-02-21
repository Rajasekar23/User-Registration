<?php

namespace App\Domain\Users\Reportings\Models;

use App\Core\CoreModel;
use App\Domain\Company\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * UserReporting Model Class.
 */
class UserReporting extends CoreModel
{
    use HasFactory;
    /**
     * Hold all the fillable fields for UserReporting.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reporting_id',
        'title',
        'comments',
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

    public function reportingTo()
    {
        return $this->belongsTo(User::class, 'reporting_id');
    }
}
