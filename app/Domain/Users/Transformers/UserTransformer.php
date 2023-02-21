<?php

namespace App\Domain\Users\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;


class UserTransformer extends TransformerAbstract
{
    public function transform($user)
    {
        $data =  $user->toArray();
        $formatData = [
            'dob' => !empty($user->dob) ? (Carbon::parse($user->dob)->Format('d M y')) : null,
            'created_at' => !empty($user->created_at) ? (Carbon::parse($user->created_at)->Format('d-M-Y H:i:s')) : null,
            'updated_at' => !empty($user->created_at) ? (Carbon::parse($user->created_at)->Format('d-M-Y H:i:s')) : null,
        ];
        return array_merge($data, $formatData);
    }
}
