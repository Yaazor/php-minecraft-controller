<?php

namespace Yazor\MinecraftProtocol\Data\Type\Action;

use Yazor\MinecraftProtocol\Data\Type\Message;
use Yazor\MinecraftProtocol\Data\Type\MinecraftPlayer;

class KickPlayer
{
    public function __construct(public MinecraftPlayer $player, public Message $message)
    {
    }

}