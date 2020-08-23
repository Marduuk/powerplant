<?php
declare(strict_types=1);

namespace App\Service;

use App\Document\Report;
use Doctrine\ODM\MongoDB\DocumentManager;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
/**
 * Class ReportService
 * @package App\Service
 */
class ReportService
{
    /** @var DocumentManager */
    private $dm;

    /** @var PaginatorInterface */
    private $paginator;

    /**
     * ReportService constructor.
     * @param DocumentManager $dm
     * @param PaginatorInterface $paginator
     */
    public function __construct(DocumentManager $dm, PaginatorInterface $paginator)
    {
        $this->dm = $dm;
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     * @param int $limit
     * @param int|null $generatorId
     * @param DateTime|null $dateFrom
     * @param DateTime|null $dataTo
     * @return PaginationInterface
     */
    public function getDataBasedOnParams($page = 1, $limit = 10, int $generatorId = null, DateTime $dateFrom = null, DateTime $dataTo = null): PaginationInterface
    {
        $qb = $this->dm->createQueryBuilder(Report::class);

        if ($generatorId !== null)
            $qb->field('generatorId')->equals($generatorId);

        if ($dateFrom !== null)
            $qb->field('measurementTime')->gte($dateFrom);

        if ($dataTo !== null)
            $qb->field('measurementTime')->lt($dataTo);

        return $this->paginator->paginate($qb->getQuery(), $page, $limit);
    }
}