<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Method\Dto\Argument;
use HttpClientBinder\Method\Dto\Method;
use HttpClientBinder\Proxy\Dto\RenderData;

final class ProxySourceRender implements SourceRenderInterface
{
    public function render(RenderData $renderData): string
    {
        return
            strtr(
                $this->getClassBody(),
                [
                    '%CLASS_NAME%' => $renderData->getClassName(),
                    '%INTERFACE_NAMESPACE%' => $renderData->getInterfaceName(),
                    '%JSON_MAPPINGS%' => $renderData->getJsonMappings(),
                    '%METHODS_IMPLEMENTATION%' => $this->renderMethods($renderData)
                ]
            );
    }

    private function getClassBody(): string
    {
        return <<<END
<?php

declare(strict_types=1);

use HttpClientBinder\Protocol\MagicProtocolFactory;
use JMS\Serializer\SerializerInterface;

class %CLASS_NAME% implements %INTERFACE_NAMESPACE%
{
    /**
     * @var HttpClientBinder\Protocol\MagicProtocol
     */
    private \$protocol;

    public function __construct(SerializerInterface \$serializer) {
        \$this->protocol = (new MagicProtocolFactory(\$serializer))->build('%JSON_MAPPINGS%');
    }

%METHODS_IMPLEMENTATION%
}
END;
    }

    private function renderMethods(RenderData $renderData): string
    {
        return
            implode("\n\n", array_map(
                function (Method $method) {
                    return
                        strtr(
                            $this->getMethodBody(),
                            [
                                '%METHOD_NAME%' => $method->getName(),
                                '%RETURN_TYPE%' => $method->getResponseType(),
                                '%METHOD_ARGUMENTS%' => implode(", ", array_map(
                                    function (Argument $argument) {
                                        return sprintf("%s $%s", $argument->getType(), $argument->getName());
                                    },
                                    $method->getArguments()
                                )),
                                '%PROTOCOL_ARGUMENTS%' => implode(", ", array_map(
                                    function (Argument $argument) {
                                        return sprintf("$%s", $argument->getName());
                                    },
                                    $method->getArguments()
                                ))
                            ]
                        );
                },
                $renderData->getMethods()
            ));
    }

    private function getMethodBody(): string
    {
        return <<<END
    public function %METHOD_NAME%(%METHOD_ARGUMENTS%): %RETURN_TYPE%
    {
        return \$this->protocol->%METHOD_NAME%(%PROTOCOL_ARGUMENTS%);
    }
END;
    }
}