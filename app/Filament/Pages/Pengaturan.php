<?php

namespace App\Filament\Pages;

use App\Models\ProfilDinas;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
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
    private const KUNCI = ['telp', 'email', 'fb', 'ig', 'tt', 'yt'];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(ProfilDinas::pengaturan());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kontak')
                    ->description('Ditampilkan di bilah atas dan footer situs.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('telp')
                            ->label('Nomor telepon')
                            ->required(),
                        TextInput::make('email')
                            ->label('Alamat email')
                            ->email()
                            ->required(),
                    ]),
                Section::make('Media Sosial')
                    ->description('Kosongkan bila tautannya belum ada — ikonnya otomatis disembunyikan.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('fb')->label('Facebook')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('ig')->label('Instagram')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('tt')->label('TikTok')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('yt')->label('YouTube')->url()->prefixIcon(Heroicon::OutlinedLink),
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
