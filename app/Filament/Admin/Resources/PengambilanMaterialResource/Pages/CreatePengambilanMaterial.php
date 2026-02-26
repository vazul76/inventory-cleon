<?php

namespace App\Filament\Admin\Resources\PengambilanMaterialResource\Pages;

use App\Filament\Admin\Resources\PengambilanMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePengambilanMaterial extends CreateRecord
{
    protected static string $resource = PengambilanMaterialResource::class;

    protected function afterCreate(): void
    {
        $this->record->material->kurangiStock($this->record->jumlah);
    }
}
