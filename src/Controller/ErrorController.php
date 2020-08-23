<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ErrorController
 * @package App\Controller
 */
class ErrorController implements NormalizerInterface
{
    /**
     * @param mixed $exception
     * @param string|null $format
     * @param array $context
     * @return JsonResponse
     */
    public function normalize($exception, string $format = null, array $context = []): JsonResponse
    {
        if ($exception->getStatusCode() === 501)
            $msg = 'Something went wrong';
        elseif ($exception->getStatusCode() === 404)
            $msg = 'Resource not found';
        else
            $msg = $exception->getMessage();

        return new JsonResponse(
            [
                'success' => false,
                'exception' => $msg,
            ],
            $exception->getStatusCode()
        );
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof FlattenException;
    }
}