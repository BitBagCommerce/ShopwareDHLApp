<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LabelNotFoundException extends NotFoundHttpException
{
}
