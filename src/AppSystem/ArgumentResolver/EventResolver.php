<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\ArgumentResolver;

use BitBag\ShopwareAppSkeleton\AppSystem\Authenticator\AuthenticatorInterface;
use BitBag\ShopwareAppSkeleton\AppSystem\Event\Event;
use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class EventResolver implements ArgumentValueResolverInterface
{
    private ShopRepositoryInterface $shopRepository;

    private AuthenticatorInterface $authenticator;

    public function __construct(
        ShopRepositoryInterface $shopRepository,
        AuthenticatorInterface $authenticator
    ) {
        $this->shopRepository = $shopRepository;
        $this->authenticator = $authenticator;
    }

    /* @psalm-suppress PossiblyUndefinedArrayOffset */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (EventInterface::class !== $argument->getType()) {
            return false;
        }

        if ('POST' !== $request->getMethod()) {
            return false;
        }

        $requestContent = $request->toArray();

        $hasSource = array_key_exists('source', $requestContent);
        $hasData = array_key_exists('data', $requestContent);
        $hasSourceAndData = $hasSource && $hasData;

        if (!$hasSourceAndData) {
            return false;
        }

        $requiredKeys = ['url', 'appVersion', 'shopId'];
        $requestSource = $requestContent['source'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $requestSource)) {
                return false;
            }
        }

        $shopSecret = $this->shopRepository->findSecretByShopId($requestContent['source']['shopId']);

        if (null === $shopSecret) {
            return false;
        }

        return $this->authenticator->authenticatePostRequest($request, $shopSecret);
    }

    /**
     * @return \Generator
     *
     * @psalm-return \Generator<int, Event, mixed, void>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        /** @var array{
         *     source: array{
         *          url: string,
         *          appVersion: string,
         *          shopId: string
         *      },
         *     data: array
         * } $requestContent
         */
        $requestContent = $request->toArray();

        $shopUrl = $requestContent['source']['url'];
        $shopId = $requestContent['source']['shopId'];
        $appVersion = (int) $requestContent['source']['appVersion'];
        $eventData = $requestContent['data'];

        yield new Event($shopUrl, $shopId, $appVersion, $eventData);
    }
}
