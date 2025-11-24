<?php
// src/Core/View.php

namespace Core;

class View
{
    public static function render($template, $data = [])
    {
        // Extract data to make variables available in the template
        extract($data);

        // Include the template file
        $templatePath = __DIR__ . '/../../templates/' . $template . '.php';

        if (file_exists($templatePath)) {
            include $templatePath;
        } else {
            echo "Template not found: $templatePath";
        }
    }
}
