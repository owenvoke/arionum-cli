<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Console\BaseCommand;
use pxgamer\Arionum\Miner;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MinerCommand
 */
class MinerCommand extends BaseCommand
{
    /**
     * @var bool
     */
    protected $requiresExistingWallet = false;

    protected function configure()
    {
        $this
            ->setName('miner')
            ->setAliases(['mine'])
            ->setDescription('Start mining for a specific wallet.')
            ->addArgument(
                'mode',
                InputArgument::REQUIRED,
                'The mode to start mining in (`solo` or `pool`)'
            )
            ->addOption(
                'node',
                'x',
                InputOption::VALUE_REQUIRED,
                'The node to use when mining (defaults to AroPool).'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $mode = $input->getArgument('mode') ?? Miner::MODE_SOLO;
        $publicKey = $this->wallet->getPublicKey();
        $privateKey = $this->wallet->getPrivateKey();
        $node = $input->getOption('node') ?? Miner::DEFAULT_NODE;
        $worker = uniqid('arionum_');

        $output->writeln('Mining on: '.$node);
        $output->writeln('With worker: '.$worker);
        $output->writeln('');

        $miner = new Miner($publicKey, $privateKey, $node, $mode, $worker);

        $miner->allTime = microtime(true);
        $miner->beginTime = time();

        $stats = ['iterator' => 0, 'start' => microtime(true)];

        $hashSpeed = new ProgressBar($output);
        $hashSpeed->setFormat('Current Hash Rate: %message% H/s');
        $hashSpeed->start();
        $output->writeln('');

        $averageSpeed = new ProgressBar($output);
        $averageSpeed->setFormat('Average Hash Rate: %message% H/s');
        $averageSpeed->start();
        $output->writeln('');

        $totalHashes = new ProgressBar($output);
        $totalHashes->setFormat('Total Hashes: %message%');
        $totalHashes->start();
        $output->writeln('');

        $miningTime = new ProgressBar($output);
        $miningTime->setFormat('Mining Time: %message%');
        $miningTime->start();
        $output->writeln('');

        $shares = new ProgressBar($output);
        $shares->setFormat('Shares: %message%');
        $shares->start();
        $output->writeln('');

        $finds = new ProgressBar($output);
        $finds->setFormat('Finds: %message%');
        $finds->start();
        $output->writeln('');

        while (true) {
            if (time() - $miner->getLastUpdate() > 2) {
                $output->write("\033[1A");
                $finds->setMessage($miner->getFound());
                $finds->advance();

                $output->write("\033[1A");
                $shares->setMessage($miner->getConfirmations());
                $shares->advance();

                $output->write("\033[1A");
                $miningTime->setMessage(time() - $miner->getBeginTime());
                $miningTime->advance();

                $output->write("\033[1A");
                $totalHashes->setMessage($miner->getCounter() ?? 0);
                $totalHashes->advance();

                $output->write("\033[1A");
                $averageSpeed->setMessage($miner->getAverageSpeed() ?? 0);
                $averageSpeed->advance();

                $output->write("\033[1A");
                $hashSpeed->setMessage($miner->getSpeed() ?? 0);
                $hashSpeed->advance();

                print PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;

                $miner->update();
            }

            $stats = $miner->run($stats);
        }
    }
}
