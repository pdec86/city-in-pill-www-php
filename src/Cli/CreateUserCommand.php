<?php

namespace App\Cli;

use App\Common\Application\CreateNewUserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-user';

    private CreateNewUserService $createNewUserService;

    public function __construct(CreateNewUserService $createNewUserService)
    {
        parent::__construct(self::$defaultName);
        $this->createNewUserService = $createNewUserService;
    }

    protected function configure()
    {
        parent::configure();
        $this
            // If you don't like using the $defaultDescription static property,
            // you can also define the short description using this method:
            ->setDescription('Command for creating user')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)

            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('You are about to create a user');

        $username = $input->getArgument("username");
        $password = $input->getArgument("password");
        $this->createNewUserService->execute($username, $password);

        $output->writeln('User created and saved into database');

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}