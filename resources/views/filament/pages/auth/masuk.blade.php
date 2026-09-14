<div class="masuk-kotak">
    <header class="masuk-judul">
        <h1>{{ $this->getHeading() }}</h1>

        @if (filled($subheading = $this->getSubheading()))
            <p>{{ $subheading }}</p>
        @endif
    </header>

    {{ $this->content }}

    <p class="masuk-catatan">
        Lupa kata sandi atau akun terkunci? Hubungi pengelola sistem Diskominfo Kota Kendari.
    </p>

    <x-filament-actions::modals />
</div>
