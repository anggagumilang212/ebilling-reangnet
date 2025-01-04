<?php
namespace App\Helpers;
use App\Models\TemplateNotifikasi;

class MessageHelper
{
    public static function processTemplate($kategori, $data)
    {
        $template = TemplateNotifikasi::where('kategori', $kategori)->first();

        if (!$template) {
            return null;
        }

        $message = $template->template_pesan;

        // Replace placeholders dengan data aktual
        foreach ($data as $key => $value) {
            $message = str_replace("{{" . $key . "}}", $value, $message);
        }

        return $message;
    }
}
