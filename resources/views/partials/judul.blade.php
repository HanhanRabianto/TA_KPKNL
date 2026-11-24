{{-- $judul, $sub, $aksi (HTML tombol, opsional) --}}
<div class="bar no-print">
    <div class="head" style="margin:0">
        <h1>{{ $judul }}</h1>
        <div class="note">{{ $sub ?? '' }}</div>
    </div>
    @isset($aksi) <div>{!! $aksi !!}</div> @endisset
</div>
