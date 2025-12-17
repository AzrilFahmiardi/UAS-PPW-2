@extends('base')
@section('title','Tambah Pegawai')
@section('menupegawai', 'underline decoration-4 underline-offset-7')
@section('content')
    <section class="p-4 bg-white rounded-lg min-h-[50vh]">
        <h1 class="text-3xl font-bold text-[#C0392B] mb-6 text-center">Tambah Pegawai</h1>
        <div class="mx-auto max-w-screen-xl">
            <div class="max-w-md mx-auto">
                @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                        <select name="pekerjaan_id" class="w-full rounded-md border px-3 py-2 text-sm">
                            <option value="">-- Pilih Pekerjaan --</option>
                            @foreach($pekerjaan as $p)
                            <option value="{{ $p->id }}" {{ old('pekerjaan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full rounded-md border px-3 py-2 text-sm"/>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-md border px-3 py-2 text-sm"/>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select name="gender" class="w-full rounded-md border px-3 py-2 text-sm">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3 flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" checked />
                        <label for="is_active" class="text-sm">Active</label>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Captcha</label>
                        <div class="flex items-center gap-2">
                            <img src="{{ captcha_src() }}" id="captcha-img" class="h-12" alt="captcha" />
                            <button type="button" id="refresh-captcha" class="rounded-md bg-gray-200 px-3 py-1 text-sm">Refresh</button>
                        </div>
                        <input type="text" name="captcha" value="" placeholder="Masukkan kode captcha" class="w-full rounded-md border px-3 py-2 text-sm mt-2" />
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Simpan</button>
                        <a href="{{ route('pegawai.index') }}" class="rounded-md bg-gray-200 px-4 py-2 text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
<script>
    document.getElementById('refresh-captcha')?.addEventListener('click', function () {
        const img = document.getElementById('captcha-img');
        if (!img) return;
        img.src = '{{ captcha_src() }}' + '?' + Date.now();
    });
</script>
@endpush
