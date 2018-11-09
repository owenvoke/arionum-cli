<?php

namespace pxgamer\ArionumCLI\Commands\Masternode;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PauseCommand
 */
final class PauseCommand extends MasternodeCommand
{
    protected function configure(): void
    {
        $this
            ->setName('masternode:pause')
            ->setDescription('Pause the masternode.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $result = $this->sendCommand(self::COMMAND_VERSION_PAUSE);

            $output->writeln('<info>Masternode pause command sent!</info>');
            $output->writeln('<info>ID: '.$result['data'].'</info>');
        } catch (\Exception $exception) {
            $output->writeln('<error>'.$exception->getMessage().'</error>');
        }
    }
}
