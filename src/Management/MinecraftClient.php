<?php

namespace Yazor\MinecraftProtocol\Management;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WebSocket\Client;
use Yazor\MinecraftProtocol\Management\Method\MinecraftRequest;
use Yazor\MinecraftProtocol\Management\Method\ServerMethod;

class MinecraftClient
{
    private Serializer $serializer;

    private function __construct(private readonly Client $client)
    {
        $propertyInfo = new PropertyInfoExtractor([], [new ReflectionExtractor()]);
        $normalizers = [new ObjectNormalizer(new ClassMetadataFactory(new AttributeLoader()), null, null, $propertyInfo), new ArrayDenormalizer()];
        $this->serializer = new Serializer($normalizers, [new JsonEncoder()]);
    }

    public static function create(Client $websocketClient, string $token): self {
        ServerMethod::initiate();
        $websocketClient->addHeader("Authorization", "Bearer $token");
        return new self($websocketClient);
    }

    public function send(MinecraftRequest $request): MinecraftRequest {
        $payload = $this->serializer->serialize($request->data(), 'json');
        $this->client->text($payload);
        $text = $this->client->receive()->getContent();

        $results = json_decode($text, true);

        if(empty($results['result'])) return $request;
        $request->applyResult($results['result'], $this->serializer);


        return $request;
    }

    public function __destruct()
    {
        $this->client->close();
    }
}