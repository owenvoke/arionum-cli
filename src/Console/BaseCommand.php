<?php

namespace pxgamer\ArionumCLI\Console;

use Exception;
use pxgamer\ArionumCLI\Api;
use pxgamer\ArionumCLI\Console\Output\Factory;
use pxgamer\ArionumCLI\Wallet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class BaseCommand
 */
abstract class BaseCommand extends Command
{
    /**
     * @var Factory
     */
    protected $outputFactory;

    /**
     * @var QuestionHelper
     */
    protected $questionHelper;

    /**
     * @var Wallet
     */
    protected $wallet;

    /**
     * @var bool
     */
    protected $requiresExistingWallet = true;

    /**
     * BaseCommand constructor.
     *
     * @param Factory|null $outputFactory
     */
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
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->questionHelper = $this->getHelper('question');

        // Set a custom peer if it's been provided
        Api::$customPeer = $input->getOption('peer');

        $this->wallet = new Wallet();

        if ($this->requiresExistingWallet && !$this->wallet->exists()) {
            throw new Exception('A wallet file is required for this command.');
        }

        if ($this->wallet->exists()) {
            $this->decryptWallet($input, $output);
            $this->wallet->decode();

            $output->writeln('<info>Your address is: '.$this->wallet->getAddress().'</info>');
            $output->writeln('');
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string|null     $message
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function decryptWallet(InputInterface $input, OutputInterface $output): void
    {
        if ($this->wallet->isEncrypted()) {
            $output->writeln('This wallet is encrypted.');
            do {
                $password = $this->askForPassword($input, $output);

                $this->wallet->decrypt($password);

                if (!$this->wallet->isEncrypted()) {
                    break;
                }

                $output->writeln('<error>Invalid password!</error>');
            } while (true);
        }
    }
}
