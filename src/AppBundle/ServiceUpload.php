<?php
namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ServiceUpload
{
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }
    
    
    public function upload(UploadedFile $file)
    {
        $filename = $this->assignName($file);
        $file->move($this->getTargetDir(), $filename);
        return $filename;
    }

    public function assignName(UploadedFile $file)
    {
        $extension = '.' . $file->guessExtension();
        $baseName = str_replace($extension, '', $file->getClientOriginalName());
        $fileName = $baseName .time(). $extension;
        return $fileName;
    }
    
    private function slugify($string, $replace = array(), $delimiter = '-')
    {
        if (!extension_loaded('iconv')) {
            throw new Exception('iconv module not loaded');
        }
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        if (!empty($replace)) {
            $clean = str_replace((array) $replace, ' ', $clean);
        }
        $clean = preg_replace("/[^a-zA-Z0-9_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        return $clean;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
