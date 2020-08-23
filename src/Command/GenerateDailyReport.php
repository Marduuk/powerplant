<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\AssemblyReports;
use App\Service\LoggerService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateDailyReport
 * @package App\Command
 */
class GenerateDailyReport extends Command
{
    /** @var LoggerService  */
    private $logger;

    /** @var DocumentManager  */
    private $dm;

    /** @var string  */
    protected static $defaultName = 'app:daily-report';

    /**
     * GenerateDailyReport constructor.
     * @param LoggerService $logger
     * @param DocumentManager $dm
     */
    public function __construct(LoggerService $logger, DocumentManager $dm)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->dm = $dm;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = new AssemblyReports($this->dm, $this->logger);
        $test->getReportForOneGenerator();
        return Command::SUCCESS;
    }
}