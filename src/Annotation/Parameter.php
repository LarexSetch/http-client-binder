<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
final class Parameter
{
    /**
     * @var string
     * @Required
     */
    private $argumentName;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $type;

    public function __construct(array $values)
    {
        $this->argumentName = $values["argument"];
        $this->alias = $values["alias"];
        $this->type = $values["type"] ?? 'query';
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getType(): string
    {
        return $this->type;
    }
}