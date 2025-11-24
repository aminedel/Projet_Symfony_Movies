<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 1. Créer l'ADMIN (Celui que tu utilises pour te connecter)
        $admin = new User();
        $admin->setEmail('admin@test.com');
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // 2. Créer des catégories
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $cat = new Category();
            $cat->setName($faker->word());
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // 3. Créer des acteurs
        $actors = [];
        for ($i = 0; $i < 20; $i++) {
            $actor = new Actor();
            $actor->setLastname($faker->lastName());
            $actor->setFirstname($faker->firstName());
            $actor->setDob($faker->dateTimeBetween('-80 years', '-20 years'));
            $manager->persist($actor);
            $actors[] = $actor;
        }

        // 4. Créer des films
        for ($i = 0; $i < 50; $i++) {
            $movie = new Movie();
            
            $movie->setName($faker->sentence(3));
            $movie->setDescription($faker->paragraph());
            $movie->setOnline($faker->boolean(80));
            $movie->setDuration($faker->numberBetween(90, 180));
            $movie->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'));
            $movie->setCategory($faker->randomElement($categories));

            $randomActors = $faker->randomElements($actors, mt_rand(2, 5));
            foreach ($randomActors as $a) {
                $movie->addActor($a);
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}