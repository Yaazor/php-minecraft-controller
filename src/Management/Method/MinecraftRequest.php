<?php

namespace Yazor\MinecraftProtocol\Management\Method;

use Symfony\Component\Serializer\Serializer;

/**
 * @template TInput
 * @template TOuput
 */
class MinecraftRequest
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
    public function payload($input): self {
        $this->input = $input;
        return $this;
    }

    public function data(): array
    {
        $data = [
            'method' => $this->method->resourceLocation->__toString(),
            'id' => 1,
        ];
        if(!empty($this->input)) $data['params'] = [$this->input];

        return $data;
    }

    public function applyResult(mixed $result, Serializer $serializer): void {
        $unserializeClass = "";
        $list = false;
        if(!empty($this->method->expectedClasses)) {
            $unserializeClass = $this->method->expectedClasses['class'] ?? "";
            $list = $this->method->expectedClasses['list'] ?? false;
        }

        if(strlen($unserializeClass) > 0) {
            if($list) {
                $finalResult = [];
                foreach($result as $item) {
                    $finalResult[] = $serializer->deserialize(json_encode($item), $unserializeClass, 'json');
                }
            }else{
                $finalResult = $serializer->deserialize(json_encode($result), $unserializeClass, 'json');
            }
        }else{
            $finalResult = $result;
        }

        $this->result = $finalResult;
    }



    /**
     * @return TOuput|null
     */
    public function result() {
        return $this->result;
    }
}