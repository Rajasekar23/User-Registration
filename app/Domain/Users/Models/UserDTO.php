<?php

namespace App\Domain\Users\Models;

use App\Core\Data\Attributes\WithDefault;
use App\Core\Data\Casters\CarbonCaster;
use App\Core\Data\CustomDto;
use App\Core\Data\Defaults\CarbonDefault;
use App\Core\Data\Defaults\StringDefault;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * Role DTo Class.
 *
 * @param name $name comment about this variable
 */
class UserDTO extends CustomDto
{
    /**
     * Id variable.
     *
     * @var integer
     */
    public ?int $id;

    /**
     * firstname variable.
     *
     * @var string
     */
    #[WithDefault(StringDefault::class)]
    public ?string $firstname;

    /**
     * Title variable.
     *
     * @var string
     */
    #[WithDefault(StringDefault::class)]
    public ?string $lastname;


    /**
     * Email.
     *
     * @var string
     */
    public ?string $email;

    /**
     * DOB.
     *
     */
    public ?string $dob;

    /**
     * Password.
     *
     * @var string
     */
    public ?string $password;


    /**
     *  Created user.
     *
     * @var integer
     */
    public ?int $created_by;

    /**
     *  Created datetime.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    #[WithDefault(CarbonDefault::class)]
    public ?Carbon $created_at;

    /**
     *  updated user.
     *
     * @var integer
     */
    public ?int $updated_by;

    /**
     *  updated datetime.
     *
     * @var Carbon
     */
    #[WithCast(CarbonCaster::class)]
    #[WithDefault(CarbonDefault::class)]
    public ?Carbon $updated_at;


}
