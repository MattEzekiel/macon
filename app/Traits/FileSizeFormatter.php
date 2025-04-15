<?php

namespace App\Traits;

trait FileSizeFormatter
{
    public function formatFileSize($size)
    {
        $unit = 'B';

        switch (true) {
            case $size >= 1024 * 1024 * 1024:
                $size = $size / (1024 * 1024 * 1024);
                $unit = 'GB';
                break;
            case $size >= 1024 * 1024:
                $size = $size / (1024 * 1024);
                $unit = 'MB';
                break;
            case $size >= 1024:
                $size = $size / 1024;
                $unit = 'KB';
                break;
        }

        return number_format($size, 2).' '.$unit;
    }
}
