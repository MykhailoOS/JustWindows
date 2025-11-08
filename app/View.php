<?php
declare(strict_types=1);

namespace App;

final class View
{
    public static function render(string $template, array $data = [], ?string $layout = 'layouts/main.php'): string
    {
        extract($data);
        
        ob_start();
        require __DIR__.'/Views/'.$template;
        $content = ob_get_clean();
        
        if ($layout) {
            ob_start();
            require __DIR__.'/Views/'.$layout;
            return (string) ob_get_clean();
        }
        
        return $content;
    }
}
