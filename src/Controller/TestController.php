<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TestController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function homepage(EntityManagerInterface $entityManager): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/new', name: 'app_new')]
    public function new(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $newUser = New User();
        $newUser->setEmail('admin@test.com');
        $newUser->setFirstname('Артемий');
        $newUser->setLastname('Лебедев');
        $newUser->setPosition('Администратор');
        $newUser->setRoles(['ROLE_ADMIN']);
        $newUser->setPassword($userPasswordHasher->hashPassword($newUser, 'tada'));

        $entityManager->persist($newUser);
        $entityManager->flush();

        return new Response('All right!');
    }

    #[Route('/admin', name: 'app_admin')]
    public function privateZone(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/test', name: 'app_test')]
    #[IsGranted('ROLE_USER')]
    public function testPage(Security $security): Response
    {
        $user = $security->getUser();
        $email = $user->getEmail();
        return $this->render('base.html.twig', ['test' => $email]);
    }
}