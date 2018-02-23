<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecryptCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('decrypt')
            ->setDescription('Decrypt the wallet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $walletRaw = 'arionum:'.$this->wallet->getPrivateKey().':'.$this->wallet->getPublicKey();

        $result = $this->wallet->saveRaw($walletRaw);

        if ($result === false || $result < 30) {
            $output->writeln($this->wallet->getPrivateKey());
            $output->writeln($this->wallet->getPrivateKey());

            $output->writeln('<error>Could not write the wallet file!</error>');
            $output->writeln(
                '<error>Please check the permissions on the current directory and save a backup of these keys.</error>'
            );
        }

        $output->writeln('The wallet has been decrypted!');
    }
}
