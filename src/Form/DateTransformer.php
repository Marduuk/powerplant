<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\DataTransformerInterface;
use DateTime;
use Exception;

/**
 * Class DateTransformer
 * @package App\Form
 */
class DateTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $issue
     * @return int|mixed
     */
    public function transform( $issue)
    {
        if (null === $issue) {
            return 0;
        }
        return $issue->getTimestamp();
    }

    /**
     * @param mixed $timestamp
     * @return DateTime|mixed
     * @throws Exception
     */
    public function reverseTransform($timestamp)
    {
        if (!$timestamp) {
            return new DateTime();
        }
        $micro = sprintf("%06d",((float)$timestamp - floor((float)$timestamp)) * 1000000);
        return new DateTime( date('Y-m-d H:i:s.'.$micro, (int)$timestamp) );
    }
}