<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;
use BitBag\ShopwareAppSystemBundle\Exception\ShopNotFoundException;
use BitBag\ShopwareAppSystemBundle\Factory\Context\ContextFactoryInterface;
use BitBag\ShopwareAppSystemBundle\Repository\ShopRepositoryInterface;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Entity\ConfigInterface;
use BitBag\ShopwareDHLApp\Finder\SalesChannelFinderInterface;
use BitBag\ShopwareDHLApp\Form\ConfigType;
use BitBag\ShopwareDHLApp\Repository\ConfigRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vin\ShopwareSdk\Data\Context;
use Vin\ShopwareSdk\Data\Entity\SalesChannel\SalesChannelEntity;

final class APISettingsController extends AbstractController
{
    private ConfigRepositoryInterface $configRepository;

    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    private SalesChannelFinderInterface $salesChannelFinder;

    private ContextFactoryInterface $contextFactory;

    public function __construct(
        ConfigRepositoryInterface $configRepository,
        ShopRepositoryInterface $shopRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        SalesChannelFinderInterface $salesChannelFinder,
        ContextFactoryInterface $contextFactory
    ) {
        $this->configRepository = $configRepository;
        $this->entityManager = $entityManager;
        $this->shopRepository = $shopRepository;
        $this->translator = $translator;
        $this->salesChannelFinder = $salesChannelFinder;
        $this->contextFactory = $contextFactory;
    }

    public function __invoke(Request $request): Response
    {
        $shopId = $request->query->get('shop-id');

        /** @var ShopInterface|null $shop */
        $shop = $this->shopRepository->find($shopId);

        if (null === $shop) {
            throw new ShopNotFoundException($shopId);
        }

        $context = $this->contextFactory->create($shop);

        if (null === $context) {
            throw new UnauthorizedHttpException('');
        }

        $config = $this->configRepository->findByShopIdAndSalesChannelId($shopId, '');

        if (null === $config) {
            $config = new Config();
            $config->setPassword('');
        }

        $session = $request->getSession();

        if ($request->isMethod('POST')) {
            $data = $request->request->all('config');

            if (isset($data['salesChannelId'])) {
                $salesChannelId = $data['salesChannelId'];

                /** @var ConfigInterface $config */
                $config = $this->configRepository->findByShopIdAndSalesChannelId($shopId, $salesChannelId) ?? new Config();
            }
        }

        $form = $this->createForm(ConfigType::class, $config, [
            'salesChannels' => $this->getSalesChannelsForForm($context),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $config->setShop($shop);

            $this->entityManager->persist($config);
            $this->entityManager->flush();

            $session->getFlashBag()->add('success', $this->translator->trans('bitbag.shopware_dhl_app.ui.saved'));
        }

        return $this->renderForm('settings/form.html.twig', [
            'form' => $form,
            'config' => $config,
        ]);
    }

    private function getSalesChannelsForForm(Context $context): array
    {
        $salesChannels = $this->salesChannelFinder->findAll($context)->getEntities()->getElements();

        $items = [];

        /** @var SalesChannelEntity $salesChannel */
        foreach ($salesChannels as $salesChannel) {
            if (null !== $salesChannel->name) {
                $items[$salesChannel->name] = $salesChannel->id;
            }
        }

        return array_merge(
            [$this->translator->trans('bitbag.shopware_dhl_app.config.sales_channels') => ''],
            $items
        );
    }
}
