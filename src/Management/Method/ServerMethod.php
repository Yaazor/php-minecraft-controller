<?php

namespace Yazor\MinecraftProtocol\Management\Method;

use JetBrains\PhpStorm\ArrayShape;
use Yazor\MinecraftProtocol\Data\ResourceLocation;
use Yazor\MinecraftProtocol\Data\Type\Action\KickPlayer;
use Yazor\MinecraftProtocol\Data\Type\MinecraftPlayer;
use Yazor\MinecraftProtocol\Data\Type\ServerState;
use Yazor\MinecraftProtocol\Data\Type\SystemMessage;
use Yazor\MinecraftProtocol\Management\MinecraftClient;
use Yazor\MinecraftProtocol\Management\ProtocolPath;

/**
 * <p>
 *  Holds the methods which can be sent by the
 *  {@link MinecraftClient} to the server.
 * </p>
 *
 * <p>
 *     New methods can be created by using {@link ServerMethod::create()}.
 *      Methods can also be attached to paths, e.g. <code>minecraft:allowlist/add</code>
 * </p>
 * @template TInput
 * @template TOutput
 */
class ServerMethod
{
    /**
     * <p>Allowlist-related route.</p>
     * - {@link ProtocolPath::SET} Sets the allowlist.
     * - {@link ProtocolPath::ADD} Adds specified players to the allowlist.
     * - {@link ProtocolPath::REMOVE} Removes specified players from the allowlist.
     * - {@link ProtocolPath::CLEAR} Clears the allowlist.
     * @var ServerMethod<MinecraftPlayer, ServerState>
     */
    public static ServerMethod $ALLOWLIST;

    /**
     * <p>Method which allows some degree of server management.</p>
     * - {@link ProtocolPath::STATUS} Fetches the server's {@link ServerState}.
     * - {@link ProtocolPath::SAVE} Saves the server.
     * - {@link ProtocolPath::STOP} Stops the server.
     * - {@link ProtocolPath::SYSTEM_MESSAGE} Sends a {@link SystemMessage}.
     * @var ServerMethod
     */
    public static ServerMethod $SERVER;

    /**
     * <p>Method which allows to query or kick players.</p>
     * - {@link ProtocolPath::KICK} Applies a list of {@link KickPlayer}
     * @var ServerMethod
     */
    public static ServerMethod $PLAYERS;

    private array $paths = [];


    private function __construct(public readonly ResourceLocation $resourceLocation, private(set) array $expectedClasses = [])
    {

    }

    /**
     * @param class-string<TOutput>|null $outputClassName
     * @param class-string<TInput>|null $inputClassName
     * @return self<TInput, TOutput>
     */
    public static function create(ResourceLocation $resourceLocation, array $expectedClasses = []): object {
        return new self($resourceLocation, $expectedClasses);
    }

    /**
     * @return MinecraftRequest<TInput, TOutput>
     */
    public function createRequest(): MinecraftRequest {
        return new MinecraftRequest($this);
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
    public function withPath(ProtocolPath|string $path, array $expectedClasses = []): object {
        if($path instanceof ProtocolPath) $path = $path->value;
        $location = $this->resourceLocation->withParam($path);
        $method = new self($location, $expectedClasses);
        $this->paths[$path] = $method;
        return $this;
    }

    public function getPath(ProtocolPath|string $path): ?ServerMethod {
        if($path instanceof ProtocolPath) $path = $path->value;
        return $this->paths[$path] ?? null;
    }

    public static function initiate(): void
    {
        self::$ALLOWLIST = self::create(
            ResourceLocation::read("minecraft:allowlist"), ['class' => MinecraftPlayer::class, 'list' => true]
        );

        self::$ALLOWLIST
            ->withPath(ProtocolPath::SET, ['class' => MinecraftPlayer::class, 'list' => true])
            ->withPath(ProtocolPath::ADD, ['class' => MinecraftPlayer::class, 'list' => true])
            ->withPath(ProtocolPath::REMOVE, ['class' => MinecraftPlayer::class, 'list' => true])
            ->withPath(ProtocolPath::CLEAR, ['class' => MinecraftPlayer::class, 'list' => true]);

        self::$SERVER = self::create(ResourceLocation::read("minecraft:server"))
            ->withPath(ProtocolPath::STATUS, ['class' => ServerState::class])
            ->withPath(ProtocolPath::STOP, ['class' => 'bool'])
            ->withPath(ProtocolPath::SAVE, ['class' => 'bool']);

        self::$PLAYERS = self::create(ResourceLocation::read("minecraft:players"), ['class' => MinecraftPlayer::class, 'list' => true])
            ->withPath(ProtocolPath::KICK, ['class' => MinecraftPlayer::class, 'list' => true]);

    }


}