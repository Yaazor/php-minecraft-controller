<?php

namespace Yazor\MinecraftProtocol\Data\Type;

readonly class MinecraftPlayer
{
    public function __construct(private(set) string $uuid, private(set) ?string $name)
    {
    }

    public static function fromUsername(string $username): MinecraftPlayer {
        return new MinecraftPlayer(null, $username);
    }

    public static function fromUuid(string $uuid): MinecraftPlayer {
        return new MinecraftPlayer($uuid, null);
    }
}