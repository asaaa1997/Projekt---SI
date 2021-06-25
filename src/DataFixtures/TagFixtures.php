<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            50,
            'tags',
            function () {
                $tag = new Tag();
                $tag->setContent($this->faker->word);
                $tag->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));

                return $tag;
            }
        );
        $manager->flush();
    }
}
