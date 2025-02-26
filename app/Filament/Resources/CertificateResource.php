<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Certificates';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ValidateCertificate::route('/'),
        ];
    }
}
