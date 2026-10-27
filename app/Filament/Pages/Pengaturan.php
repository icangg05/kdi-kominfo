<?php

namespace App\Filament\Pages;

use App\Models\ProfilDinas;
use App\Support\Telegram;
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
    private const KUNCI = ['telp', 'email', 'alamat', 'fb', 'ig', 'tt', 'yt', 'survei_aktif', 'survei_url', 'telegram_aktif', 'telegram_token', 'telegram_chat_id'];

    public ?array $data = [];

    public function mount(): void
    {
        $atur = ProfilDinas::pengaturan();
        $atur['telegram_token'] = Telegram::buka($atur['telegram_token'] ?? null);

        $this->form->fill($atur);
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
                    Tab::make('Notifikasi Telegram')->columns(2)->schema([
                        Text::make('Setiap pengajuan layanan baru diberitahukan ke chat, grup, atau channel Telegram ini. Buat bot lewat @BotFather, lalu tambahkan bot itu sebagai admin channel (atau anggota grup) yang boleh mengirim pesan.')->columnSpanFull(),
                        Toggle::make('telegram_aktif')
                            ->label('Kirim notifikasi pengajuan ke Telegram')
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('telegram_token')
                            ->label('Token bot')
                            ->placeholder('Contoh: 123456789:AAHk3...')
                            ->helperText('Diberikan @BotFather saat bot dibuat. Disimpan terenkripsi.')
                            ->password()
                            ->revealable()
                            ->regex('/^\d+:[\w-]{30,}$/')
                            ->validationMessages(['regex' => 'Format token bot tidak sesuai, mis. 123456789:AAHk3... dari @BotFather.'])
                            ->required(fn (Get $get): bool => (bool) $get('telegram_aktif')),
                        TextInput::make('telegram_chat_id')
                            ->label('ID chat atau channel')
                            ->placeholder('Contoh: -1001234567890 atau @namachannel')
                            ->helperText('Channel publik boleh memakai @nama; channel privat dan grup memakai ID angka (berawalan -100).')
                            ->regex('/^(-?\d+|@\w{5,})$/')
                            ->validationMessages(['regex' => 'Isi ID angka (mis. -1001234567890) atau nama channel berawalan @.'])
                            ->required(fn (Get $get): bool => (bool) $get('telegram_aktif')),
                        Actions::make([
                            Action::make('ujiTelegram')
                                ->label('Kirim pesan uji')
                                ->icon(Heroicon::OutlinedPaperAirplane)
                                ->color('gray')
                                ->action(fn () => $this->ujiTelegram()),
                        ])->columnSpanFull(),
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
        $nilai['telegram_token'] = Telegram::kunci($nilai['telegram_token'] ?? null);

        $baris = ProfilDinas::firstOrNew(['jenis' => 'pengaturan']);
        $baris->konten = array_map(
            fn (string $kunci) => ['id' => $kunci, 'value' => $nilai[$kunci] ?? null],
            self::KUNCI,
        );
        $baris->save();

        Notification::make()->title('Pengaturan tersimpan')->success()->send();
    }

    /** Mencoba token dan ID chat yang sedang diisi, sebelum disimpan. */
    private function ujiTelegram(): void
    {
        $token = trim((string) ($this->data['telegram_token'] ?? ''));
        $chat = trim((string) ($this->data['telegram_chat_id'] ?? ''));

        if ($token === '' || $chat === '') {
            Notification::make()->title('Isi token bot dan ID chat lebih dulu')->warning()->send();

            return;
        }

        $galat = Telegram::kirim($token, $chat, Telegram::pesanUji());

        $galat
            ? Notification::make()->title('Pesan uji gagal terkirim')->body($galat)->danger()->persistent()->send()
            : Notification::make()->title('Pesan uji terkirim')->body('Periksa chat Telegram tujuan. Jangan lupa simpan perubahan.')->success()->send();
    }
}
