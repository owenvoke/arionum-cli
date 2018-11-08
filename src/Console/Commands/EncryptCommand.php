<?php

namespace pxgamer\ArionumCLI\Console\Commands;

use pxgamer\ArionumCLI\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function strlen;

/**
 * Class EncryptCommand
 */
class EncryptCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('encrypt')
            ->setDescription('Encrypt the wallet.');

        parent::configure();
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

        do {
            $password = $this->askForPassword($input, $output);

            if (strlen($password) < 8) {
                $output->writeln('The password must be at least 8 characters long.');
                continue;
            }

            $passConfirm = $this->askForPassword($input, $output, 'Please confirm your password: ');

            if ($password === $passConfirm) {
                break;
            }

            $output->writeln('<comment>The passwords did not match!</comment>');
        } while (true);

        $walletEncrypted = $this->wallet->encrypt($password);

        $result = $this->wallet->saveRaw($walletEncrypted);

        if ($result === false || $result < 30) {
            $output->writeln($this->wallet->getPrivateKey());
            $output->writeln($this->wallet->getPrivateKey());

            $output->writeln('<error>Could not write the wallet file!</error>');
            $output->writeln(
                '<error>Please check the permissions on the current directory and save a backup of these keys.</error>'
            );
        }
    }
}
