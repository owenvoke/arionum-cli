<?php

namespace pxgamer\ArionumCLI;

use Exception;
use pxgamer\Arionum\Arionum;
use pxgamer\ArionumCLI\Output\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    /** @var Arionum */
    protected $arionumClient;

    /** @var Factory */
    protected $outputFactory;

    /** @var QuestionHelper */
    protected $questionHelper;

    /** @var Wallet */
    protected $wallet;

    /** @var bool */
    protected $requiresExistingWallet = true;

    public function __construct(?Factory $outputFactory = null)
    {
        $this->outputFactory = $outputFactory;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'peer',
            null,
            InputOption::VALUE_REQUIRED,
            'A custom peer to use for API calls.'
        )->addOption(
            'wallet-path',
            'w',
            InputOption::VALUE_REQUIRED,
            'A path to a custom wallet file.'
        );
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int|null|void
     * @throws ArionumException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->questionHelper = $this->getHelper('question');

        $this->arionumClient = new Arionum($input->getOption('peer') ?? Api::getPeer());

        $walletFile = $input->getOption('wallet-path');
        $this->wallet = new Wallet($walletFile);

        if ($this->requiresExistingWallet && ! $this->wallet->exists()) {
            throw new ArionumException('A wallet file is required for this command.');
        }

        if ($this->wallet->exists()) {
            $this->decryptWallet($input, $output);
            $this->wallet->decode();

            $output->writeln('<info>Connected to node:</info> '.$this->arionumClient->getNodeAddress());
            $output->writeln('<info>Your address is:</info> '.$this->wallet->getAddress());
            $output->writeln(null);
        }
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @param  string|null  $message
     * @return mixed
     */
    protected function askForPassword(InputInterface $input, OutputInterface $output, string $message = null)
    {
        $passwordQuestion = new Question($message ?? 'Enter your password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        return $this->questionHelper->ask($input, $output, $passwordQuestion);
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     */
    protected function decryptWallet(InputInterface $input, OutputInterface $output): void
    {
        $isEncrypted = $this->wallet->isEncrypted();

        if ($isEncrypted) {
            $output->writeln('This wallet is encrypted.');
            do {
                $password = $this->askForPassword($input, $output);

                $this->wallet->decrypt($password);

                $isEncrypted = $this->wallet->isEncrypted();

                if (! $isEncrypted) {
                    break;
                }

                $output->writeln('<error>Invalid password!</error>');
            } while (true);
        }
    }
}
