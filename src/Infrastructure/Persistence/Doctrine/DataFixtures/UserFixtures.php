<?php

namespace App\Infrastructure\Persistence\Doctrine\DataFixtures;

use App\Domain\Entity\Profile;
use App\Domain\Entity\User;
use App\Domain\Enum\User\Gender;
use App\Domain\Enum\User\Status;
use App\Domain\ValueObject\Age;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $genders = Gender::cases();
        $statuses = Status::cases();
        $femaleAvatars = [46,47,48,49];

        for ($i = 0; $i < 100; $i++) {
            try {
                $user = new User();
                $user->setEmail($faker->unique()->safeEmail());
                $user->setPassword('sdadad21asd@#@#sdad4343');

                $profile = new Profile($user);
                $gender = $genders[array_rand($genders)];

                if ($gender == Gender::FEMALE) {
                    $firstName = $faker->firstNameFemale();
                    $avatar = 'https://i.pravatar.cc/150?img=' . $femaleAvatars[array_rand($femaleAvatars)];
                } else {
                    $firstName = $faker->firstNameMale();
                    $avatar = 'https://i.pravatar.cc/150?img=50';
                }
                $profile->putDetails(
                    $firstName,
                    $faker->lastName(),
                    new Age($faker->numberBetween(18, 70)),
                    $gender,
                    $statuses[array_rand($statuses)],
                    $faker->paragraph(),
                    $avatar
                );
                $manager->persist($user);
                $manager->persist($profile);
            } catch (Exception $e) {
                echo 'Error creating user: ' . $e->getMessage() . PHP_EOL;
                continue;
            }
        }

        $manager->flush();
    }
}
