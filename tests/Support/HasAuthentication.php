<?php

declare(strict_types=1);

namespace App\Tests\Support;

use RuntimeException;

trait HasAuthentication
{
    protected function authenticate(array $credentials): array
    {
        if (!method_exists($this, 'createHttpClient')) {
            throw new RuntimeException('Cannot use HasAuthentication out of App\Tests\TestCase.');
        }

        $this->client->request('POST', '/api/login/check', [], [], [], json_encode($credentials));
        $responseBody = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $responseBody['token']));

        return $responseBody;
    }
}
