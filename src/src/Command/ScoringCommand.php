<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\Client;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:scoring-refresh',
    description: 'Client scoring calculation.',
    hidden: false,
    aliases: ['app:scoring']
)]
class ScoringCommand extends Command
{
    private $scoringService;
    private $entityManager;

    public function __construct(ScoringService $scoringService, EntityManagerInterface $entityManager)
    {
        $this->scoringService = $scoringService;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to recalculate client scoring.')
            ->addArgument('client_id', InputArgument::OPTIONAL, 'Client ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clientId = $input->getArgument('client_id');

        $repo = $this->entityManager->getRepository(Client::class);
        $criteria = $clientId ? ['id' => $clientId] : [];
        $clients = $repo->findBy($criteria);

        if (count($clients) == 0) {
            $output->writeln('Clients not found.');
        } else {
            $progressBar = new ProgressBar($output, count($clients));
            $progressBar->start();

            $table = new Table($output);
            $table->setHeaders($this->getTableHeader());
            $table->setHeaderTitle('Clients');

            foreach ($clients as $client) {
                $score = $this->scoringService->calcClientScore($client);
                $client->scoring($score);

                $this->entityManager->persist($client);
                $this->entityManager->flush();

                $table->addRow($this->getTableRow($client));
                $progressBar->advance();
            }

            $progressBar->finish();
            $output->writeln('');
            $table->render();
        }

        return Command::SUCCESS;
    }

    private function getTableHeader(): array
    {
        return ['ID', 'Firstname', 'Lastname', 'Phone', 'Email', 'Education', 'Consent', 'Scoring'];
    }

    private function getTableRow(Client $client): array
    {
        return [
            $client->getId(),
            $client->getFirstname(),
            $client->getLastname(),
            $client->getPhone(),
            $client->getEmail(),
            $client->getEducation()->name,
            $client->getConsent() ? 'Yes' : 'No',
            $client->getScore() ? $client->getScore()->getScore() : '-',
        ];
    }
}
