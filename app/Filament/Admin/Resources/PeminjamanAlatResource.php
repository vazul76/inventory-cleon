<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PeminjamanAlatResource\Pages;
use App\Models\PeminjamanAlat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PeminjamanAlatResource extends Resource
{
    protected static ?string $model = PeminjamanAlat::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'Peminjaman Alat';
    protected static ?string $modelLabel = 'Peminjaman Alat';
    protected static ?string $pluralModelLabel = 'Peminjaman Alat';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('alat_id')
                    ->label('Alat')
                    ->relationship('alat', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if ($state) {
                            $alat = \App\Models\Alat::find($state);
                            $set('max_jumlah', $alat->available ?? 0);
                        }
                    }),
                
                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah yang Dipinjam')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->live()
                    ->helperText(function (Forms\Get $get) {
                        $alatId = $get('alat_id');
                        if ($alatId) {
                            $alat = \App\Models\Alat::find($alatId);
                            return 'Tersedia: ' . ($alat->available ?? 0) . ' unit';
                        }
                        return '';
                    }),
                
                Forms\Components\TextInput::make('nama_peminjam')
                    ->label('Nama Peminjam (Tim)')
                    ->required()
                    ->placeholder('Misal: Tim 1, Tim Field, dll'),
                Forms\Components\DateTimePicker::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('tanggal_kembali')
                    ->label('Tanggal Kembali'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                    ])
                    ->default('dipinjam')
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alat.name')
                    ->label('Alat')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->alignCenter()
                    ->badge()
                    ->color('blue'),
                
                Tables\Columns\TextColumn::make('nama_peminjam')
                    ->label('Peminjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tgl Pinjam')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(80)
                    ->wrap(),
            ])
            ->defaultSort('tanggal_pinjam', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                    ]),
                Tables\Filters\Filter::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['tanggal'])) {
                            $query->whereDate('tanggal_pinjam', $data['tanggal']);
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamanAlats::route('/'),
        ];
    }
}
