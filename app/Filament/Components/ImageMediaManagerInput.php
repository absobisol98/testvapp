<?php

namespace App\Filament\Components;

use TomatoPHP\FilamentMediaManager\Form\MediaManagerInput;
use TomatoPHP\FilamentMediaManager\Form\FileInput;

class ImageMediaManagerInput extends MediaManagerInput
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Override the default schema to add file type validation
        $this->childComponents([
            FileInput::make('file')
                ->disk($this->diskName)
                ->required()
                ->storeFiles(false)
                ->collection($this->name)
                ->acceptedFileTypes([
                    'image/jpeg',
                    'image/jpg', 
                    'image/png',
                    'image/webp'
                ])
                ->maxSize(10240), // 10MB limit
        ]);
    }
}
