<?php

namespace App\Command;

use App\Entity\Shoe;
use App\Repository\ShoeRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:new-shoe',
    description: 'Create a new shoe',
)]
class NewShoeCommand extends Command
{
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
        $this->shoeRepository = $doctrineManager->getRepository(Shoe::class);
        
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a shoe')
            ->addArgument('brand', InputArgument::REQUIRED, 'The name of the shoe brand.')
            ->addArgument('model', InputArgument::REQUIRED, 'The name of the shoe model.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $shoe = new Shoe();
        $shoe->setBrand($input->getArgument('brand'));
        $shoe->setModel($input->getArgument('model'));
        
        $this->shoeRepository->save($shoe, true);
        
        if($shoe->getId()) {
            $io->text('Created: '. $shoe);
            return Command::SUCCESS;
        }
        else {
            $io->error('could not create shoe!');
            return Command::FAILURE;
        }
    }
}
