<?php

namespace App\Command;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Command ListMembers
 */
#[AsCommand(
    name: 'app:list-members',
    description: 'List the members',
)]
class ListMembersCommand extends Command
{
    /**
     *  @var MemberRepository data access repository
     */
    private $memberRepository;

    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->memberRepository = $doctrineManager->getRepository(Member::class);
        
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to list the members');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetches all instances of class Cupboard from the DB
        $members = $this->memberRepository->findAll();
        //dump($members);
        if (!empty($members)) {
            $io->title('List of members:');
            $io->listing($members);
        } else {
            $io->error('No members found!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
