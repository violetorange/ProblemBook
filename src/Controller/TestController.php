<?php


namespace App\Controller;


use App\Entity\Comments;
use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\TimeCosts;
use App\Form\admin\CommentType;
use App\Form\admin\ParticipantType;
use App\Form\admin\ProjectType;
use App\Form\admin\TaskType;
use App\Form\EditTaskType;
use App\Repository\CommentsRepository;
use App\Repository\ParticipantsRepository;
use App\Repository\ProjectsRepository;
use App\Repository\TasksRepository;
use App\Repository\TimeCostsRepository;
use App\Repository\UserRepository;
use App\Service\UploadFile;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    // TEST PART

    #[Route('/form', name: 'app_form')]
    public function index(Request $request, EntityManagerInterface $entityManager, UploadFile $uploadFile): Response
    {
        // Project Form
        $project = new Projects();
        $projectForm = $this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $projectLogo = $projectForm->get('img')->getData();
            if ($projectLogo) {
                $logoSrc = $uploadFile->upload($projectLogo);
                $project->setImg($logoSrc);
            }

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
    public function profile($userId, UserRepository $userRepository, ParticipantsRepository $participantsRepository, CommentsRepository $commentsRepository): Response
    {
        $user = $userRepository->findOneById($userId);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }
        $participating = $participantsRepository->findAllOrderedByParticipant($user);
        $comments = $commentsRepository->getFiveByUser($user);

        return $this->render('profile.html.twig', [
            'user' => $user,
            'participating' => $participating,
            'comments' => $comments
        ]);
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function tasks(Request $request, UserRepository $userRepository): Response
    {
        $userId = $request->get('user');
        $currentUser = $userRepository->findOneById($userId);
        if (!$currentUser) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $this->render('tasks.html.twig', [
            'currentUser' => $currentUser
        ]);
    }

    #[Route('task/{taskId}', name: 'app_task_detail')]
    public function taskDetail(TasksRepository $tasksRepository, CommentsRepository $commentsRepository, TimeCostsRepository $timeCostsRepository, $taskId, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Редактирование задачи
        $task = $tasksRepository->findOneById($taskId);
        if (!$task) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $editTaskForm = $this->createForm(EditTaskType::class, $task);
        $editTaskForm->handleRequest($request);

        if ($editTaskForm->isSubmitted() && $editTaskForm->isValid()) {

            // Создание комментария
            $newText = $editTaskForm->get('text')->getData();
            if ($newText) {
                $comment = new Comments();
                $comment->setNumber($commentsRepository->getNextCommentNumber($task));
                $comment->setTask($task);
                $comment->setText($newText);
                $comment->setUserOwner($this->getUser());
                $comment->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow')));
                $entityManager->persist($comment);
            }

            // Внесение трудозатрат
            $newTimeCostsAmount = $editTaskForm->get('timeCostsAmount')->getData();
            if ($newTimeCostsAmount > 0) {
                $newTimeCostsDescription = $editTaskForm->get('timeCostsDescription')->getData();
                $timeCosts = new TimeCosts();
                $timeCosts->setUserOwner($this->getUser());
                $timeCosts->setTask($task);
                $timeCosts->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow')));
                $timeCosts->setTime($newTimeCostsAmount);
                $timeCosts->setDescription($newTimeCostsDescription);
                $entityManager->persist($timeCosts);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_task_detail', ['taskId' => $taskId]);
        }

        // Общее количество затраченного на задачу времени
        $currentTimeCosts = $timeCostsRepository->findByTask($task);
        $totalTime = 0;
        foreach ($currentTimeCosts as $timeCost) {
            $totalTime += $timeCost->getTime();
        }

        // Отображение задачи
        return $this->render('taskDetail.html.twig', [
            'task' => $task,
            'task_edit_form' => $editTaskForm,
            'totalTime' => $totalTime
        ]);
    }

    #[Route('/comments', name: 'app_comments')]
    public function comments(UserRepository $userRepository, CommentsRepository $commentsRepository, Request $request): Response
    {
        $user = $request->get('user');
        $currentUser = $userRepository->findOneById($user);
        if (!$currentUser) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $queryBuilder = $commentsRepository->createOrderedByUserQueryBuilder($currentUser);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $request->query->get('page', 1), 10);

        return $this->render('comments.html.twig', [
            'pager' => $pagerfanta
        ]);
    }

    #[Route('tasks/{taskId}/time_costs', name: 'app_time_costs')]
    public function timeCosts($taskId, TasksRepository $tasksRepository, TimeCostsRepository $timeCostsRepository): Response
    {
        $currentTask = $tasksRepository->findOneById($taskId);
        if (!$currentTask) {
            throw new NotFoundHttpException('Задача не найдена');
        }
        $currenTasktTimeCosts = $timeCostsRepository->findByTask($currentTask);

        return $this->render('timeCosts.html.twig', [
            'timeCosts' => $currenTasktTimeCosts
        ]);
    }

    #[Route('project/{projectId}', name: 'app_project')]
    public function project($projectId, ProjectsRepository $projectsRepository, TasksRepository $tasksRepository): Response
    {
        $currentProject = $projectsRepository->find($projectId);
        if (!$currentProject) {
            throw new NotFoundHttpException('Проект не найден');
        }

        $currentProjectTasks = $tasksRepository->findByProjectGrouppedByType($currentProject);


        return $this->render('projectDetail.html.twig', [
            'project' => $currentProject,
            'grouped_tasks' => $currentProjectTasks
        ]);
    }

    #[Route('project/{projectId}/tasks', name: 'app_project_tasks')]
    public function projectTasks($projectId, ProjectsRepository $projectsRepository, TasksRepository $tasksRepository,Request $request): Response
    {
        $currentProject = $projectsRepository->find($projectId);
        if (!$currentProject) {
            throw new NotFoundHttpException('Проект не найден');
        }

        $currentType = $request->get('type');

        if ($currentType) {
            $currentTasks = $tasksRepository->findByTypeAndProject($currentType, $currentProject);
        } else {
            $currentTasks = $currentProject->getTasks();
        }

        return $this->render('projectTasks.html.twig', [
            'project' => $currentProject,
            'tasks' => $currentTasks,
            'type' => $currentType
        ]);
    }
}