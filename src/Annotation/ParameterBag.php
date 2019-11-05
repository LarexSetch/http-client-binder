<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class ParameterBag
{
    /**
     * @var Parameter[]
     * @Required
     */
    private $parameters;

    public function __construct(array $values)
    {
        $this->parameters = $values["value"];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}