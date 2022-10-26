<?php

namespace Uvodo\Cardlink\Presentation\RequestHandlers;

use Modules\Option\Domain\Exceptions\OptionAlreadyExistsException;
use Modules\Plugin\Infrastructure\Helpers\OptionHelper;
use Presentation\Shared\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Services\HttpService;
use Uvodo\Cardlink\CardlinkContext;

class UpdateKeysRequestHandler
{
    public function __construct(
        private HttpService $httpService,
        private OptionHelper $optionHelper,
        private CardlinkContext $context
    ) {
    }

    /**
     * @param ServerRequestInterface $req
     * @return ResponseInterface
     * @throws OptionAlreadyExistsException
     */
    public function __invoke(ServerRequestInterface $req): ResponseInterface
    {
        $input = $this->httpService->getInput($req);
        $cardlinkApiKey = $input->get('CARDLINK_API_KEY');

        $this->optionHelper->createOrUpdateOption(
            $this->context::$context,
            'CARDLINK_API_KEY',
            $cardlinkApiKey
        );

        return new JsonResponse([
            'cardlink_api_key' => $cardlinkApiKey,
        ]);
    }
}
