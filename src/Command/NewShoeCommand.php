<?php

namespace App\Command;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use App\Repository\ShoeRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command NewShoe
 */
#[AsCommand(
    name: 'app:new-shoe',
    description: 'Create a new shoe',
)]
class NewShoeCommand extends Command
{
    /**
     *  @var ManagerRegistry data access repository
     */
    private ManagerRegistry $doctrineManager;

    /**
     *  @var ShoeRepository data access repository
     */
    private $shoeRepository;

    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;

        $this->shoeRepository = $this->doctrineManager->getRepository(Shoe::class);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a shoe')
            ->addArgument('brand', InputArgument::REQUIRED, 'The name of the shoe brand')
            ->addArgument('model', InputArgument::REQUIRED, 'The name of the shoe model')
            ->addArgument('cupboard_name', InputArgument::REQUIRED, 'The name of the cupboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create shoe and set brand and model
        $shoe = new Shoe();
        $shoe->setBrand($input->getArgument('brand'));
        $shoe->setModel($input->getArgument('model'));
        // Load and set cupboard
        $cupboardRepository = $this->doctrineManager->getRepository(Cupboard::class);
        $cupboard = $cupboardRepository->findByName($input->getArgument('cupboard_name'));
        if (!$cupboard) {
            $io->error('could not find cupboard!');
            return Command::FAILURE;
        }
        $cupboard[0]->addShoe($shoe);

        // Save shoe
        $this->shoeRepository->save($shoe, true);

        // Check if shoe has been created
        if ($shoe->getId()) {
            $io->text('Created: ' . $shoe);
            return Command::SUCCESS;
        } else {
            $io->error('could not create shoe!');
            return Command::FAILURE;
        }
    }
}
