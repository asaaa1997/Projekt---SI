<?php
/**
 * Status fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;

/**
 * Class StatusFixtures.
 */
class StatusFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            1,
            'active',
            function () {
                $status = new Status();
                $status->setTitle('ACTIVE');

                return $status;
            }
        );

        $this->createMany(
            1,
            'inactive',
            function () {
                $status = new Status();
                $status->setTitle('INACTIVE');

                return $status;
            }
        );

        $manager->flush();
    }
}
