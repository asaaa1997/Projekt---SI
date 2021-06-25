<?php
/**
 * Advert fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Advert;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AdvertFixtures.
 */
class AdvertFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            40,
            'adverts',
            function () {
                $advert = new Advert();
                $advert->setTitle($this->faker->sentence);
                $advert->setContent($this->faker->paragraph);
                $advert->setDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
                $advert->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
                $advert->setCategory($this->getRandomReference('categories'));

                $tags = $this->getRandomReferences(
                    'tags',
                    $this->faker->numberBetween(0, 5)
                );

                foreach ($tags as $tag) {
                    $advert->addTag($tag);
                }

                $advert->setAuthor($this->getRandomReference('users'));
                $advert->setStatus($this->getRandomReference('active'));

                return $advert;
            }
        );
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
