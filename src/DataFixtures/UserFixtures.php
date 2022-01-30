<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends BaseFixtures
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function loadData(ObjectManager $manager){

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->hashPassword(
                $user,
                'engage'
            ));
            return $user;
        };

        $manager->persist();

        $manager->flush();

    }

}
