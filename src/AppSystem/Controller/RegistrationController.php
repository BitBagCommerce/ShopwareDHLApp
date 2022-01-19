<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\AppSystem\Controller;

use BitBag\ShopwareAppSkeleton\AppSystem\Authenticator\AuthenticatorInterface;
use BitBag\ShopwareAppSkeleton\Entity\Shop;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    private AuthenticatorInterface $authenticator;

    private EntityManagerInterface $entityManager;

    private ShopRepositoryInterface $shopRepository;

    public function __construct(
        AuthenticatorInterface $authenticator,
        EntityManagerInterface $entityManager,
        ShopRepositoryInterface $shopRepository
    ) {
        $this->authenticator = $authenticator;
        $this->entityManager = $entityManager;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @Route("/registration", name="register", methods={"GET"})
     */
    public function __invoke(Request $request, string $appName, string $appSecret): Response
    {
        if (!$this->authenticator->authenticateRegisterRequest($request)) {
            return new Response(null, 401);
        }

        $shopUrl = $this->getShopUrl($request);
        $shopId = $this->getShopId($request);

        if (null === $shopUrl || null === $shopId) {
            throw new BadRequestHttpException('Missing query parameters.');
        }

        $shop = $this->shopRepository->find($shopId);

        if (null !== $shop) {
            throw new BadRequestHttpException('Shop already exists.');
        }

        $secret = \bin2hex(\random_bytes(64));

        $shop = new Shop();

        $shop->setShopId($shopId);
        $shop->setShopUrl($shopUrl);
        $shop->setShopSecret($secret);

        $this->entityManager->persist($shop);
        $this->entityManager->flush();

        $proof = \hash_hmac('sha256', $shopId.$shopUrl.$appName, $appSecret);
        $body = ['proof' => $proof, 'secret' => $secret, 'confirmation_url' => $this->generateUrl('confirm', [], UrlGeneratorInterface::ABSOLUTE_URL)];

        return $this->json($body);
    }

    private function getShopUrl(Request $request): ?string
    {
        return $request->query->get('shop-url');
    }

    private function getShopId(Request $request): ?string
    {
        return $request->query->get('shop-id');
    }
}