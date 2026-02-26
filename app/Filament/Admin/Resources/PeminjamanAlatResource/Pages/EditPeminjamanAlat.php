<?php

namespace App\Filament\Admin\Resources\PeminjamanAlatResource\Pages;

use App\Filament\Admin\Resources\PeminjamanAlatResource;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanAlat extends EditRecord
{
    protected static string $resource = PeminjamanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
