<?php

namespace Yazor\MinecraftProtocol\Data;

use Yazor\MinecraftProtocol\Management\ProtocolPath;

/**
 * <p>Represents a Minecraft resource location.</p>
 * <p>Equivalent to Kyori's <code>Key</code> class.</p>
 */
class ResourceLocation
{
    private const string DEFAULT_NAMESPACE = "minecraft";

    private(set) string $namespace;
    private(set) string $value;

    /**
     * Constructs a ResourceLocation based on an optional namespace and path.
     * @param string|null $namespace Optional namespace (defaults to <code>minecraft</code>)
     * @param string $value
     */
    public function __construct(?string $namespace, string $value) {
        $this->namespace = self::DEFAULT_NAMESPACE;
        if(!empty($namespace)) {
            $this->namespace = $namespace;
        }

        $this->value = $value;
    }

    public function withParam(ProtocolPath|string $param): ResourceLocation {
        $append = $param;
        if($param instanceof ProtocolPath) $append = $param->value;
        return self::create($this->namespace, $this->value.$append);
    }

    public static function create(string $namespace, string $value): self {
        return new self($namespace, $value);
    }

    /**
     * Returns a resource location based on a separated string.
     * @param string $value
     * @return self
     */
    public static function read(string $value): self {
        $split = explode(":", $value);
        $namespace = self::DEFAULT_NAMESPACE;

        if(count($split) == 2) {
            $namespace = $split[0];
            $value = $split[1];
        }else{
            $value = $split[0];
        }

        return self::create($namespace, $value);
    }

    public function __toString(): string
    {
        return "{$this->namespace}:{$this->value}";
    }

}