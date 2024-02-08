<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\EmailService;

#[AsCommand(
    name: 'app:envia-gmail',
    description: 'Una forma nueva de enviar gmail.',
)]

class EnviarGmailCommand extends Command
{

    public function __construct(private EmailService $EmailService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('destinatario', InputArgument::OPTIONAL, '¿A quién le envías?')
            ->addArgument('remitente', InputArgument::OPTIONAL, '¿Quién envía?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $destinatario = $input->getArgument('destinatario') ?: 'mariadoloresga18@gmail.com';

        $remitente = $input->getArgument('remitente') ?: 'mgonalc1802@g.educaand.es';

        $this->EmailService->mandaEmail($remitente, $destinatario);

        $message = sprintf('Mensaje enviado a %s de parte de %s', $destinatario, $remitente);

        $io->success($message);
        
        return Command::SUCCESS;
    }

}