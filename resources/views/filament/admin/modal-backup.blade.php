@php
    use App\Support\BackupDatabase;
    use Filament\Support\Icons\Heroicon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Number;
@endphp

{{-- Konfirmasi menu Backup Database: item menunya menunjuk ke #backup-database, kliknya dicegat di bawah. --}}
@auth
    <x-filament::modal
        id="backup-database"
        :icon="Heroicon::OutlinedCircleStack"
        heading="Backup Database"
        description="Seluruh isi database diunduh sebagai berkas .sql. Berkas upload (gambar, dokumen) tidak ikut."
        width="md"
    >
        <dl class="kdi-info-backup">
            <div>
                <dt>Nama database</dt>
                <dd>{{ DB::connection()->getDatabaseName() }}</dd>
            </div>
            <div>
                <dt>Ukuran</dt>
                <dd>{{ Number::withLocale(app()->getLocale(), fn () => Number::fileSize(BackupDatabase::ukuran(), maxPrecision: 1)) }}</dd>
            </div>
        </dl>

        <x-slot name="footerActions">
            <x-filament::button
                tag="a"
                :href="route('filament.admin.backup-database')"
                :icon="Heroicon::OutlinedArrowDownTray"
                x-on:click="$dispatch('close-modal', { id: 'backup-database' })"
            >
                Unduh
            </x-filament::button>
            <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'backup-database' })">
                Batal
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <script>
        document.addEventListener('click', (e) => {
            if (!e.target.closest('a[href="#backup-database"]')) return;
            e.preventDefault();
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'backup-database' } }));
        });
    </script>
@endauth
