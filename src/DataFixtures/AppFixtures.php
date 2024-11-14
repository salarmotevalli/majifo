<?php

namespace App\DataFixtures;

use App\Entity\Approval;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostType;
use App\Entity\User;
use App\Enum\ApprovalStatusEnum;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager); 
        $this->loadPostType($manager);
        $this->loadCategory($manager);
        $this->loadPost($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as $data) {
            $user = new User();
            $user->setRoles($data['roles']);
            $user->setUsername($data['username']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setEmail($data['email']);

            $manager->persist($user);
            $this->addReference($data['username'], $user);
        }

        $manager->flush();
    }

    private function loadPost(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setTitle('title'.$i);
            $post->setPublishedAt(new \DateTime());
            $post->setContent($this->lorem());
            $post->setStatus(ApprovalStatusEnum::cases()[rand(0,2)]);
            $post->setPostType($this->getRandomType());
            $post->setAuthor($this->getRandomUser());
            $post->addCategory($this->getRandomCategory());
            $post->addCategory($this->getRandomCategory());

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function loadPostType(ObjectManager $manager)
    {
        foreach ($this->getPostTypeData() as $data) {
            $type = new PostType();
            $type->setTitle($data['title']);
            $type->setDescription($data['description']);
            $manager->persist($type);

            $this->addReference($data['title'].'-type', $type);
        }

        $manager->flush();
    }

    private function loadCategory(ObjectManager $manager)
    {
        foreach ($this->getCategoryData() as $data) {
            $categoey = new Category();
            $categoey->setTitle($data['title']);
            $categoey->setDescription($data['description']);
            $manager->persist($categoey);

            $this->addReference($data['title'].'-category', $categoey);
        }

        $manager->flush();
    }

    private function getRandomCategory(): Category
    {
        $refs = ['sport-category', 'food-category', 'art-category'];
        $randRef = $refs[rand(0, count($refs) -1)];
        return $this->getReference($randRef);
    }

    private function getRandomType(): PostType
    {
        $refs = ['image-type', 'text-type', 'video-type'];
        $randRef = $refs[rand(0, count($refs) -1)];
        return $this->getReference($randRef);
    }

    private function getRandomUser(): User
    {
        $rand = rand(0, count($this->getUserData()) -1);

        return $this->getReference($this->getUserData()[$rand]['username']);
    }

    private function getPostTypeData()
    {
        return [
            ['title' => 'image', 'description' => 'image type'],
            ['title' => 'video', 'description' => 'video type'],
            ['title' => 'text', 'description' => 'text type'],
        ];
    }

    private function getCategoryData()
    {
        return [
            ['title' => 'sport', 'description' => 'sport category'],
            ['title' => 'food', 'description' => 'food categoey'],
            ['title' => 'art', 'description' => 'art type'],
        ];
    }

    private function lorem() {
        return 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eius odio hic sunt tempora deserunt voluptates explicabo blanditiis atque. Vitae consequuntur quibusdam hic iste! Sint minus velit cum tempore necessitatibus fuga.';
    }
    
    private function getUserData(): array
    {
        return [
            ['username' => 'Jane Doe' ,'email' => 'jane_admin@symfony.com','roles' => [RoleEnum::SUPER_ADMIN]],
            ['username' => 'Tom Doe' ,'email' => 'tom_admin@symfony.com','roles' => [RoleEnum::NORMAL_ADMIN]],
            ['username' => 'John Nil' ,'email' => 'john_user@symfony.com','roles' => [RoleEnum::READ_ONLY_ADMIN, RoleEnum::POST_MANAGER_ADMIN]],
            ['username' => 'Normal' ,'email' => 'normal@symfony.com','roles' => []],
        ];
    }
}
