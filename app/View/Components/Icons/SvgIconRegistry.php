<?php

declare(strict_types=1);

namespace App\View\Components\Icons;

use Illuminate\Support\Str;

/**
 * Tracks the SVG icons used while rendering the page so that subsequent renders
 * can emit a reference to the earlier rendered icon.
 *
 * @method static bool isFirstRender(string $filename)
 */
final class SvgIconRegistry
{
    private const ICON_DIRECTORY = 'public_html/images/icons';
    private const BLADE_DIRECTORY = 'app/generated';
    private const VIEW_PARENT_PATH = 'svg-icon';

    public static function getBladePath(): string
    {
        return storage_path(self::BLADE_DIRECTORY);
    }

    protected array $used = [];

    protected array $compiled = [];

    /**
     * Returns true if the icon with this filename has not been rendered yet.
     *
     * @param string $filename The filename of the icon to check.
     * @return bool
     */
    public function isFirstRender(string $filename): bool
    {
        $firstRender = !isset($this->used[$filename]);
        $this->used[$filename] = true;
        return $firstRender;
    }

    /**
     * Returns the name of the view that renders the icon with this filename.
     *
     * If the view has not been compiled yet, or is out-of-date, it will be
     * compiled and saved to the icon-cache directory.
     *
     * @param string $filename The filename of the icon to get the view name for.
     * @return string The name of the view that renders the icon.
     */
    public function getViewName(string $filename): string
    {
        if (isset($this->compiled[$filename])) {
            return $this->compiled[$filename];
        }

        $sourcePath = base_path(implode(DIRECTORY_SEPARATOR, [self::ICON_DIRECTORY, "{$filename}.svg"]));

        $viewId = Str::slug($filename);
        $compiledPath = storage_path(implode(DIRECTORY_SEPARATOR, [self::BLADE_DIRECTORY, self::VIEW_PARENT_PATH, "{$viewId}.blade.php"]));

        if (!file_exists($sourcePath)) {
            throw new \RuntimeException("SVG icon not found at {$sourcePath} for {$filename}");
        }

        if (!file_exists($compiledPath) || (filemtime($sourcePath) > filemtime($compiledPath))) {
            $svg = file_get_contents($sourcePath);
            $compiled = $this->transformSvgToBlade($svg, $viewId);

            @mkdir(dirname($compiledPath), 0777, true);
            file_put_contents($compiledPath, $compiled);
        }

        $this->compiled[$filename] = implode('.', [self::VIEW_PARENT_PATH, $viewId]);

        return $this->compiled[$filename];
    }

    protected function buildSvgAttributeString(array $attributes): string
    {
        $attributes = array_filter($attributes);
        if (empty($attributes)) {
            return '';
        }

        return ' ' . implode(' ', array_map(
            fn($key, $value) => $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"',
            array_keys($attributes),
            $attributes,
        ));
    }

    /**
     * Transforms the SVG markup into a Blade view that renders the icon.
     *
     * @param string $svg The SVG markup to transform.
     * @param string $viewId The ID of the view that will render the icon.
     * @return string The Blade view that renders the icon.
     */
    protected function transformSvgToBlade(string $svg, string $viewId): string
    {
        $dom = new \DOMDocument();
        $dom->loadXML($svg);

        $svgElement = $dom->documentElement;

        // Attributes to use on uses of the SVG element.
        $width = $svgElement->getAttribute('width');
        $height = $svgElement->getAttribute('height');

        // Attributes to copy to the SVG symbol
        $viewBox = $svgElement->getAttribute('viewBox');
        $preserveAspectRatio = $svgElement->getAttribute('preserveAspectRatio');

        // Extract title if present to use as a default title for the icon.
        $titleElement = $svgElement->getElementsByTagName('title')->item(0);
        $title = $titleElement ? $titleElement->textContent : '';

        // Extract the aria-label if present to use as a default label for the icon.
        $label = $svgElement->getAttribute('aria-label');

        $output = "@php \$title ??= '" . addslashes($title) . "'; @endphp\n";
        $output .= "@php \$titleId = '$viewId-' . uniqid(); @endphp\n";
        $output .= "@php \$label ??= '" . addslashes($label) . "'; @endphp\n";

        $output .= '<svg xmlns="http://www.w3.org/2000/svg" version="2.0" role="img"';
        $output .= $this->buildSvgAttributeString([
            'width' => $width,
            'height' => $height,
        ]) . "\n";

        $output .= "  @if (!empty(\$label)) aria-label=\"{{ \$label }}\" @endif\n";
        $output .= "  @if (!empty(\$title)) aria-describedby=\"{{ \$titleId }}\" @endif\n";
        $output .= ">\n";

        $output .= "  @if (!empty(\$title))\n";
        $output .= "    <title id=\"{{ \$titleId }}\">{{ \$title }}</title>\n";
        $output .= "  @endif\n";

        $output .= "  @if (\$firstRender)\n";
        $output .= "  <defs>\n";
        $output .= "    <symbol id=\"svg-icon-$viewId\"";
        $output .= $this->buildSvgAttributeString([
            'viewBox' => $viewBox,
            'preserveAspectRatio' => $preserveAspectRatio,
        ]);
        $output .= ">\n";

        // Add all child nodes except title
        foreach ($svgElement->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->tagName === 'title') {
                continue;
            }

            $content = mb_trim($child->ownerDocument->saveXML($child));
            if (!empty($content)) {
                $output .= "      $content\n";
            }
        }

        $output .= "    </symbol>\n";
        $output .= "  </defs>\n";
        $output .= "  @endif\n";
        $output .= "  <use href=\"#svg-icon-$viewId\"/>\n";
        $output .= "</svg>\n";

        return $output;
    }
}
