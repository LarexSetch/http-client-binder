<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\Self_;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
final class Parameter
{
    public const TYPE_QUERY = 'query';
    public const TYPE_PATH = 'path';
    public const TYPE_BODY = 'body';
    public const TYPE_HEADER = 'header';

    /**
     * @var string
     * @Required
     */
    private $argumentName;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * @var string[]
     */
    private $types = [];

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->argumentName = $values['value'];
        } elseif (isset($values['argument'])) {
            $this->argumentName = $values["argument"];
        }

        if (isset($values['types'])) {
            $this->types =
                is_array($values['types'])
                    ? $values['types']
                    : [$values['types']];
        }

        if (isset($values['alias'])) {
            $this->alias = $values["alias"];
        }

        $this->checkParameter();
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    private function checkParameter(): void
    {
        if (null === $this->argumentName) {
            throw $this->createException();
        }

        if (!$this->isTypesCorrect()) {
            throw $this->createIncorrectTypeException();
        }
    }

    private function createException(): InvalidArgumentException
    {
        return
            new InvalidArgumentException(
                'The parameter is not required but you must define as' .
                '@Parameter("argumentName", alias="aliasForArgument", type="query") or' .
                '@Parameter("argumentName", alias="aliasForArgument") or' .
                '@Parameter("argumentName", type="query") or ' .
                '@Parameter(argument="argumentName", type="query")'
            );
    }

    private function createIncorrectTypeException(): InvalidArgumentException
    {
        return
            new InvalidArgumentException(sprintf(
                'Incorrect types %s available %s',
                implode(',', $this->types),
                implode(',', self::allTypes())
            ));
    }

    private function isTypesCorrect(): bool
    {
        return 0 === count(array_diff($this->types, self::allTypes()));
    }

    private static function allTypes(): array
    {
        return [self::TYPE_QUERY, self::TYPE_PATH, self::TYPE_BODY, self::TYPE_HEADER];
    }
}