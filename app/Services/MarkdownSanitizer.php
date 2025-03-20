<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownSanitizer
{
    public static function sanitize(string $markdown): string
    {
        // Convert to HTML using CommonMark
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($markdown)->getContent();

        // Remove potentially harmful HTML elements and attributes
        $html = strip_tags($html, '<p><br><strong><em><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><pre><code><a><img>');

        // Remove SVG images or any suspicious URLs
        $html = preg_replace('/<img[^>]+src="[^"]*\.svg[^"]*"[^>]*>/i', '', $html);
        $html = preg_replace('/<a[^>]+href="javascript:[^"]*"[^>]*>/i', '', $html);

        return $html;
    }
}
