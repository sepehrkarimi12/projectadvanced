<?php

namespace app\modules\secretariat_v2\Services\Generators;

use app\models\Intldate;

/**
 * this class handles prefix of letters
 * @author Mehran
 */
class PrefixGenerator
{
    // this is only for testing the system, you should separate methods based on office_prefix_format tables
    public static function getPrefix()
    {
        $current_year = Intldate::get()->timestampToPersian(time());
        $current_year = explode('/', $current_year);
        return substr($current_year[0], 2);
    }
}