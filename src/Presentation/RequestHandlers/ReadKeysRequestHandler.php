<?php

namespace Uvodo\Cardlink\Presentation\RequestHandlers;

use Modules\Plugin\Infrastructure\Helpers\OptionHelper;
use Presentation\Shared\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Uvodo\Cardlink\CardlinkContext;

class ReadKeysRequestHandler
{
    public function __construct(
        private CardlinkContext $context,
        private OptionHelper $optionHelper
    ){
    }

    /**
     * @return ResponseInterface
     */
    public function __invoke(): ResponseInterface
    {
        $ctx = $this->context;
        return new JsonResponse([
            'cardlink_api_key' => $this->optionHelper->getOptionValue($ctx::$context, 'CARDLINK_API_KEY')
        ]);
    }
}
