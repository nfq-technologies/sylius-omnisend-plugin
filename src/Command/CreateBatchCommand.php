<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Command;

use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateBatchCommand extends Command
{
    public const AVAILABLE_TYPES = [
        'products',
        'categories',
    ];

    use LockableTrait;

    protected static $defaultName = 'nfq:sylius-omnisend:create-batch';

    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Pushes all resources by type to Omnisend')
            ->addArgument(
                'type',
                InputOption::VALUE_REQUIRED,
                'Import type. Available types are ' . implode(', ', self::AVAILABLE_TYPES)
            )
            ->addArgument('channelCode', InputOption::VALUE_REQUIRED, 'Channel Code')
            ->addArgument('localeCode', InputOption::VALUE_REQUIRED, 'Locale Code')
            ->addOption(
                'batchSize',
                'batchSize',
                InputOption::VALUE_OPTIONAL,
                'Batch size for Omnisend. Default is 1000'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $channelCode = (string)$input->getArgument('channelCode');
        $localeCode = (string)$input->getArgument('localeCode');
        $type = (string)$input->getArgument('type');
        $batchSize = CreateBatch::DEFAULT_BATCH_SIZE;
        if (!in_array($type, self::AVAILABLE_TYPES, true)) {
            $output->writeln('Invalid type provided');

            return 1;
        }

        if (null !== $input->getOption('batchSize')) {
            $batchSize = (int)$input->getOption('batchSize');

            if ($batchSize <= 0 || $batchSize > CreateBatch::DEFAULT_BATCH_SIZE) {
                $output->writeln('Invalid batch size provided. Size should be between 1 and 1000');

                return 1;
            }
        }

        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 1;
        }

        $this->commandBus->dispatch(
            new Envelope(
                new CreateBatch(
                    $type,
                    $channelCode,
                    $localeCode,
                    $batchSize
                )
            )
        );

        $this->release();

        return 0;
    }
}
