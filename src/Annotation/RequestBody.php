<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class RequestBody
{
    /**
     * @Required
     * @var string
     */
    private $argumentName;

    public function __construct(array $values)
    {
        $this->argumentName = $values['argumentName'];
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }
}