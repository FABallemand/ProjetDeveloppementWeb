<?php

namespace App\Command;

use App\Entity\Member;
use App\Entity\MemberRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command NewMember
 */
#[AsCommand(
    name: 'app:new-member',
    description: 'Create a new member',
)]
class NewMemberCommand extends Command
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
            ->setHelp('This command allows you to create a member')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the member')
            ->addArgument('age', InputArgument::OPTIONAL, 'The age of the member');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $member = new Member();
        $member->setName($input->getArgument('name'));
        if ($input->getArgument('age')) {
            $member->setAge($input->getArgument('age'));
        }

        $this->memberRepository->save($member, true);

        if ($member->getId()) {
            $io->text('Created: ' . $member);
            return Command::SUCCESS;
        } else {
            $io->error('could not create member!');
            return Command::FAILURE;
        }
    }
}
