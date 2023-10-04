<?php

namespace App\Command;

use App\Entity\Cupboard;
use App\Entity\Member;
use App\Entity\CupboardRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command NewCupboard
 */
#[AsCommand(
    name: 'app:new-cupboard',
    description: 'Create a new cupboard',
)]
class NewCupboardCommand extends Command
{
    /**
     *  @var ManagerRegistry data access repository
     */
    private ManagerRegistry $doctrineManager;

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
        $this->doctrineManager = $doctrineManager;

        $this->cupboardRepository = $this->doctrineManager->getRepository(Cupboard::class);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a cupboard')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the cupboard')
            ->addArgument('member_name', InputArgument::REQUIRED, 'The name of the member');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create cupboard and set name
        $cupboard = new Cupboard();
        $cupboard->setName($input->getArgument('name'));
        // Load and set member
        $memberRepository = $this->doctrineManager->getRepository(Member::class);
        $member = $memberRepository->findByName($input->getArgument('member_name'));
        if (!$member) {
            $io->error('could not find member!');
            return Command::FAILURE;
        }
        $member[0]->addCupboard($cupboard);

        // Save cupboard
        $this->cupboardRepository->save($cupboard, true);

        // Check if cupboard has been created
        if ($cupboard->getId()) {
            $io->text('Created: ' . $cupboard);
            return Command::SUCCESS;
        } else {
            $io->error('could not create cupboard!');
            return Command::FAILURE;
        }
    }
}
