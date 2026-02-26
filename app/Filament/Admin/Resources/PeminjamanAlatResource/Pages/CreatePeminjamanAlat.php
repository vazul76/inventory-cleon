<?php

namespace App\Filament\Admin\Resources\PeminjamanAlatResource\Pages;

use App\Filament\Admin\Resources\PeminjamanAlatResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjamanAlat extends CreateRecord
{
    protected static string $resource = PeminjamanAlatResource::class;

    protected function afterCreate(): void
    {
        $this->record->alat->kurangiAvailable($this->record->jumlah);
    }
}
