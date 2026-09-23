<?php

namespace App\Filament\Pages;

use App\Models\ProfilDinas;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Pengaturan extends Page
{
    protected string $view = 'filament.pages.pengaturan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    /** Urutan kunci di dalam kolom `konten` milik baris `pengaturan`. */
    private const KUNCI = ['telp', 'email', 'alamat', 'fb', 'ig', 'tt', 'yt', 'survei_aktif', 'survei_url'];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(ProfilDinas::pengaturan());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Pengaturan')->tabs([
                    Tab::make('Kontak')->columns(2)->schema([
                        Text::make('Ditampilkan di bilah atas dan footer situs.')->columnSpanFull(),
                        TextInput::make('telp')
                            ->label('Nomor telepon')
                            ->placeholder('Contoh: 0822-1234-5678')
                            ->required(),
                        TextInput::make('email')
                            ->label('Alamat email')
                            ->placeholder('Contoh: diskominfo@kendarikota.go.id')
                            ->email()
                            ->required(),
                        TextInput::make('alamat')
                            ->label('Alamat kantor')
                            ->placeholder('Contoh: Jl. Nama Jalan No. 1, Kendari, Sulawesi Tenggara')
                            ->helperText('Hanya tampil di footer.')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                    Tab::make('Media Sosial')->columns(2)->schema([
                        Text::make('Kosongkan bila tautannya belum ada, nanti ikonnya otomatis disembunyikan di situs.')->columnSpanFull(),
                        TextInput::make('fb')->label('Facebook')->placeholder('https://facebook.com/namaakun')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('ig')->label('Instagram')->placeholder('https://instagram.com/namaakun')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('tt')->label('TikTok')->placeholder('https://tiktok.com/@namaakun')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('yt')->label('YouTube')->placeholder('https://youtube.com/@namakanal')->url()->prefixIcon(Heroicon::OutlinedLink),
                    ]),
                    Tab::make('Survei Kepuasan')->schema([
                        Text::make('Tombol "Survei Kepuasan" di situs membuka tautan ini dalam jendela di atas halaman.'),
                        Toggle::make('survei_aktif')
                            ->label('Tampilkan tombol Survei Kepuasan')
                            ->live(),
                        TextInput::make('survei_url')
                            ->label('Tautan survei')
                            ->placeholder('https://surveidigital.spbe.go.id/embed/survey/...')
                            ->url()
                            ->required(fn (Get $get): bool => (bool) $get('survei_aktif'))
                            ->prefixIcon(Heroicon::OutlinedLink),
                    ]),
                ]),
                Actions::make([
                    Action::make('save')->label('Simpan perubahan')->submit('save'),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $nilai = $this->form->getState();

        $baris = ProfilDinas::firstOrNew(['jenis' => 'pengaturan']);
        $baris->konten = array_map(
            fn (string $kunci) => ['id' => $kunci, 'value' => $nilai[$kunci] ?? null],
            self::KUNCI,
        );
        $baris->save();

        Notification::make()->title('Pengaturan tersimpan')->success()->send();
    }
}
