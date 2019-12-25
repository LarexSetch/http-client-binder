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
    public const TYPE_BODY = 'body';

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
        if(isset($values['value'])) {
            $this->argumentName = $values['value'];
        } elseif(isset($values['argument'])) {
            $this->argumentName = $values["argument"];
        }

        if(isset($values['alias'])) {
            $this->alias = $values["alias"];
        }

        if(isset($values['type'])) {
            $this->type = $values["type"];
        } else {
            $this->type = self::TYPE_QUERY;
        }

        $this->checkParameter();
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

    private function checkParameter(): void
    {
        if(null === $this->argumentName) {
            throw $this->createException();
        }

        if( !in_array($this->type, self::getTypes())) {
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
                '@Parameter("argumentName", type="query") or '.
                '@Parameter(argument="argumentName", type="query")'
            );
    }

    private function createIncorrectTypeException(): InvalidArgumentException
    {
        return
            new InvalidArgumentException(sprintf(
                'Incorrect type %s available %s',
                $this->type,
                implode(',', self::getTypes())
            ));
    }

    private static function getTypes(): array
    {
        return [self::TYPE_QUERY, self::TYPE_BODY];
    }
}