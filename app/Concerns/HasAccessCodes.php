<?php

namespace App\Concerns;

use App\Helpers\RandomCodeGenerator;
use App\Models\AccessCode;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait HasAccessCodes
{
    /**
     * Define access codes relation
     *
     * @return MorphMany
     */
    public function accessCodes(): MorphMany
    {
        return $this->morphMany(AccessCode::class, 'verifiable');
    }

    /**
     * Generate Random Code
     *
     * @param string $target
     * @return string
     */
    public function generateCode(string $target): string
    {
        $code = rand(100000, 999999);;

        $this->accessCodes()->for($target)->delete();

        $this->accessCodes()->create($this->getPayload($code, $target));

        return $code;
    }

    /**
     * Build the record payload for access codes table
     *
     * @param string $code
     * @param string $target
     * @return array
     */
    private function getPayload(string $code, string $target): array
    {
        return [
            'code' => $code,
            'target' => $target,
            'expires_at' => Carbon::now()->addMinutes(config('settings.access_codes.reset_password_expiration',180)),
            'created_at' => Carbon::now()
        ];
    }
}
