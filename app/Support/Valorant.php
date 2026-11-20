<?php

namespace App\Support;

class Valorant
{
    /**
     * @return array<string, string>
     */
    public static function maps(): array
    {
        return [
            'Abyss' => 'Abyss',
            'Ascent' => 'Ascent',
            'Bind' => 'Bind',
            'Breeze' => 'Breeze',
            'Fracture' => 'Fracture',
            'Haven' => 'Haven',
            'Icebox' => 'Icebox',
            'Lotus' => 'Lotus',
            'Pearl' => 'Pearl',
            'Split' => 'Split',
            'Sunset' => 'Sunset',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function roles(): array
    {
        return [
            'Duelist' => 'Duelist',
            'Initiator' => 'Initiator',
            'Controller' => 'Controller',
            'Sentinel' => 'Sentinel',
            'Flex' => 'Flex',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function agents(): array
    {
        return [
            'Astra' => 'Astra',
            'Breach' => 'Breach',
            'Brimstone' => 'Brimstone',
            'Chamber' => 'Chamber',
            'Clove' => 'Clove',
            'Cypher' => 'Cypher',
            'Deadlock' => 'Deadlock',
            'Fade' => 'Fade',
            'Gekko' => 'Gekko',
            'Harbor' => 'Harbor',
            'Iso' => 'Iso',
            'Jett' => 'Jett',
            'KAY/O' => 'KAY/O',
            'Killjoy' => 'Killjoy',
            'Neon' => 'Neon',
            'Omen' => 'Omen',
            'Phoenix' => 'Phoenix',
            'Raze' => 'Raze',
            'Reyna' => 'Reyna',
            'Sage' => 'Sage',
            'Skye' => 'Skye',
            'Sova' => 'Sova',
            'Tejo' => 'Tejo',
            'Viper' => 'Viper',
            'Vyse' => 'Vyse',
            'Waylay' => 'Waylay',
            'Yoru' => 'Yoru',
        ];
    }
}
