<?php

namespace App\Domain\Users\AssignedUsers\Models;

use App\Core\Data\Attributes\WithDefault;
use App\Core\Data\Casters\CarbonCaster;
use App\Core\Data\CustomDto;
use App\Core\Data\Defaults\CarbonDefault;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * AssignedUserDTO DTo Class.
 *
 * @param name $name comment about this variable
 */
class AssignedUserDTO extends CustomDto
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
     * Assigned_id variable.
     *
     * @var integer
     */
    public ?int $assigned_id;

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
     * From_date variable.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    public ?Carbon $from_date;

    /**
     * To_date variable.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    public ?Carbon $to_date;

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
