<?php

namespace Samek\Validator\Helpers;

/**
 * Class Config
 *
 * TODO: put in library.
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Uploader\Helpers
 */
class Config
{
    /**
     * Read setting from environment.
     *
     * @param string $name
     * @return string
     * @throws \Exception When environment setting does not exist
     */
    public static function getSettingValue($name)
    {
        $settingValue = getenv($name);

        if ($name == null)
            throw new \Exception("Fatal error: Environment setting '$name' is missing!");

        return $settingValue;
    }
}
