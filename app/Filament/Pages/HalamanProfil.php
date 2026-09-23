<?php

namespace App\Filament\Pages;

use App\Models\Pegawai;
use App\Models\ProfilDinas;
use App\Support\KompresGambar;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Group;
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

    /** Jumlah tingkat di bawah pimpinan yang bisa disusun di bagan. */
    private const TINGKAT_MAKS = 5;

    public ?array $data = [];

    public function mount(): void
    {
        $jenis = [...self::DAFTAR, 'sambutan-kadis', 'sejarah', 'visi', 'tugas', 'bagan-organisasi', 'struktur-organisasi'];

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
                            ->maxSize(config('app.upload.gambar_maks_kb'))
                            ->saveUploadedFileUsing(KompresGambar::simpan(...))
                            ->helperText('Maksimal ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB per foto.'),
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
                        // Semua repeater di sini mulai kosong: item kosong yang wajib diisi akan menggagalkan simpan tab lain.
                        Group::make([
                            TextInput::make('nama')->label('Pimpinan')->placeholder('Kepala Dinas')->maxLength(100),
                            static::pejabat(),
                            static::anak(1)->columnSpanFull(),
                        ])->columns(2)->statePath('bagan_organisasi'),
                        FileUpload::make('struktur_organisasi')
                            ->label('Gambar bagan resmi (opsional)')
                            ->helperText('Ditautkan di bawah bagan, misalnya hasil pindai SK. Tampil sebagai pengganti bila bagan di atas kosong. Maksimal ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB.')
                            ->image()
                            ->disk('public')
                            ->directory('struktur-organisasi')
                            ->maxSize(config('app.upload.gambar_maks_kb'))
                            ->saveUploadedFileUsing(KompresGambar::simpan(...)),
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
                // Gambar bagan opsional: kalau dikosongkan jadi null, padahal kolom konten NOT NULL.
                : $isi ?? '';
            $baris->save();
        }

        Notification::make()->title('Isi halaman tersimpan')->success()->send();
    }

    /**
     * Unit di bawah pimpinan, tiap unit bisa punya sub unit lagi.
     * ponytail: skema Filament disusun di depan jadi kedalaman dibatasi TINGKAT_MAKS; naikkan bila bagan butuh lebih dalam.
     */
    private static function anak(int $tingkat): Repeater
    {
        $isi = [
            TextInput::make('nama')->label('Nama unit/jabatan')->required()->maxLength(100),
            static::pejabat(),
        ];

        // Letak hanya berlaku untuk unit langsung di bawah pimpinan; tingkat berikutnya selalu menurun.
        if ($tingkat === 1) {
            $isi[] = Select::make('posisi')
                ->label('Letak di bagan')
                ->options([
                    'lini' => 'Sejajar di bawah pimpinan (bidang)',
                    'staf' => 'Samping garis pimpinan (sekretariat)',
                    'bawah' => 'Paling bawah (UPTD)',
                ])
                ->default('lini')
                ->selectablePlaceholder(false);
        }

        if ($tingkat < self::TINGKAT_MAKS) {
            $isi[] = static::anak($tingkat + 1)->columnSpanFull();
        }

        return Repeater::make('anak')
            ->label($tingkat === 1 ? 'Unit di bawah pimpinan' : 'Sub unit')
            ->schema($isi)
            ->columns($tingkat === 1 ? 3 : 2)
            ->itemLabel(fn (array $state): ?string => $state['nama'] ?? null)
            ->collapsible()
            ->reorderable()
            ->defaultItems(0)
            ->addActionLabel($tingkat === 1 ? 'Tambah unit' : 'Tambah sub unit');
    }

    /** Disimpan sebagai id supaya nama di bagan ikut berubah bila data pegawai diedit. */
    private static function pejabat(): Select
    {
        return Select::make('pegawai_id')
            ->label('Pejabat (opsional)')
            ->options(fn () => once(fn () => Pegawai::orderBy('nama')->pluck('nama', 'id')->all()))
            ->searchable();
    }

    private static function kunci(string $jenis): string
    {
        return str_replace('-', '_', $jenis);
    }
}
