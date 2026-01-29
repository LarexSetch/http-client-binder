<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Metadata\Dto\ClientMetadata;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\EndpointArgument;
use HttpClientBinder\Proxy\EndpointRenderer;
use HttpClientBinder\Utils\ClassNameResolver;

final class ProxySourceRender
{
    public function render(ClientMetadata $metadata): string
    {
        return
            strtr(
                $this->getClassBody(),
                [
                    '%CLASS_NAME%' => ClassNameResolver::resolve($metadata),
                    '%INTERFACE_NAMESPACE%' => $metadata->name,
                    '%METHODS_IMPLEMENTATION%' => $this->renderMethods($metadata),
                ]
            );
    }

    private function getClassBody(): string
    {
        return <<<END
<?php

declare(strict_types=1);

class %CLASS_NAME% implements %INTERFACE_NAMESPACE%
{
    public function __construct(
        private readonly \HttpClientBinder\RemoteCall\RemoteCall \$remoteCall,
        private readonly \HttpClientBinder\Proxy\RequestFactory\RequestFactory \$requestFactory,
        private readonly \HttpClientBinder\Proxy\ResultFactory\ResultFactory \$resultFactory,
    ) {
    }

%METHODS_IMPLEMENTATION%
}
END;
    }

    private function renderMethods(ClientMetadata $metadata): string
    {
        return
            implode(
                "\n\n",
                array_map(
                    function (Endpoint $endpoint) {
                        return
                            strtr(
                                $this->getMethodBody(),
                                [
                                    '%METHOD_NAME%' => $endpoint->name,
                                    '%RETURN_TYPE%' => $endpoint->resultType,
                                    '%METHOD_ARGUMENTS%' => implode(
                                        ", ",
                                        array_map(
                                            function (EndpointArgument $argument) {
                                                return sprintf("%s $%s", $argument->type, $argument->name);
                                            },
                                            $endpoint->arguments
                                        )
                                    ),
                                    '%PROTOCOL_ARGUMENTS%' => implode(
                                        ", ",
                                        array_map(
                                            function (EndpointArgument $argument) {
                                                return sprintf("$%s", $argument->name);
                                            },
                                            $endpoint->arguments
                                        )
                                    ),
                                    '%ENDPOINT%' => EndpointRenderer::render($endpoint),
                                ]
                            );
                    },
                    $metadata->endpoints
                )
            );
    }

    private function getMethodBody(): string
    {
        return <<<END
    public function %METHOD_NAME%(%METHOD_ARGUMENTS%): %RETURN_TYPE%
    {
        \$endpoint = %ENDPOINT%;
        \$request = \$this->requestFactory->create(\$endpoint, func_get_args());
        \$response = \$this->remoteCall->invoke(\$request);

        return \$this->resultFactory->create(\$endpoint, \$response);
    }
END;
    }
}