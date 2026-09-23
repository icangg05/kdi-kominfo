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
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

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

    /** Jumlah tingkat di bawah pimpinan: unit lalu sub unit (pimpinan => unit => sub unit). */
    private const TINGKAT_MAKS = 2;

    private const FOTO_KANTOR_MAKS = 3;

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
                            ->maxFiles(self::FOTO_KANTOR_MAKS)
                            ->reorderable()
                            // Tanpa ini Filament menampilkan foto terbalik dari urutan simpan (terbaru di depan),
                            // jadi urutan hasil seret di admin berkebalikan dengan halaman depan.
                            ->appendFiles()
                            // Pratinjau berjajar, bukan satu foto per baris.
                            ->panelLayout('grid')
                            ->imagePreviewHeight('200')
                            ->extraAttributes(['class' => 'kdi-foto-tambahan'])
                            ->disk('public')
                            ->directory('foto-diskominfo')
                            ->maxSize(config('app.upload.gambar_maks_kb'))
                            ->saveUploadedFileUsing(KompresGambar::simpan(...))
                            ->helperText('Maksimal ' . self::FOTO_KANTOR_MAKS . ' foto, ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB per foto. Foto pertama tampil paling besar; seret untuk mengubah urutan.'),
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
                        // Tampil sebagai pohon: garis cabang dan chip tingkat ada di gaya.blade.php (.kdi-bagan-*).
                        Group::make([
                            Fieldset::make('Pimpinan')
                                ->schema([
                                    TextInput::make('nama')->label('Nama jabatan')->placeholder('Kepala Dinas')->maxLength(100),
                                    static::pejabat(),
                                ])
                                ->extraAttributes(['class' => 'kdi-bagan-akar']),
                            static::anak(1),
                        ])->statePath('bagan_organisasi'),
                        FileUpload::make('struktur_organisasi')
                            ->label('Bagan resmi (opsional)')
                            ->helperText('Gambar atau PDF, misalnya hasil pindai SK. Ditautkan di bawah bagan; gambar tampil sebagai pengganti bila bagan di atas kosong. Maksimal ' . (config('app.upload.gambar_maks_kb') / 1024) . ' MB, gambar otomatis dikompres.')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
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
     * Kedalaman dibatasi TINGKAT_MAKS; tingkat terakhir tidak punya repeater sub unit lagi.
     */
    private static function anak(int $tingkat): Repeater
    {
        $isi = [
            // live saat blur supaya judul kartu ikut berganti dari "Belum dinamai".
            TextInput::make('nama')
                ->label('Nama unit/jabatan')
                ->placeholder($tingkat === 1 ? 'Sekretariat' : 'Sub Bagian Umum')
                ->required()
                ->maxLength(100)
                ->live(onBlur: true),
            static::pejabat(),
        ];

        // Letak hanya berlaku untuk unit langsung di bawah pimpinan; tingkat berikutnya selalu menurun.
        if ($tingkat === 1) {
            $isi[] = Select::make('posisi')
                ->label('Letak di bagan')
                ->options([
                    'lini' => 'Sejajar di bawah pimpinan',
                    'staf' => 'Samping garis pimpinan',
                ])
                ->default('lini')
                ->selectablePlaceholder(false);
        }

        if ($tingkat < self::TINGKAT_MAKS) {
            $isi[] = static::anak($tingkat + 1)->columnSpanFull();
        }

        return Repeater::make('anak')
            ->label($tingkat === 1 ? 'Unit di bawah pimpinan' : 'Sub unit')
            // Judul diganti chip tingkat di tiap item dan garis cabang dari induknya.
            ->hiddenLabel()
            ->schema($isi)
            ->columns($tingkat === 1 ? 3 : 2)
            ->itemLabel(fn (array $state): HtmlString => static::judulSimpul($tingkat, $state))
            // Nama panjang dibungkus, bukan dipotong: di ponsel chip tingkat menyisakan sedikit ruang.
            ->truncateItemLabel(false)
            ->extraAttributes(['class' => "kdi-bagan-tingkat kdi-bagan-tingkat-{$tingkat}"])
            ->collapsible()
            ->reorderable()
            ->defaultItems(0)
            ->addActionLabel($tingkat === 1 ? 'Tambah unit' : 'Tambah sub unit')
            ->addActionAlignment(Alignment::Start)
            // Tombol tambah makin ringan di tingkat yang lebih dalam.
            ->addAction(fn (Action $action): Action => $action->icon(Heroicon::Plus)->when($tingkat > 1, fn (Action $a) => $a->link()));
    }

    /** Judul item repeater: chip tingkat, nama unit, dan jumlah sub unit (berguna saat kartu dilipat). */
    private static function judulSimpul(int $tingkat, array $state): HtmlString
    {
        $nama = filled($state['nama'] ?? null)
            ? e($state['nama'])
            : '<span class="kdi-bagan-kosong">Belum dinamai</span>';
        $jumlah = count($state['anak'] ?? []);

        return new HtmlString(
            "<span class=\"kdi-bagan-chip kdi-bagan-chip-{$tingkat}\">" . ($tingkat === 1 ? 'Unit' : 'Sub unit') . '</span> ' . $nama
            . ($jumlah ? "<span class=\"kdi-bagan-jumlah\">{$jumlah} sub unit</span>" : ''),
        );
    }

    /** Disimpan sebagai id supaya nama di bagan ikut berubah bila data pegawai diedit. */
    private static function pejabat(): Select
    {
        return Select::make('pegawai_id')
            ->label('Pejabat (opsional)')
            ->options(fn () => once(fn () => Pegawai::orderBy('nama')->pluck('nama', 'id')->all()))
            ->searchable()
            // Satu pegawai untuk satu jabatan: yang sudah dipilih di kotak lain tidak bisa dipilih lagi,
            // dan aturan `in` bawaan Select menolaknya saat simpan.
            ->disableOptionWhen(fn (string $value, mixed $state, self $livewire): bool => static::jumlahPejabat($livewire, $value) > (int) ($value === (string) $state))
            ->validationMessages(['in' => 'Pegawai ini sudah menjabat di kotak lain. Satu pegawai hanya untuk satu jabatan.']);
    }

    /** Berapa kotak di seluruh bagan (pimpinan, unit, sub unit) yang memilih pegawai ini sebagai pejabat. */
    private static function jumlahPejabat(self $livewire, string $pegawaiId): int
    {
        return collect(Arr::dot($livewire->data['bagan_organisasi'] ?? []))
            ->filter(fn ($id, string $kunci): bool => str_ends_with(".{$kunci}", '.pegawai_id') && (string) $id === $pegawaiId)
            ->count();
    }

    private static function kunci(string $jenis): string
    {
        return str_replace('-', '_', $jenis);
    }
}
