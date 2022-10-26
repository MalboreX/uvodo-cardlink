<?php

namespace Uvodo\Cardlink;

use Framework\Contracts\Container\ContainerInterface;
use Modules\Option\Domain\Exceptions\OptionAlreadyExistsException;
use Modules\Option\Domain\Exceptions\OptionNotFoundException;
use Modules\Plugin\Domain\Context;
use Modules\Plugin\Domain\Hooks\ActivateHookInterface;
use Modules\Plugin\Domain\Hooks\DeactivateHookInterface;
use Modules\Plugin\Domain\Hooks\InstallHookInterface;
use Modules\Plugin\Domain\Hooks\UninstallHookInterface;
use Modules\Plugin\Domain\PluginInterface;
use Modules\Plugin\Infrastructure\Helpers\OptionHelper;
use Modules\Setting\Domain\Exceptions\PaymentGatewayNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Support\PaymentGateway\PaymentGatewayFactory;

/** @package Uvodo\Cardlink */
class CardlinkPlugin implements PluginInterface, InstallHookInterface, UninstallHookInterface, ActivateHookInterface, DeactivateHookInterface
{
    /**
     * @param ContainerInterface $container
     * @param PaymentGatewayFactory $gatewayFactory
     * @param RoutingBootstrapper $rb
     * @param OptionHelper $optionHelper
     */
    public function __construct(
        private ContainerInterface $container,
        private PaymentGatewayFactory $gatewayFactory,
        private RoutingBootstrapper $rb,
        private OptionHelper $optionHelper
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws PaymentGatewayNotFoundException
     */
    public function boot(Context $context): void
    {
        $this->container
            ->set(
                ApiKey::class,
                new ApiKey(
                    $this->optionHelper
                        ->getOptionValue(
                            $context,
                            'CARDLINK_API_KEY'
                        )
                )
                        );

        $this->gatewayFactory
            ->registerPaymentGateway(
                CardlinkPaymentGateway::SHORT_NAME,
                CardlinkPaymentGateway::class,
                $context
            );

        CardlinkContext::$context = $context;

        $this->rb->boot($context);
    }

    /**
     * @throws OptionAlreadyExistsException
     */
    public function install(Context $context): void
    {
        $this->addOptions($context);
    }

    /**
     * @throws OptionNotFoundException
     */
    public function uninstall(Context $context): void
    {
        $this->removeOptions($context);
    }

    public function activate(Context $context): void
    {
        // activate
    }

    public function deactivate(Context $context): void
    {
        // deactivate
    }

    /**
     * @throws OptionAlreadyExistsException
     */
    private function addOptions(Context $context)
    {
        $this->optionHelper->createOrUpdateOption(
            $context,
            'CARDLINK_API_KEY',
            'CARDLINK_API_KEY'
        );
    }

    /**
     * @throws OptionNotFoundException
     */
    private function removeOptions(Context $context)
    {
        $this->optionHelper->deleteOption(
            $context,
            'CARDLINK_API_KEY'
        );
    }
}
