<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Controller;

use BitBag\ShopwareAppSystemBundle\Entity\ShopInterface;
use BitBag\ShopwareAppSystemBundle\Repository\ShopRepositoryInterface;
use BitBag\ShopwareDHLApp\Entity\Config;
use BitBag\ShopwareDHLApp\Form\ConfigType;
use BitBag\ShopwareDHLApp\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class APISettingsController extends AbstractController
{
    private ConfigRepository $configRepository;

    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    public function __construct(
        ConfigRepository $configRepository,
        ShopRepositoryInterface $shopRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ) {
        $this->configRepository = $configRepository;
        $this->entityManager = $entityManager;
        $this->shopRepository = $shopRepository;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): Response
    {
        $shopId = $request->query->get('shop-id');

        /** @var ShopInterface $shop */
        $shop = $this->shopRepository->find($shopId);

        $config = $this->configRepository->findOneBy(['shop' => $shop]);

        if (!$config) {
            $config = new Config();
        }
        $session = $request->getSession();

        $form = $this->createForm(ConfigType::class, $config);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $config->setShop($shop);
            $this->entityManager->persist($config);
            $this->entityManager->flush();

            $session->getFlashBag()->add('success', $this->translator->trans('bitbag.shopware_dhl_app.ui.saved'));
        }

        return $this->renderForm('settings/form.html.twig', [
            'form' => $form,
        ]);
    }
}
