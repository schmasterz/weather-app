<?php

namespace App\Command;

use App\Service\ApiKeyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;

#[AsCommand(
    name: 'react:update-api-key',
    description: 'Generate an API key and update REACT_APP_API_KEY in the .env file if missing',
)]
class ReactUpdateApiKeyCommand extends Command
{
    private ApiKeyService $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dotenvPath = dirname(__DIR__, 2) . '/.env';
        $dotenv = new Dotenv();

        try {
            $envVars = [];
            if (file_exists($dotenvPath)) {
                $envVars = $dotenv->parse(file_get_contents($dotenvPath), $dotenvPath);
            }

            if (empty($envVars['REACT_APP_API_KEY'])) {
                $io->info('REACT_APP_API_KEY is missing or empty. Generating a new API key.');

                $newApiKey = $this->apiKeyService->generateAndSaveApiKey('react-app')->getApiKeyValue();
                $envContent = file_exists($dotenvPath) ? file_get_contents($dotenvPath) : '';
                $envContent .= "\nREACT_APP_API_KEY=\"$newApiKey\"\n";
                file_put_contents($dotenvPath, $envContent);

                $io->success('API key generated and added to .env file.');
            } else {
                $io->info('REACT_APP_API_KEY already exists in the .env file.');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}