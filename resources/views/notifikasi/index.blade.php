@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
  <div class="max-w-4xl mx-auto">

    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        Notifikasi
      </h1>
      <p class="text-gray-500 mt-1">Semua pemberitahuan tentang status pengaduan Anda</p>
    </div>

    @if($notifikasi->isEmpty())
      <div class="bg-white rounded-2xl shadow-md p-12 text-center">
        <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Notifikasi</h3>
        <p class="text-gray-500">Saat ini Anda tidak memiliki notifikasi</p>
      </div>
    @else
      <div class="space-y-3">
        @foreach($notifikasi as $item)
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 {{ !$item->is_read ? 'border-l-4 border-l-purple-500' : '' }}">
            <div class="p-5">
              <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 rounded-full bg-gradient-to-br 
                    @if(str_contains($item->tipe, 'pengaduan')) from-blue-500 to-blue-600
                    @elseif($item->tipe == 'user_baru') from-green-500 to-green-600
                    @elseif($item->tipe == 'pengaduan_ditugaskan') from-orange-500 to-orange-600
                    @else from-purple-500 to-purple-600
                    @endif
                    flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      @if($item->tipe == 'user_baru')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                      @elseif($item->tipe == 'pengaduan_ditugaskan')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                      @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                      @endif
                    </svg>
                  </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="text-base font-bold text-gray-900">
                      {{ $item->judul }}
                    </h3>
                    
                    @if(!$item->is_read)
                      <span class="flex-shrink-0 px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">Baru</span>
                    @endif
                  </div>

                  <p class="text-sm text-gray-600 mb-3">
                    {{ $item->isi }}
                  </p>

                  <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      {{ $item->created_at->diffForHumans() }}
                    </span>
                  </div>

                  @if($item->link)
                    <div class="mt-3">
                      <a href="{{ $item->link }}" class="inline-flex items-center gap-1 text-sm font-medium text-purple-600 hover:text-purple-800 transition">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($notifikasi->hasPages())
        <div class="mt-6">
          {{ $notifikasi->links() }}
        </div>
      @endif
    @endif

  </div>
</div>
@endsection
