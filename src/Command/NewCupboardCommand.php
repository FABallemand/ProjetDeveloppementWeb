<?php

namespace App\Command;

use App\Entity\Cupboard;
use App\Repository\CupboardRepositoryRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:new-cupboard',
    description: 'Create a new cupboard',
)]
class NewCupboardCommand extends Command
{
    /**
     *  @var CupboardRepository data access repository
     */
    private $cupboardRepository;

    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->cupboardRepository = $doctrineManager->getRepository(Todo::class);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a cupboard')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the cupboard.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cupboard = new Cupboard();
        $cupboard->setName($input->getArgument('name'));

        $this->cupboardRepository->save($cupboard, true);

        if ($cupboard->getId()) {
            $io->text('Created: ' . $cupboard);
            return Command::SUCCESS;
        } else {
            $io->error('could not create cupboard!');
            return Command::FAILURE;
        }
    }
}
