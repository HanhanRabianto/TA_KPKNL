{{-- $kartu: daftar [label, nilai, warna aksen (opsional)] --}}
<div class="grid">
    @foreach ($kartu as $k)
        <div class="card" style="border-left-color:{{ $k[2] ?? 'var(--bd)' }}">
            <div class="num" style="font-size:24px">{{ $k[1] }}</div>
            <div class="lbl">{{ $k[0] }}</div>
        </div>
    @endforeach
</div>
