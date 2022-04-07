<?php

declare(strict_types=1);

namespace BitBag\ShopwareDHLApp\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LabelNotFoundException extends NotFoundHttpException
{
}
