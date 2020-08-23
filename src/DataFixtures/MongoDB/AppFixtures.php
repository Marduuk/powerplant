<?php
declare(strict_types=1);

namespace App\DataFixtures\MongoDB;

use App\Document\Report;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;

/**
 * AppFixtures.
 */
final class AppFixtures extends Fixture
{
    /** @var DocumentManager */
    private $dm;

    /**
     * AppFixtures constructor.
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++){

            $date = new \DateTime("2019-01-01 00:00:00");
            $end = new \DateTime("2019-01-02 00:00:00");
            $startTimeStamp = $date->getTimestamp();
            $endTimeStamp = $end->getTimestamp();

            while ($startTimeStamp < $endTimeStamp) {
                $micro = sprintf("%06d",((float)$startTimeStamp - floor((float)$startTimeStamp)) * 1000000);
                $kewl = new \DateTime( date('Y-m-d H:i:s.'.$micro, (int)$startTimeStamp) );

                $startTimeStamp += 0.5;

                $test = new Report();

                $test->setMeasurementTime($kewl);
                $test->setGeneratorId($i);
                $test->setPower(rand(1, 1000));
                $this->dm->persist($test);
            }
            $this->dm->flush();
        }
    }
}
