<?php


namespace App\Controller;


use App\Entity\Comments;
use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ParticipantType;
use App\Form\ProjectType;
use App\Form\TaskType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/form', name: 'app_form')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Project Form
        $project = new Projects();
        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();
        }

        // Task Form
        $task = new Tasks();
        $taskForm = $this->createForm(TaskType::class, $task);
        $taskForm->handleRequest($request);

        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
        }

        // Comment Form
        $comment = new Comments();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        // Participant Form
        $participant = new Participants();
        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();
        }

        return $this->render('form/index.html.twig', [
            'project_form' => $projectForm->createView(),
            'task_form' => $taskForm->createView(),
            'comment_form' => $commentForm->createView(),
            'participant_form' => $participantForm->createView()
        ]);
    }

    #[Route('/test', name: 'app_test')]
    #[IsGranted('ROLE_USER')]
    public function testPage(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('base.html.twig', ['user' => $user]);
    }
}