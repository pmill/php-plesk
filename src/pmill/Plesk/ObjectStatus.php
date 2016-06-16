<?php
namespace pmill\Plesk;

abstract class ObjectStatus
{
    const ACTIVE = 0;
    const UNDER_BACKUP = 4;
    const DISABLED_BY_ADMIN = 16;
    const DISABLED_BY_RESELLER = 32;
    const DISABLED_BY_CUSTOMER = 64;
    const EXPIRED = 256;

    /**
     * @param $status
     * @return bool
     */
    public static function isValidStatus($status)
    {
        return in_array($status, [
            self::ACTIVE,
            self::UNDER_BACKUP,
            self::DISABLED_BY_ADMIN,
            self::DISABLED_BY_CUSTOMER,
            self::DISABLED_BY_RESELLER,
            self::EXPIRED,
        ]);
    }
}
