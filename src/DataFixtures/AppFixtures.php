<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\TimeCosts;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Менеджер, первый пользователь
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setFirstname('Иван');
        $user->setLastname('Иванов');
        $user->setPosition('Менеджер');
        $user->setRoles(['ROLE_MANAGER']);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, 'pass');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        // Проект
        $project = new Projects();
        $project->setTitle('Тестовый проект 1');
        $project->setDescription('Описание тестового проекта 1');
        $manager->persist($project);

        // Задача
        $task = new Tasks();
        $task->setTitle('Исправить неверную переадресацию');
        $task->setDescription('Ссылка на главной странице никуда не ведёт в данный момент, нужно пофиксить');
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setUserOwner($user);
        $task->setProject($project);
        $task->setType('Ошибка');
        $task->setCostEstimation(2);
        $manager->persist($task);

        // Привязка пользовтеля к проекту
        $participant = new Participants();
        $participant->setParticipant($user);
        $participant->setProject($project);
        $manager->persist($participant);

        // Комментарий к задаче
        $comment = new Comments();
        $comment->setUserOwner($user);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setTask($task);
        $comment->setText('Тестовый комментарий к задаче');
        $comment->setNumber(1);
        $manager->persist($comment);

        // Временные затраты
        $timeCosts = new TimeCosts();
        $timeCosts->setTask($task);
        $timeCosts->setCreatedAt(new \DateTimeImmutable());
        $timeCosts->setUserOwner($user);
        $timeCosts->setDescription('Тестовое описание проделанной работы');
        $timeCosts->setTime(1.2);
        $manager->persist($timeCosts);

        $manager->flush();
    }
}
