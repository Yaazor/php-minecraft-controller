<?php

namespace Yazor\MinecraftProtocol\Data\Type;

use JsonSerializable;
use Yazor\MinecraftProtocol\Data\Type\Text\Translatable;

class Message implements JsonSerializable
{

    public function __construct(public ?string $literal = null, public ?Translatable $translatable = null)
    {
    }

    public static function literal(string $literal): Message {
        return new self($literal);
    }

    public static function translatable(Translatable $translatable): self
    {
        return new self(null, $translatable);
    }

    public function __serialize(): array
    {
        $data = [];
        if ($this->literal !== null) $data['literal'] = $this->literal;
        if ($this->translatable !== null) {
            $data['translatable'] = $this->translatable->translatable;
            if(count($this->translatable->options) > 0) $data['translatableParams'] = $this->translatable->options;
        }
        return $data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->__serialize();
    }

}