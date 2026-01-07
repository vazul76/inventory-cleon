<?php

namespace App\Filament\Admin\Resources\PengambilanMaterialResource\Pages;

use App\Filament\Admin\Resources\PengambilanMaterialResource;
use Filament\Resources\Pages\ListRecords;

class ListPengambilanMaterials extends ListRecords
{
    protected static string $resource = PengambilanMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
