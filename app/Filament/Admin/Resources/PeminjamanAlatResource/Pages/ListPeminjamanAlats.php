<?php

namespace App\Filament\Admin\Resources\PeminjamanAlatResource\Pages;

use App\Filament\Admin\Resources\PeminjamanAlatResource;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanAlats extends ListRecords
{
    protected static string $resource = PeminjamanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
