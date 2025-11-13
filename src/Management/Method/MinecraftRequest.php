<?php

namespace Yazor\MinecraftProtocol\Management\Method;

/**
 * @template TInput
 * @template TOuput
 */
class MinecraftRequest implements \JsonSerializable
{
    /**
     * @var TInput
     */
    private $input;

    /**
     * @var TOuput|null
     */
    private $result = null;

    public function __construct(public readonly ServerMethod $method)
    {
    }

    /**
     * @param TInput $input
     * @return $this
     */
    public function input($input): self {
        $this->input = $input;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $data = [
            'method' => $this->method->resourceLocation->__toString(),
            'id' => 1,
        ];
        if(!empty($this->input)) $data['params'] = $this->input;

        return $data;
    }

    public function applyResult(mixed $result): void {
        $unserializeClass = $this->method->outputClassName;

        if($this->method->receives_array) {
            $finalResult = [];
            foreach($result as $item) {
                $finalResult[] = $this->unserializeItem($item, $unserializeClass);
            }
        }else{
            $finalResult = $this->unserializeItem($result, $unserializeClass);
        }

        $this->result = $finalResult;
    }

    /**
     * @return TOuput|null
     */
    public function result() {
        return $this->result;
    }

    private function unserializeItem(mixed $object, string $className): object {
        return unserialize(
            $object,
            [$className]
        );
    }
}