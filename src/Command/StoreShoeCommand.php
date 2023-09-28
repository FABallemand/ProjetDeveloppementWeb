<?php

namespace App\Command;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:store-shoe',
    description: 'Store shoes in a cupboard',
)]
class StoreShoeCommand extends Command
{
    /**
     *  @var ManagerRegistry data access repository
     */
    private ManagerRegistry $doctrineManager;

    /**
     * Plugs the database to the command
     *
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('shoeId', InputArgument::REQUIRED, 'id of the shoe')
            ->addArgument('cupboardName', InputArgument::REQUIRED, 'Name of the cupboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $shoeId = $input->getArgument('shoeId');
        $cupboardName = $input->getArgument('cupboardName');

        // dump($shoeId);
        // dump($cupboardName);

        // Load shoe
        $shoeRepository = $this->doctrineManager->getRepository(Shoe::class);
        $shoe = $shoeRepository->find($shoeId);
        if (!$shoe) {
            $io->error('could not find shoe!');
            return Command::FAILURE;
        }

        // load cupboard
        $cupboardRepository = $this->doctrineManager->getRepository(Cupboard::class);
        $cupboard = $cupboardRepository->findOneBy(['name' => $cupboardName]);
        // $tag = $cupboardRepository->findOneByName($cupboardName);
        if (!$cupboard) {
            $io->error('could not find cupboard!');
            return Command::FAILURE;
        }

        // dump($shoe);
        // dump($tag);

        // add shoe to cupboard
        $cupboard->addShoe($shoe);

        // save changes to the database
        $cupboardRepository->save($cupboard, true);

        return Command::SUCCESS;
    }
}
