<?php
declare(strict_types=1);

namespace Lcobucci\ContentNegotiation\Formatter;

use Lcobucci\ContentNegotiation\ContentCouldNotBeFormatted;
use League\Plates\Engine;
use Throwable;

final class Plates extends ContentOnly
{
    private const DEFAULT_ATTRIBUTE = 'template';

    private Engine $engine;
    private string $attributeName;

    public function __construct(Engine $engine, string $attributeName = self::DEFAULT_ATTRIBUTE)
    {
        $this->engine        = $engine;
        $this->attributeName = $attributeName;
    }

    /**
     * {@inheritdoc}
     */
    public function formatContent($content, array $attributes = []): string
    {
        try {
            return $this->render($content, $attributes);
        } catch (Throwable $exception) {
            throw new ContentCouldNotBeFormatted(
                'An error occurred while formatting using plates',
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param mixed   $content
     * @param mixed[] $attributes
     *
     * @throws Throwable
     */
    private function render($content, array $attributes = []): string
    {
        $template = $attributes[$this->attributeName] ?? '';

        return $this->engine->render($template, ['content' => $content]);
    }
}
