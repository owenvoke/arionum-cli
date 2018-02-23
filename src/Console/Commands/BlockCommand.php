<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlockCommand
 */
class BlockCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('block')
            ->setDescription('Display data about the current block.');
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

        $result = Api::getCurrentBlock();

        if ($result['status'] != 'ok') {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
        } else {
            $output->writeln('<info>Latest Block</info>');
            $output->writeln('');

            foreach ($result['data'] as $key => $value) {
                $output->writeln('<comment>'.$key.':</comment> '.$value);
            }
        }
    }
}
