<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Repository\AdviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

final class AdviceController extends AbstractController
{
    #[Route('/api/advice', name: 'app_advice', methods: ['GET'])]
    public function index(AdviceRepository $adviceRepository, SerializerInterface $serializer): JsonResponse
    {
        $adviceList = $adviceRepository->findAll();
        $jsonAdviceList = $serializer->serialize($adviceList, 'json');
        return new JsonResponse($jsonAdviceList, Response::HTTP_OK, [], true
        );
    }

    #[Route('/api/advice/{month}', name: 'app_advice_by_month', requirements: ['month' => '0?[1-9]|1[0-2]'], methods: ['GET'])]
    public function getAdviceByMonth(string$month, AdviceRepository $adviceRepository, SerializerInterface $serializer): JsonResponse
    {
        if (strlen($month) > 2 || !ctype_digit($month)) {
            return new JsonResponse(['error' => 'Mauvais format pour le mois. le mois doit être un nombre à deux chiffres entre 1 et 12.'], Response::HTTP_BAD_REQUEST);
        } else if ((int)$month < 1 || (int)$month > 12) {
            return new JsonResponse(['error' => 'Mois invalide. le mois doit être un nombre entre 01 et 12.'], Response::HTTP_BAD_REQUEST);
        }
        $adviceList = $adviceRepository->findByMonth($month);
        $jsonAdviceList = $serializer->serialize($adviceList, 'json');
        return new JsonResponse($jsonAdviceList, Response::HTTP_OK, [], true
        );
    }

    #[Route('/api/advice/current-month', name: 'app_advice_current_month', methods: ['GET'])]
    public function getAdviceForCurrentMonth(AdviceRepository $adviceRepository, SerializerInterface $serializer): JsonResponse
    {   
        $currentMonth = date('m');
        $adviceList = $adviceRepository->findByMonth($currentMonth);
        $jsonAdviceList = $serializer->serialize($adviceList, 'json');
      
        return new JsonResponse($jsonAdviceList, Response::HTTP_OK, [], true
        );
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/advice/create', name: 'app_advice_create', methods: ['POST'])]
    public function createAdvice(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $advice = $serializer->deserialize($request->getContent(), Advice::class, 'json');
        $em->persist($advice);
        $em->flush();
        $jsonAdvice = $serializer->serialize($advice, 'json');
        return new JsonResponse($jsonAdvice, Response::HTTP_CREATED, [], true);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/advice/{id}/update', name: 'app_advice_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAdvice(Request $request, SerializerInterface $serializer, Advice $advice, EntityManagerInterface $em): JsonResponse
    {
        $serializer->deserialize(
            $request->getContent(),
            Advice::class,
            'json',
            ['object_to_populate' => $advice]
        );

        if (empty($advice->getMonths())) {
            return new JsonResponse(
                ['error' => 'Vous devez renseigner au moins un mois.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        foreach ($advice->getMonths() as $month) {
            if ($month < 1 || $month > 12) {
                return new JsonResponse(
                    ['error' => 'Mois invalide. Chaque mois doit être compris entre 1 et 12.'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        $em->persist($advice);
        $em->flush();
        return $this->json($advice, Response::HTTP_OK); 
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/advice/{id}/delete', name: 'app_advice_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAdvice(int $id, EntityManagerInterface $em): JsonResponse
    {
        $advice = $em->getRepository(Advice::class)->find($id);
        if (!$advice) {
            return new JsonResponse(['error' => 'Avis non trouvé.'], Response::HTTP_NOT_FOUND);
        };
        $em->remove($advice);
        $em->flush();

        // Return a success message for the deletion but we could also use a 204 No Content response if we don't want to return any content.
        return new JsonResponse("L'avis a été supprimé avec succès.", Response::HTTP_OK);   
    } 
}
