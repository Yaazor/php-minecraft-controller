<?php

namespace Yazor\MinecraftProtocol\Management;

use WebSocket\Client;
use Yazor\MinecraftProtocol\Management\Method\MinecraftRequest;
use Yazor\MinecraftProtocol\Management\Method\ServerMethod;

class MinecraftClient
{

    private function __construct(private Client $client)
    {
    }

    public static function create(Client $websocketClient, string $token): self {
        ServerMethod::initiate();
        $websocketClient->addHeader("Authorization", $token);
        return new self($websocketClient);
    }

    public function send(MinecraftRequest $request): MinecraftRequest {
        $this->client->text(json_encode($request));
        if($request->method->outputClassName != null) {
            $results = json_decode($this->client->receive());
            if(empty($results['result'])) return $request;

            $request->applyResult($results['result']);
        }

        return $request;
    }
}