<?php

namespace BitBag\ShopwareAppSkeleton\Controller;

use BitBag\ShopwareAppSkeleton\Entity\Config;
use BitBag\ShopwareAppSkeleton\Entity\ShopInterface;
use BitBag\ShopwareAppSkeleton\Form\ConfigType;
use BitBag\ShopwareAppSkeleton\Repository\ConfigRepositoryInterface;
use BitBag\ShopwareAppSkeleton\Repository\ShopRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class APISettingsController
{
    private Environment $template;

    private FormFactory $form;

    private ConfigRepositoryInterface $configRepository;

    private ShopRepositoryInterface $shopRepository;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;


    public function __construct(
        Environment $template,
        FormFactory $form,
        ConfigRepositoryInterface $configRepository,
        ShopRepositoryInterface $shopRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ) {
        $this->template = $template;
        $this->form = $form;
        $this->configRepository = $configRepository;
        $this->entityManager = $entityManager;
        $this->shopRepository = $shopRepository;
        $this->translator = $translator;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
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

        $form = $this->form->create(ConfigType::class, $config);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $config->setShop($shop);
            $this->entityManager->persist($config);
            $this->entityManager->flush();

            $session->getFlashBag()->add('success', $this->translator->trans('bitbag.shopware_dhl_app.ui.saved'));
        }

        return new Response($this->template->render('settings/form.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
