<?php

namespace pxgamer\Arionum\Console\Commands\Masternode;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PauseCommand
 */
class PauseCommand extends MasternodeCommand
{
    protected function configure()
    {
        $this
            ->setName('masternode:pause')
            ->setDescription('Pause the masternode.');
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

        try {
            $result = $this->sendCommand(self::COMMAND_VERSION_PAUSE);

            $output->writeln('<info>Masternode pause command sent!</info>');
            $output->writeln('<info>ID: '.$result['data'].'</info>');
        } catch (\Exception $exception) {
            $output->writeln('<error>'.$exception->getMessage().'</error>');
        }
    }
}
