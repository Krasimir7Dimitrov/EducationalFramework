<?php

namespace App\System\Debugbar;

interface DebugDataInterface
{
    public function getBaseUrl(): string;

    public function getIp(): string;

    public function getUserSession(): array;

    public function getController(): string;

    public function getAction(): string;

    public function getMemoryUsed(): int;

    public function getHttpMethod(): string;

    public function getQueryString(): string;

    public function getRequestData(): array;

    public function getExecutionTime(): float;

}