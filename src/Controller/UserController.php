<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/user/{id}', name: 'app_user_detail', methods: ['GET'])]
    public function getUserById(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'getUser']);
    }

    #[Route('/api/user/create', name: 'app_user_create', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
       $user = $serializer->deserialize($request->getContent(), User::class, 'json');
       $existingUser = $em->getRepository(User::class)
            ->findOneBy(['username' => $user->getUsername()]);

        if ($existingUser) {
            return new JsonResponse(
                ['message' => 'Username already exists'],
                Response::HTTP_CONFLICT
            );
        }
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUser']);
        
        $location = $urlGenerator->generate('app_user_detail', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/user/update/{id}', name: 'app_user_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateUser(Request $request, SerializerInterface $serializer, User $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        return $this->json($user, Response::HTTP_OK, ['groups' => 'getUser']);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/user/delete/{id}', name: 'app_user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }
        $em->remove($user);
        $em->flush();
        return new JsonResponse("L'utilisateur a été supprimé avec succès.", Response::HTTP_OK);
    }
}
