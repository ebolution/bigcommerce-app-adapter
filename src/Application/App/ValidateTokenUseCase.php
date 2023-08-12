<?php

namespace Ebolution\BigcommerceAppAdapter\Application\App;

use Ebolution\BigcommerceAppAdapter\Application\Contracts\ConfigurationInterface;
use Ebolution\BigcommerceAppAdapter\Application\Contracts\PersistenceInterface;
use Ebolution\BigcommerceAppAdapter\Application\Exceptions\InvalidJWTToken;
use Ebolution\BigcommerceAppAdapter\Application\Helpers\JWTHelper;
use Ebolution\BigcommerceAppAdapter\Domain\Contracts\BCAuthorizedUserRepositoryContract;

class ValidateTokenUseCase
{
    public function __construct(
        private readonly BCAuthorizedUserRepositoryContract $repository,
        private readonly ConfigurationInterface $configuration,
        private readonly PersistenceInterface $storage
    ) {}

    public function __invoke(string $token): bool
    {
        $decoder = new JWTHelper($token, $this->configuration->get("AppSecret"));
        $data = $decoder->decode();

        if ($data['exp'] < time()) {
            throw new InvalidJWTToken('Token has expired');
        }

        if ($data['iss'] !== $this->configuration->get("AppName")) {
            throw new InvalidJWTToken('Token has an invalid issuer');
        }

        if ($data['sub'] !== 'BigCommerce') {
            throw new InvalidJWTToken('Token subject is invalid');
        }

        $authUser = $this->repository->findById($data['id']);
        $this->storage->persist($authUser);

        return true;
    }
}
