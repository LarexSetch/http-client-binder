<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use InvalidArgumentException;

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
        if (isset($values['value'])) {
            $this->argumentName = $values['value'];
        } elseif (isset($values['argumentName'])) {
            $this->argumentName = $values['argumentName'];
        } else {
            throw new InvalidArgumentException(
                'You must define request body as ' .
                '@RequestBody("arg1") or ' .
                '@RequestBody(argumentName="arg1")'
            );
        }
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }
}