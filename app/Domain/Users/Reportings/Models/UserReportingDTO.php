<?php

namespace App\Domain\Users\Reportings\Models;

use App\Core\Data\Attributes\WithDefault;
use App\Core\Data\Casters\CarbonCaster;
use App\Core\Data\CustomDto;
use App\Core\Data\Defaults\CarbonDefault;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * User Reporting DTo Class.
 *
 * @param name $name comment about this variable
 */
class UserReportingDTO extends CustomDto
{
    /**
     * Id variable.
     *
     * @var integer
     */
    public ?int $id;

    /**
     * User_id variable.
     *
     * @var integer
     */
    public ?int $user_id;

    /**
     * Reporting_id variable.
     *
     * @var integer
     */
    public ?int $reporting_id;

    /**
     * Title.
     *
     * @var string
     */
    public ?string $title;

    /**
     * Comments.
     *
     * @var string
     */
    public ?string $comments;

    /**
     * Status.
     *
     * @var int
     */
    public ?int $status;

    /**
     * Company id.
     *
     * @var integer
     */
    #[MapInputName('fk_company_id')]
    public ?int $fk_company_id;

    /**
     * Created user.
     *
     * @var integer
     */
    public ?int $created_by;

    /**
     * Created datetime.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    #[WithDefault(CarbonDefault::class)]
    public ?Carbon $created_at;

    /**
     * Updated user.
     *
     * @var integer
     */
    public ?int $updated_by;

    /**
     * Updated datetime.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    #[WithDefault(CarbonDefault::class)]
    public ?Carbon $updated_at;
}
