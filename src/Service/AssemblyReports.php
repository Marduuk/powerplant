<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Report;
use Doctrine\ODM\MongoDB\DocumentManager;
use DateTime;
use Exception;
use Doctrine\ODM\MongoDB\Iterator\Iterator;

/**
 * Class AssemblyReports
 * @package App\Service
 */
class AssemblyReports
{
    /** @var DocumentManager */
    private $dm;

    /** @var LoggerService */
    private $logger;

    /**
     * AssemblyReports constructor.
     * @param DocumentManager $dm
     * @param LoggerService $logger
     */
    public function __construct(DocumentManager $dm, LoggerService $logger)
    {
        $this->dm = $dm;
        $this->logger = $logger;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCurrentDate(): DateTime
    {
        return new DateTime( date('Y-m-d H:i:s.', time()));
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getYesterdaysDate(): DateTime
    {
        $start = new DateTime( date('Y-m-d H:i:s.', time()) );
        return $start->sub(new \DateInterval('P1D'));
    }

    /**
     * @throws Exception
     * return void
     */
    public function getReportForOneGenerator(): void
    {
        $res = $this->getReportForPeriod($this->getYesterdaysDate(), $this->getCurrentDate());

        $data = $this->prepareReportForOutput($res);
        $this->logger->logDailyReport($data);
    }

    /**
     * @param Iterator $report
     * @return array
     */
    public function prepareReportForOutput(Iterator $report): array
    {
        $data = [];
        foreach ($report as $r ){
            $data ['generators'][$r['_id']['generatorId']] []=  ['hour' => $r['_id']['hour'],  'power'=> $r['power']];
        }
        return $data;
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return Iterator
     */
    public function getReportForPeriod(DateTime $start, DateTime $end): Iterator
    {
        $builder = $this->dm->createAggregationBuilder(Report::class);
        return $builder
            ->match()
            ->field('measurementTime')
            ->gte($start)
            ->lt($end)
            ->group()
            ->field('id')
            ->expression('$id')
            ->expression(
                $builder->expr()
                    ->field('generatorId')
                    ->expression('$generatorId')
                    ->field('hour')
                    ->hour('$measurementTime')
            )
            ->field('power')
            ->avg('$power')
            ->execute();
    }
}