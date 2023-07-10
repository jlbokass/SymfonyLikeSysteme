<?php

namespace App\DataFixtures;

use App\Entity\LikedPost;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;

    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];

        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, '123456'))
            ;
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i =0; $i < 20; $i++) {
            $post = new Post();
            $post
                ->setTitle($faker->sentence(6))
                ->setIntroduction($faker->paragraph())
                ->setContent('<p>' . join(',', $faker->paragraphs()) . '</p>')
                ;

            $manager->persist($post);

            for ($j = 0; $j < mt_rand(0,10); $j++) {
                $like = new LikedPost();
                $like
                    ->setUser($faker->randomElement($users))
                    ->setPost($post)
                    ;

                $manager->persist($like);
            }
        }
        $manager->flush();
    }
}
