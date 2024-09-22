<?php


namespace App\Controller;


use App\Entity\Comments;
use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Form\CommentType;
use App\Form\ParticipantType;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\CommentsRepository;
use App\Repository\ParticipantsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    // TEST PART

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

    // MAIN PART

    #[Route('/', name: 'app_homepage')]
    public function homepage(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/user/{userId}', name: 'app_profile')]
    public function profile($userId = null, ParticipantsRepository $participantsRepository): Response
    {
        $user = $this->getUser();
        $projects = $participantsRepository->findAllOrderedByParticipant($user);

        return $this->render('profile.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function tasks(): Response
    {
        return $this->render('tasks.html.twig');
    }

    #[Route('/comments', name: 'app_comments')]
    public function comments(CommentsRepository $commentsRepository, Request $request): Response
    {
        $user = $this->getUser();
        $queryBuilder = $commentsRepository->createOrderedByUserQueryBuilder($user);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $request->query->get('page', 1), 10);

        return $this->render('comments.html.twig', [
            'pager' => $pagerfanta
        ]);
    }
}