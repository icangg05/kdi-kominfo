<?php

namespace App\Filament\Pages;

use App\Models\ProfilDinas;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Mengelola isi halaman statis (beranda, tentang kami, tupoksi, struktur organisasi)
 * yang semuanya tersimpan di tabel `profil_dinas` sebagai pasangan jenis => konten.
 */
class HalamanProfil extends Page
{
    protected string $view = 'filament.pages.halaman-profil';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static string|\UnitEnum|null $navigationGroup = 'Profil Dinas';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Isi Halaman';

    protected static ?string $navigationLabel = 'Isi Halaman';

    /** Jenis yang tersimpan sebagai daftar [{id, value}] — sisanya HTML biasa. */
    private const DAFTAR = ['tagline-sambutan', 'misi', 'fungsi', 'foto-diskominfo'];

    public ?array $data = [];

    public function mount(): void
    {
        $jenis = [...self::DAFTAR, 'sambutan-kadis', 'sejarah', 'visi', 'tugas', 'struktur-organisasi'];

        $this->form->fill(collect($jenis)->mapWithKeys(fn (string $j) => [
            static::kunci($j) => in_array($j, self::DAFTAR, true)
                ? ProfilDinas::nilai($j)
                : ProfilDinas::konten($j),
        ])->all());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Konten')->tabs([
                    Tab::make('Beranda')->schema([
                        RichEditor::make('sambutan_kadis')
                            ->label('Sambutan Kepala Dinas')
                            ->required(),
                        Repeater::make('tagline_sambutan')
                            ->label('Tagline')
                            ->simple(TextInput::make('value')->required()->maxLength(60))
                            ->addActionLabel('Tambah tagline')
                            ->reorderable(),
                    ]),
                    Tab::make('Tentang Kami')->schema([
                        RichEditor::make('sejarah')->label('Sejarah')->required(),
                        Textarea::make('visi')->label('Visi')->rows(3)->required(),
                        Repeater::make('misi')
                            ->label('Misi')
                            ->simple(Textarea::make('value')->rows(2)->required())
                            ->addActionLabel('Tambah misi')
                            ->reorderable(),
                        FileUpload::make('foto_diskominfo')
                            ->label('Foto kantor')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('foto-diskominfo')
                            ->maxSize(4096),
                    ]),
                    Tab::make('Tupoksi')->schema([
                        RichEditor::make('tugas')->label('Tugas')->required(),
                        Repeater::make('fungsi')
                            ->label('Fungsi')
                            ->simple(Textarea::make('value')->rows(2)->required())
                            ->addActionLabel('Tambah fungsi')
                            ->reorderable(),
                    ]),
                    Tab::make('Struktur Organisasi')->schema([
                        FileUpload::make('struktur_organisasi')
                            ->label('Bagan struktur organisasi')
                            ->image()
                            ->disk('public')
                            ->directory('struktur-organisasi')
                            ->maxSize(8192),
                    ]),
                ])->columnSpanFull(),
                Actions::make([
                    Action::make('save')->label('Simpan perubahan')->submit('save'),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $nilai = $this->form->getState();

        foreach ($nilai as $kunci => $isi) {
            $jenis = str_replace('_', '-', $kunci);

            $baris = ProfilDinas::firstOrNew(['jenis' => $jenis]);
            $baris->konten = in_array($jenis, self::DAFTAR, true)
                ? collect((array) $isi)->values()->map(fn ($v, $i) => ['id' => $i + 1, 'value' => $v])->all()
                : $isi;
            $baris->save();
        }

        Notification::make()->title('Isi halaman tersimpan')->success()->send();
    }

    private static function kunci(string $jenis): string
    {
        return str_replace('-', '_', $jenis);
    }
}
