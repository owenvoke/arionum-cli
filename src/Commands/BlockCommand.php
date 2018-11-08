<?php

namespace pxgamer\ArionumCLI\Commands;

use GuzzleHttp\Exception\GuzzleException;
use pxgamer\ArionumCLI\Api;
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
     * @return int|null|void
     * @throws \Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $result = Api::getCurrentBlock();

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('<info>Latest Block</info>');
        $output->writeln('');

        foreach ($result['data'] as $key => $value) {
            $output->writeln('<comment>'.$key.':</comment> '.$value);
        }
    }
}
