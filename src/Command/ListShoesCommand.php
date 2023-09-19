<?php

namespace App\Command;

use App\Entity\Shoe;
use App\Repository\ShoesRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

#[AsCommand(
    name: 'app:list-shoes',
    description: 'List the shoes',
)]
class ListShoesCommand extends Command
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
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to list the shoes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetches all instances of class Shoes from the DB
        $shoes = $this->shoeRepository->findAll();
        //dump($todos);
        if(!empty($shoes)) {
            $io->title('List of shoes:');
            $io->listing($shoes);
        } else {
            $io->error('No shoes found!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
