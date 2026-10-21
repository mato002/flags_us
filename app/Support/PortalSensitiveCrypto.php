<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

final class PortalSensitiveCrypto
{
    private const PREFIX = 'ENC:v1:';

    public static function encrypt(?string $plain): ?string
    {
        if ($plain === null || $plain === '') {
            return $plain;
        }

        if (str_starts_with($plain, self::PREFIX)) {
            return $plain;
        }

        return self::PREFIX.Crypt::encryptString($plain);
    }

    /**
     * @internal Only use in trusted admin contexts when the value must be echoed.
     */
    public static function decrypt(?string $stored): ?string
    {
        if ($stored === null || $stored === '') {
            return $stored;
        }

        if (! str_starts_with($stored, self::PREFIX)) {
            return $stored;
        }

        try {
            return Crypt::decryptString(substr($stored, strlen(self::PREFIX)));
        } catch (DecryptException) {
            return null;
        }
    }

    public static function isEncrypted(?string $stored): bool
    {
        return is_string($stored) && str_starts_with($stored, self::PREFIX);
    }
}
