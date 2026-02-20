@extends('layouts.app')

@section('title', 'Pengaturan Footer')

@section('content')
<div class="px-4 py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center whitespace-normal">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Footer</h1>
            <p class="mt-2 text-sm text-gray-500">
                Atur informasi yang ditampilkan di bagian bawah (footer) website.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50/50 border border-green-200 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                <p class="text-xs font-medium text-green-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50/50 border border-red-200">
            <div class="flex items-center gap-3 mb-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan</h3>
            </div>
            <ul class="list-disc pl-8">
                @foreach ($errors->all() as $error)
                    <li class="text-xs font-medium text-red-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.footer-settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Umum -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Informasi Umum</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tentang (ID)</label>
                            <textarea name="about_id" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm">{{ old('about_id', $setting->about_id) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tentang (EN)</label>
                            <textarea name="about_en" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm">{{ old('about_en', $setting->about_en) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Kontak</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <input type="text" name="address" value="{{ old('address', $setting->address) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Tautan Sosial Media</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded flex gap-2 mb-4">
                            <i class="fa-solid fa-circle-info mt-0.5"></i>
                            <p>Kosongkan kolom tautan jika Anda tidak ingin menampilkan ikon sosial media tersebut di footer.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                              <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fa-brands fa-facebook-f w-4 text-center"></i>
                              </span>
                              <input type="url" name="facebook_link" value="{{ old('facebook_link', $setting->facebook_link) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="https://facebook.com/...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                              <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fa-brands fa-instagram w-4 text-center"></i>
                              </span>
                              <input type="url" name="instagram_link" value="{{ old('instagram_link', $setting->instagram_link) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="https://instagram.com/...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                              <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fa-brands fa-youtube w-4 text-center"></i>
                              </span>
                              <input type="url" name="youtube_link" value="{{ old('youtube_link', $setting->youtube_link) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="https://youtube.com/...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Twitter / X URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                              <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fa-brands fa-x-twitter w-4 text-center"></i>
                              </span>
                              <input type="url" name="twitter_link" value="{{ old('twitter_link', $setting->twitter_link) }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-primary focus:ring-primary sm:text-sm" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="lg:col-span-2 flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex justify-center items-center gap-2 py-3 px-8 shadow-md text-sm font-bold rounded-lg text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-save"></i> Simpan Pengaturan Footer
                </button>
            </div>
            
        </div>
    </form>
</div>
@endsection
