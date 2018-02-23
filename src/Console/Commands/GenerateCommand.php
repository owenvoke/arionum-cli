<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Console\BaseCommand;
use pxgamer\Arionum\Wallet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class GenerateCommand extends BaseCommand
{
    protected $requiresExistingWallet = false;

    protected function configure()
    {
        $this
            ->setName('generate')
            ->setAliases(['gen'])
            ->setDescription('Generate a new wallet file in the current directory.');
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

        $output->writeln('<info>Generating a new wallet</info>');

        if ($this->wallet->exists()) {
            $existsQuestion = new ConfirmationQuestion('A wallet already exists, overwrite it? (y\N) ', false);
            if (!$this->questionHelper->ask($input, $output, $existsQuestion)) {
                throw new \Exception('Wallet exists. Aborting.');
            }
        }

        $password = null;
        $encryptQuestion = new ConfirmationQuestion('Would you like to encrypt this wallet? (y/N) ', false);

        if ($encrypt = $this->questionHelper->ask($input, $output, $encryptQuestion)) {
            do {
                $password = $this->askForPassword($input, $output);

                if (strlen($password) < 8) {
                    $output->writeln('The password must be at least 8 characters long.');
                    continue;
                }

                $passConfirm = $this->askForPassword($input, $output, 'Please confirm your password: ');

                if ($password === $passConfirm) {
                    break;
                } else {
                    $output->writeln('<comment>The passwords did not match!</comment>');
                }
            } while (true);

            $output->writeln('');
        }

        $walletRaw = $this->wallet->create();

        if ($encrypt) {
            $walletRaw = $this->wallet->encrypt($password, $walletRaw);
        }

        $result = $this->wallet->saveRaw($walletRaw);

        if ($result === false || $result < 30) {
            $output->writeln('<error>Could not write the wallet file!</error>');
            $output->writeln(
                '<error>Please check the permissions on the current directory and save a backup of these keys.</error>'
            );
        } else {
            $this->wallet = new Wallet();

            $this->decryptWallet($input, $output);
            $this->wallet->decode();

            $output->writeln('');

            $output->writeln('<comment>Your address is:</comment> '.$this->wallet->getAddress());
            $output->writeln('<comment>Your public key is:</comment> '.$this->wallet->getPublicKey());
            $output->writeln('<comment>Your private key is:</comment> '.$this->wallet->getPrivateKey());
        }
    }
}
