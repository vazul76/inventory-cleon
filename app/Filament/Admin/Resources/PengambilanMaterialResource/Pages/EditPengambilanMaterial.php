<?php

namespace App\Filament\Admin\Resources\PengambilanMaterialResource\Pages;

use App\Filament\Admin\Resources\PengambilanMaterialResource;
use Filament\Resources\Pages\EditRecord;

class EditPengambilanMaterial extends EditRecord
{
    protected static string $resource = PengambilanMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
