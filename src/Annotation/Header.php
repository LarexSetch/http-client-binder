<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
final class Header
{
    /**
     * @var string
     * @Required
     */
    private $header;

    /**
     * @var string[]
     * @Required
     */
    private $values;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->header = $values['value'];
        }

        if (!isset($values['values'])) {
            throw $this->createException();
        }

        if (is_array($values['values'])) {
            $this->values = $values['values'];
        } else {
            $this->values = [$values['values']];
        }

        $this->checkHeader();
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    private function checkHeader(): void
    {
        if (null === $this->header || null === $this->values) {
            throw $this->createException();
        }
    }

    private function createException(): InvalidArgumentException
    {
        return
            new InvalidArgumentException(
                'You must register header as ' .
                '@Header("Content-type", values={"application/json"}) or' .
                '@Header("Content-type", values="application/json")');
    }
}