<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\User;
use App\Entity\UserSkillLevel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class MainController extends AbstractController
{

    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response {

        $allUserSkills = $entityManager->getRepository(UserSkillLevel::class)->findAll();

        return $this->render('main/index.html.twig', [
            'allUserSkills' => $allUserSkills,
        ]);
    }

    #[Route('/my-skills', name: 'app_my_skills', methods: ['GET', 'POST'])]
    public function mySkills(
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
    ): Response
    {
        $user = $entityManager->getRepository(User::class)->findWithSkills($this->getUser()->getId());

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            $submittedToken = $data['_csrf_token'] ?? null;
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('update_skill', $submittedToken))) {
                return $this->json(['error' => 'Invalid CSRF token'], 400);
            }

            $skill = $entityManager->getRepository(Skill::class)->find($data['skill_id']);
            $checkboxChecked = $data['checkbox_checked'];

            if ($checkboxChecked) {
                // Creating new entry in user_skill_level
                $userSkillLevel = new UserSkillLevel();
                $userSkillLevel->setUser($user);
                $userSkillLevel->setSkill($skill);
                $userSkillLevel->setLevel(1);

                $entityManager->persist($userSkillLevel);
                $entityManager->flush();

                return $this->json(["status" => "ok", "message" => "Created new entry."]);
            } elseif ($checkboxChecked === false) {
                // Removing entry from user_skill_level
                $userSkillLevel = $entityManager->getRepository(UserSkillLevel::class)->findOneBy(["user" => $user, "skill" => $skill]);
                $entityManager->remove($userSkillLevel);
                $entityManager->flush();

                return $this->json(["status" => "ok", "message" => "Removed entry."]);
            }

            return $this->json(["message" => "No database updates."]);
        }

        $allSkills = $entityManager->getRepository(Skill::class)->findAll();
        $userSkills = $user->getUserSkillLevels();
        $userSkillIds = array_map(fn(UserSkillLevel $userSkillLevel) => $userSkillLevel->getSkill()->getId(), $userSkills->toArray());
        $skillIdLevel = [];

        foreach ($userSkills as $userSkill) {
            $skillIdLevel[$userSkill->getSkill()->getId()] = $userSkill->getLevel();
        }

        return $this->render('main/my_skills.html.twig', [
            'allSkills' => $allSkills,
            'userSkills' => $userSkills,
            'userSkillIds' => $userSkillIds,
            'skillIdLevel' => $skillIdLevel,
        ]);
    }

    #[Route('/my-skills/{id}', name: 'app_update_skill_level', methods: ['POST'])]
    public function updateSkillLevel(
        EntityManagerInterface $entityManager,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager,
    ): Response {

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            $submittedToken = $data['_csrf_token'] ?? null;
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('update_skill', $submittedToken))) {
                return $this->json(['error' => 'Invalid CSRF token'], 400);
            }

            $user = $entityManager->getRepository(User::class)->findWithSkills($this->getUser()->getId());
            $skill = $entityManager->getRepository(Skill::class)->find($data['skill_id']);

            $userSkillsLevel = $entityManager->getRepository(UserSkillLevel::class)->findOneBy(["user" => $user, "skill" => $skill]);

            // Update skill level in table "user_skill_level"
            $userSkillsLevel?->setLevel($data["skill_level"]);
            $entityManager->persist($userSkillsLevel);
            $entityManager->flush();

            return $this->json(["status" => "ok", "message" => "Updated skill level successfully."]);
        }

        return $this->json(["message" => "Error updating skill level"]);
    }
}
