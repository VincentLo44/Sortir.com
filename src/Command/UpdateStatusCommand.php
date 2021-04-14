<?php

namespace App\Command;

use App\Entity\Outing;
use App\Entity\OutingStatus;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateStatusCommand extends Command
{
    protected static $defaultName = 'app:update-status';
    protected static $defaultDescription = 'Update of outings status';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new DateTime();
        $io = new SymfonyStyle($input, $output);
        $outingRepository = $this->entityManager->getRepository(Outing::class);
        $allOutings = $outingRepository->findAll();

        $io->progressStart();

        /** @var Outing $outing */
        foreach ($allOutings as $outing){
            if($outing->getStartingTime() < new \DateTime()){
                $outing->setStatus($this->entityManager->getRepository(OutingStatus::class)->findOneBy(['description' => "Done"]));
                $io->writeln("Status changed for outing ".$outing->getId());
            }

            if($outing->getStartingTime() < $date->modify('-1 month')){
                $outing->setStatus($this->entityManager->getRepository(OutingStatus::class)->findOneBy(['description' => "Closed"]));
                $io->writeln("Status changed for outing ".$outing->getId());
            }
            $io->progressAdvance();
        }

        $io->progressFinish();

        $io->writeln("Executing app:update-status");

        $io->success('JOB DONE !! Update complete');

    }
}
