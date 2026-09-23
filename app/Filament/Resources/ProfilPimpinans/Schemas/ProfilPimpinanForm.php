<?php

namespace App\Filament\Resources\ProfilPimpinans\Schemas;

use App\Filament\Resources\Pegawais\PegawaiResource;
use App\Models\Pegawai;
use App\Support\KompresGambar;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ProfilPimpinanForm
{
    private const FOTO_MAKS = 3;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Image::make(fn (Get $get) => static::fotoPegawai($get('pegawai_id')), 'Foto profil pegawai')
                        ->imageWidth('6rem')
                        ->imageHeight('7.5rem')
                        ->extraAttributes(['class' => 'kdi-foto-pegawai'])
                        ->grow(false),
                    Select::make('pegawai_id')
                        ->label('Pimpinan')
                        ->relationship('pegawai', 'nama', fn (Builder $query) => $query->with('jabatan'))
                        ->getOptionLabelFromRecordUsing(fn (Pegawai $pegawai) => "{$pegawai->nama} ({$pegawai->jabatan?->nama})")
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        // Tab baru supaya isian profil yang belum disimpan tidak hilang.
                        ->hintAction(
                            Action::make('editPegawai')
                                ->label('Edit data pegawai')
                                ->icon(Heroicon::OutlinedPencilSquare)
                                ->url(fn ($state) => $state ? PegawaiResource::getUrl('edit', ['record' => $state]) : null)
                                ->openUrlInNewTab()
                                ->visible(fn ($state) => filled($state)),
                        )
                        ->helperText('Nama, jabatan, dan foto utama di situs diambil dari data pegawai. Ubah lewat tautan "Edit data pegawai".'),
                ])->from('sm')->columnSpanFull(),
                FileUpload::make('foto')
                    ->label('Foto tambahan')
                    ->image()
                    ->multiple()
                    ->maxFiles(self::FOTO_MAKS)
                    ->reorderable()
                    ->panelLayout('grid')
                    ->imagePreviewHeight('200')
                    ->extraAttributes(['class' => 'kdi-foto-tambahan'])
                    ->disk('public')
                    ->directory('foto-kadis')
                    ->maxSize(config('app.upload.gambar_maks_kb'))
                    ->saveUploadedFileUsing(KompresGambar::simpan(...))
                    ->columnSpanFull()
                    ->helperText('Tampil di halaman profil pimpinan. Maksimal ' . self::FOTO_MAKS . ' foto, ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB per foto.'),
                RichEditor::make('konten')
                    ->label('Profil')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    private static function fotoPegawai(mixed $pegawaiId): string
    {
        $foto = $pegawaiId ? Pegawai::find($pegawaiId)?->foto : null;

        return $foto && Storage::disk('public')->exists($foto) ? "/storage/{$foto}" : '/img/gambar-default.webp';
    }
}
