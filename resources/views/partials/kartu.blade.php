{{-- $kartu: daftar [label, nilai, warna kotak ikon (opsional, mis. #E5C97A)] --}}
<div class="cards">
    @foreach ($kartu as $k)
        <div class="card st">
            <span class="ico"@if (! empty($k[2])) style="background:{{ $k[2] }}33"@endif>{!! \App\Support\Ikon::svg(\App\Support\Ikon::untuk($k[0])) !!}</span>
            <div>
                <div class="k">{{ $k[0] }}</div>
                <div class="v">{{ $k[1] }}</div>
            </div>
        </div>
    @endforeach
</div>
