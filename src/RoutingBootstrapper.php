<?php

namespace Uvodo\Cardlink;

use Framework\Routing\Route;
use Modules\Plugin\Domain\Context;
use Modules\Plugin\Infrastructure\Helpers\RoutingHelper;
use Presentation\Shared\RouteClass;
use Uvodo\Cardlink\Presentation\RequestHandlers\ReadKeysRequestHandler;
use Uvodo\Cardlink\Presentation\RequestHandlers\UpdateKeysRequestHandler;

class RoutingBootstrapper
{
    private RoutingHelper $helper;

    public function __construct(RoutingHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Context $context
     * @return void
     */
    public function boot(Context $context): void
    {
        $this->helper->addRoute(
            $context,
            RouteClass::PLUGINS_ADMIN_API(),
            new Route('GET', '/settings', ReadKeysRequestHandler::class)
        );

        $this->helper->addRoute(
            $context,
            RouteClass::PLUGINS_ADMIN_API(),
            new Route('POST', '/settings', UpdateKeysRequestHandler::class)
        );
    }
}
