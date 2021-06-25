<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserData;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            10,
            'users',
            function ($i) {
                $user = new User();
                $user->setUsername(sprintf('user%d', $i));
                $user->setRoles([User::ROLE_USER]);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'user1234'
                    )
                );
                $userData = new UserData();
                $userData->setFirstname($this->faker->firstName);
                $userData->setLastname($this->faker->lastName);
                $userData->setEmail($this->faker->email);

                $user->setUserdata($userData);

                return $user;
            }
        );

        $this->createMany(
            3,
            'admins',
            function ($i) {
                $user = new User();
                $user->setUsername(sprintf('admin%d', $i));
                $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'admin1234'
                    )
                );
                $userData = new UserData();
                $userData->setFirstname($this->faker->firstName);
                $userData->setLastname($this->faker->lastName);
                $userData->setEmail($this->faker->email);

                $user->setUserdata($userData);

                return $user;
            }
        );

        $manager->flush();
    }
}
