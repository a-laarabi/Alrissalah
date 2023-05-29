<?php

namespace App\Serializer;

use App\Entity\Level;
use App\Repository\CourseContentRepository;
use App\Repository\UserProgressesRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LevelNormalizer_ implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'BOOK_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    private Security $security;
    private UserProgressesRepository $userProgressesRepository;
    private CourseContentRepository $courseContentRepository;

    private PaymentRepository $paymentRepository;

    public function __construct(
        Security $security,
        UserProgressesRepository $userProgressesRepository,
        CourseContentRepository $courseContentRepository,
        PaymentRepository $paymentRepository

    )
    {
        $this->security = $security;
        $this->userProgressesRepository = $userProgressesRepository;
        $this->courseContentRepository = $courseContentRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED]))
            return false;

        return $data instanceof Level;
    }

    public function normalize(mixed $object, string $format = null, array $context = []): float|array|\ArrayObject|bool|int|string|null
    {

        $user = $this->security->getUser();
        $countCC = $this->courseContentRepository->countByLevel($object->getId());
        $countUP = $this->userProgressesRepository->countByUser($user);

        $paymentExists = $this->paymentRepository->findOneBy(['user' => $user]);

        $object->setCompleted($countCC !== 0 ? $countUP * 100 / $countCC : 0);
        $object->setPaid($paymentExists !== null);

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }
}