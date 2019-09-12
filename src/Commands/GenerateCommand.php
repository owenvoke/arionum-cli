<?php

namespace pxgamer\ArionumCLI\Commands;

use Exception;
use function strlen;
use pxgamer\ArionumCLI\Wallet;
use pxgamer\ArionumCLI\BaseCommand;
use pxgamer\ArionumCLI\Output\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class GenerateCommand extends BaseCommand
{
    public function __construct(?Factory $outputFactory = null)
    {
        $this->requiresExistingWallet = false;
        $this->outputFactory = $outputFactory;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('generate')
            ->setAliases(['gen'])
            ->setDescription('Generate a new wallet file in the current directory.');

        parent::configure();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        $output->writeln('<info>Generating a new wallet</info>');

        if ($this->wallet->exists()) {
            $existsQuestion = new ConfirmationQuestion('A wallet already exists, overwrite it? (y\N) ', false);
            if (! $this->questionHelper->ask($input, $output, $existsQuestion)) {
                $output->writeln('<fg=red>Wallet file already exists. Aborting.</>');

                return;
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
                }

                $output->writeln('<comment>The passwords did not match!</comment>');
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

            return;
        }

        $walletFile = $input->getOption('wallet-path');
        $this->wallet = new Wallet($walletFile);

        $this->decryptWallet($input, $output);
        $this->wallet->decode();

        $output->writeln('');

        $output->writeln('<comment>Your address is:</comment> '.$this->wallet->getAddress());
        $output->writeln('<comment>Your public key is:</comment> '.$this->wallet->getPublicKey());
        $output->writeln('<comment>Your private key is:</comment> '.$this->wallet->getPrivateKey());
    }
}
