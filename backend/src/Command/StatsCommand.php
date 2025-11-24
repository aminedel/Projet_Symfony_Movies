<?php

namespace App\Command;

use App\Repository\ActorRepository;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:stats',
    description: 'Affiche les statistiques de la BDD (Films et Acteurs)',
)]
class StatsCommand extends Command
{
    public function __construct(
        private MovieRepository $movieRepository,
        private ActorRepository $actorRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // On compte les éléments en BDD
        $nbMovies = $this->movieRepository->count([]);
        $nbActors = $this->actorRepository->count([]);

        $io->title('Statistiques de l\'application');
        $io->text("---------------------------------");
        $io->success("Nombre de films : " . $nbMovies);
        $io->success("Nombre d'acteurs : " . $nbActors);
        $io->text("---------------------------------");

        return Command::SUCCESS;
    }
}