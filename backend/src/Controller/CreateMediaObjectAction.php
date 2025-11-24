<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $media = new MediaObject();
        $media->file = $uploadedFile;

        // 1. On sauvegarde manuellement en base de données
        // (Comme ça, VichUploader va s'activer et créer le fichier)
        $em->persist($media);
        $em->flush();

        // 2. On construit la réponse JSON nous-mêmes pour éviter l'erreur 500
        // On renvoie l'ID et l'URL, c'est ce qu'il faut pour lier au film.
        return $this->json([
            '@context' => '/api/contexts/MediaObject',
            '@id' => '/api/media_objects/' . $media->getId(),
            '@type' => 'MediaObject',
            'contentUrl' => '/media/' . $media->getFilePath(),
            'id' => $media->getId()
        ], 201);
    }
}