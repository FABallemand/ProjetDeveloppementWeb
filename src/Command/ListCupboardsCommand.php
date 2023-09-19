<?php

namespace App\Command;

use App\Entity\Cupboard;
use App\Repository\CupboardRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Command ListCupboards
 */
#[AsCommand(
    name: 'app:list-cupboards',
    description: 'List the cupboards',
)]
class ListCupboardsCommand extends Command
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
        $this->cupboardRepository = $doctrineManager->getRepository(Cupboard::class);
        
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to list the cupboards')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetches all instances of class Cupboard from the DB
        $cupboard = $this->cupboardRepository->findAll();
        //dump($todos);
        if(!empty($cupboard)) {
            $io->title('List of cupboards:');
            $io->listing($cupboard);
        } else {
            $io->error('No cupboards found!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
