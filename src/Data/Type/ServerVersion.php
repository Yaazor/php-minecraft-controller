<?php

namespace Yazor\MinecraftProtocol\Data\Type;

class ServerVersion
{
    /**
     * @var int Protocol number (e.g. <code>773</code>)
     */
    public int $protocol;
    /**
     * @var string Version name (e.g. <code>1.21.10</code>)
     */
    public string $name;
}