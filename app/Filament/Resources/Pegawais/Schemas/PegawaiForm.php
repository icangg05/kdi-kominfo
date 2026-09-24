<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use App\Filament\Resources\Jabatans\Schemas\JabatanForm;
use App\Support\KompresGambar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Js;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->placeholder('Contoh: Andi Pratama, S.Kom.')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP')
                    ->placeholder('Contoh: 198501012010011001')
                    ->maxLength(255),
                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->relationship('jabatan', 'nama')
                    ->placeholder('Pilih jabatan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm(fn (Schema $schema) => JabatanForm::configure($schema)),
                FileUpload::make('foto')
                    ->image()
                    ->disk('public')
                    ->directory('pegawai')
                    ->placeholder('Seret foto ke sini atau klik untuk memilih')
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([null, '1:1'])
                    // Cropper sudah bebas saat dibuka; ini hanya menandai tombol "Bebas" aktif setiap editor dibuka.
                    ->extraAlpineAttributes(['x-effect' => 'if (isEditorOpen) currentRatio = ' . Js::from(__('filament-forms::components.file_upload.editor.aspect_ratios.no_fixed.label'))])
                    ->maxSize(config('app.upload.gambar_maks_kb'))
                    ->saveUploadedFileUsing(KompresGambar::simpan(...))
                    ->helperText('Maksimal ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB.'),
            ]);
    }
}
