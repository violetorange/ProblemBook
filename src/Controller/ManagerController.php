<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\User;
use App\Form\admin\ParticipantType;
use App\Form\admin\ProjectType;
use App\Form\admin\TaskType;
use App\Form\admin\UserType;
use App\Service\UploadFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ManagerController extends AbstractController
{
    #[Route('/manager', name: 'app_manager')]
    public function index(Request $request, EntityManagerInterface $entityManager, UploadFile $uploadFile, UserPasswordHasherInterface $userPasswordHasher): Response
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

        // User Form
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $roles = $userForm->get('roles')->getData();
            $user->setRoles([$roles]);

            $user->setPassword($userPasswordHasher->hashPassword($user, $userForm->get('password')->getData()));

            $entityManager->persist($user);
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

        return $this->render('manager/index.html.twig', [
            'project_form' => $projectForm->createView(),
            'task_form' => $taskForm->createView(),
            'user_form' => $userForm->createView(),
            'participant_form' => $participantForm->createView()
        ]);
    }
}
