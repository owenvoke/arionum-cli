<?php

namespace pxgamer\ArionumCLI\Commands;

use pxgamer\Arionum\ApiException;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlockCommand
 */
final class BlockCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('block')
            ->setDescription('Display data about the current block.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $result = (array)$this->arionumClient->getCurrentBlock();

            $output->writeln('<info>Latest Block</info>');
            $output->writeln('');

            foreach ($result as $key => $value) {
                $output->writeln('<comment>'.$key.':</comment> '.$value);
            }
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
