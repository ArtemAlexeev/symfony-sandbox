<?php

namespace App\Infrastructure\Entrypoints\Http\Rest;

use App\Application\DTO\Response\UserSettingsDTO;
use App\Application\DTO\UpdateUserSettingsDTO;
use App\Domain\Entity\UserSettings;
use App\Domain\Enum\User\Language;
use App\Infrastructure\Entrypoints\Http\BaseController;
use App\Infrastructure\Persistence\Doctrine\UserSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'User Settings')]
#[Route('/user/settings', name: 'api_user_settings_')]
class UserSettingsController extends BaseController
{
    public function __construct(
        private LoggerInterface $businessLogger,
        private UserSettingsRepository $repo,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $settings = $this->repo->findOneByUser($this->getUser());
            if (!$settings) {
                $settings = new UserSettings($this->getUser());
                $this->entityManager->persist($settings);
                $this->entityManager->flush();
            }

            return $this->json([
                'data' => UserSettingsDTO::fromEntity($settings),
            ]);
        } catch (Exception $e) {
            $this->businessLogger->error('Error fetching user settings', [
                'msg' => $e->getMessage(),
            ]);

            return $this->json([
                'error' => 'An error occurred while fetching user settings.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'patch', methods: ['PATCH', 'PUT'])]
    public function updateSettings(
        Request $request,
        #[MapRequestPayload] UpdateUserSettingsDTO $dto,
        SerializerInterface $serializer
    ): JsonResponse {
        try {
            $user = $this->getUser();
            $settings = $this->repo->findOneByUser($user);
            if (!$settings) {
                $settings = new UserSettings($user);
                $this->entityManager->persist($settings);
            }

            if (isset($dto->emailEnabled)) $settings->setEmailEnabled($dto->emailEnabled);
            if (isset($dto->pushEnabled)) $settings->setPushEnabled($dto->pushEnabled);
            if (isset($dto->language)) $settings->setLanguage(Language::from($dto->language));

            $this->entityManager->flush();

            return $this->json([
                'data' => UserSettingsDTO::fromEntity($settings),
            ]);
        } catch (Exception $e) {
            $this->businessLogger->error('Error updating user settings', [
                'msg' => $e->getMessage(),
            ]);

            return $this->json([
                'error' => 'An error occurred while updating user settings.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
