<script>
    // Jam WITA untuk semua halaman panel (topbar dan halaman login). Sama dengan waktu.ts di situs publik.
    (() => {
        const jam = document.querySelectorAll('[data-jam]');
        const sapaan = document.querySelectorAll('[data-sapaan]');

        if (!jam.length && !sapaan.length) return;

        const format = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' });

        const tik = () => {
            const b = Object.fromEntries(format.formatToParts(new Date()).map((p) => [p.type, p.value]));
            const j = Number(b.hour);
            const teks = j < 4 ? 'Selamat malam' : j < 11 ? 'Selamat pagi' : j < 15 ? 'Selamat siang' : j < 18 ? 'Selamat sore' : 'Selamat malam';

            // Topbar dirender ulang Livewire, jadi elemen dicari lagi setiap detik.
            document.querySelectorAll('[data-jam]').forEach((el) => (el.textContent = `${b.hour}.${b.minute}.${b.second}`));
            document.querySelectorAll('[data-sapaan]').forEach((el) => (el.textContent = teks));
        };

        setTimeout(() => { tik(); setInterval(tik, 1000); }, 1000 - (Date.now() % 1000));
    })();
</script>
