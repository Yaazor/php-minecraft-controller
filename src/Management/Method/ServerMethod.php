<?php

namespace Yazor\MinecraftProtocol\Management\Method;

use JetBrains\PhpStorm\ArrayShape;
use Yazor\MinecraftProtocol\Data\ResourceLocation;
use Yazor\MinecraftProtocol\Data\Type\MinecraftPlayer;
use Yazor\MinecraftProtocol\Data\Type\ServerState;

/**
 * @template TInput
 * @template TOutput
 */
class ServerMethod
{
    /**
     * @var ServerMethod<MinecraftPlayer, ServerState>
     */
    public static ServerMethod $ALLOWLIST;
    private array $paths = [];

    private function __construct(public readonly ResourceLocation $resourceLocation, private(set) ?string $inputClassName, private(set) ?string $outputClassName, private(set) bool $takes_array = false, private(set) bool $receives_array =  false)
    {

    }

    /**
     * @param class-string<TInput>|null $inputClassName
     * @param class-string<TOutput>|null $outputClassName
     * @return self<TInput, TOutput>
     */
    public static function create(ResourceLocation $resourceLocation, ?string $inputClassName, ?string $outputClassName, bool $takes_array = false, bool $receives_array = false): object {
        return new self($resourceLocation, $inputClassName, $outputClassName, $receives_array);
    }

    /**
     * @template TI
     * @template TO
     * @param string $path
     * @param class-string<TI>|null $inputClassName
     * @param class-string<TO>|null $outputClassName
     * @param bool $takes_array
     * @param bool $receives_array
     * @return self<TI, TO>
     */
    public function withPath(string $path, ?string $inputClassName, ?string $outputClassName, bool $takes_array = false, bool $receives_array = false): object {
        $location = $this->resourceLocation->withParam($path);
        $method = new self($location, $inputClassName, $outputClassName, $takes_array, $receives_array);
        $paths[$path] = $method;

        return $method;
    }

    public function getPath(string $path): ?ServerMethod {
        return $this->paths[$path] ?? null;
    }

    /**
     * @param TInput $object
     * @return self<TInput, TOutput>
     */
    public function input($object): self {

        return $this;
    }

    /**
     * @return TOutput|null
     */
    public function result() {

    }

    public static function initiate(): void
    {
        self::$ALLOWLIST = self::create(
            ResourceLocation::read("minecraft:allowlist"),
            null,
            MinecraftPlayer::class, false, true
        );

        self::$ALLOWLIST
            ->withPath("/set", MinecraftPlayer::class, MinecraftPlayer::class, true, true)
            ->withPath("/add", MinecraftPlayer::class, MinecraftPlayer::class, true, true)
            ->withPath("/remove", MinecraftPlayer::class, MinecraftPlayer::class, true, true)
            ->withPath("/clear", null, MinecraftPlayer::class, false, true);



    }

}