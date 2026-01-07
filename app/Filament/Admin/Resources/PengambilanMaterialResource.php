<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengambilanMaterialResource\Pages;
use App\Models\PengambilanMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengambilanMaterialResource extends Resource
{
    protected static ?string $model = PengambilanMaterial::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pengambilan Material';
    protected static ?string $modelLabel = 'Pengambilan Material';
    protected static ?string $pluralModelLabel = 'Pengambilan Material';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nama_pengambil')
                    ->label('Nama Pengambil (Tim)')
                    ->required()
                    ->placeholder('Misal: Tim 1, Tim Field, dll'),
                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\DateTimePicker::make('tanggal_ambil')
                    ->label('Tanggal Ambil')
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('keperluan')
                    ->label('Keperluan')
                    ->rows(2),
                Forms\Components\Textarea::make('lokasi_pemasangan')
                    ->label('Lokasi Pemasangan')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('material.name')
                    ->label('Material')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pengambil')
                    ->label('Pengambil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->alignCenter()
                    ->badge()
                    ->color('blue'),
                Tables\Columns\TextColumn::make('tanggal_ambil')
                    ->label('Tanggal Ambil')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_pemasangan')
                    ->label('Lokasi')
                    ->limit(30),
            ])
            ->defaultSort('tanggal_ambil', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengambilanMaterials::route('/'),
            'create' => Pages\CreatePengambilanMaterial::route('/create'),
            'edit' => Pages\EditPengambilanMaterial::route('/{record}/edit'),
        ];
    }
}
