<?php

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\AppSystem\Event\EventInterface;
use BitBag\ShopwareAppSkeleton\Entity\Config;
use BitBag\ShopwareAppSkeleton\Entity\ShopInterface;
use BitBag\ShopwareAppSkeleton\Form\ConfigType;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepositoryInterface;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class APISettingsController extends AbstractController
{
    private ConfigRepositoryInterface $configRepository;

    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    public function __construct(
        ConfigRepositoryInterface $configRepository,
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
        $shopId = $request->get('shop-id');

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
