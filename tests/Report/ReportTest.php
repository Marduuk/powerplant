<?php
declare(strict_types=1);

namespace App\Tests\Report;

use App\Document\Report;
use App\Service\ReportService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DateTime;

/**
 * Class ReportTest
 * @package App\Tests\Report
 */
class ReportTest extends WebTestCase
{
    /**
     * @throws \Exception
     */
    public function testSaveNewReport(): void
    {
        $client = static::createClient();
        /** @var  DocumentManager $lol */
        $dm = static::$kernel->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $timestamp = 149760458.11;

        $client->request('POST', 'http://localhost:4040/report', ['generatorId' => 12, 'power' => 5, 'measurementTime' => $timestamp]);
        $this->assertResponseStatusCodeSame(201, (string)$client->getResponse()->getStatusCode());


        $micro = sprintf("%06d", ($timestamp - floor($timestamp)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.' . $micro, (int)$timestamp));
        /** @var Report $report */
        $report = $dm->getRepository(Report::class)->findOneBy(['measurementTime' => $date]);

        $this->assertEquals(12, $report->getGeneratorId());
    }

    /**
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function testServiceWithParams(): void
    {
        static::createClient();
        /** @var  DocumentManager $dm */
        $dm = static::$kernel->getContainer()->get('doctrine_mongodb.odm.document_manager');
        /** @var PaginatorInterface $paginator */
        $paginator = static::$kernel->getContainer()->get('knp_paginator');
        $service = new ReportService($dm, $paginator);

        $reports = $service->getDataBasedOnParams(
            1,
            10,
            3,
            new \DateTime("2019-01-01 22:00:00"),
            new \DateTime("2019-01-01 23:00:00")
        );
        $this->assertEquals(10, count($reports));

        /** @var Report $report */
        foreach ($reports as $report){
            $this->assertEquals(3, $report->getGeneratorId());
            $this->assertGreaterThanOrEqual(new \DateTime("2019-01-01 22:00:00"), $report->getMeasurementTime());
            $this->assertLessThanOrEqual(new \DateTime("2019-01-01 23:00:00"), $report->getMeasurementTime());

        }
    }
}