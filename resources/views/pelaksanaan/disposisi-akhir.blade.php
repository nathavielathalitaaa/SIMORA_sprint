@extends('layouts.master')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Disposisi Akhir Pelaksanaan</h1>
            <p class="text-[13px] font-light text-[#6B7280] mt-1">
                Tugaskan Penanggung Jawab (PIC) untuk setiap kegiatan organisasi yang telah disetujui penuh.
            </p>
        </div>
    </div>

    {{-- Grid List Surat Disposisi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($surats as $surat)
            @php
                $detail = $surat->kegiatanDetail;
                // Get all users from same organization
                $members = $surat->organisasi ? $surat->organisasi->members : collect();
            @endphp
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] flex flex-col transition hover:shadow-md">
                
                {{-- Card Header --}}
                <div class="p-6 pb-4 border-b border-gray-50">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#E8F5EE] flex items-center justify-center shrink-0">
                                <i data-lucide="award" class="w-5 h-5 text-[#2E7D5E]"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-playfair font-bold text-[#1A2B24]">{{ $detail->nama_kegiatan ?? $surat->perihal }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Organisasi: <span class="font-semibold text-gray-700">{{ $surat->organisasi->nama ?? '-' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                            Approved
                        </span>
                    </div>
                </div>

                {{-- Timeline & Location --}}
                <div class="px-6 py-4 flex-1 space-y-4">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Timeline Kegiatan</span>
                        <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-100 space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400 shrink-0"></i>
                                <span>
                                    {{ \Carbon\Carbon::parse($detail->tanggal_mulai)->translatedFormat('d M Y') }}
                                    @if($detail->tanggal_selesai)
                                        s/d {{ \Carbon\Carbon::parse($detail->tanggal_selesai)->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 shrink-0"></i>
                                <span class="font-medium">{{ $detail->lokasi }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Current PIC Display --}}
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">PIC Saat Ini</span>
                        <div class="flex items-center gap-3 p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                            <div class="w-8 h-8 rounded-full bg-white border border-emerald-200 flex items-center justify-center font-bold text-xs text-emerald-600 shrink-0">
                                {{ strtoupper(substr($surat->picUser->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-emerald-800">{{ $surat->picUser->name ?? 'Belum Ditugaskan' }}</p>
                                <p class="text-[10px] text-emerald-600">Email: {{ $surat->picUser->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Action / Form Assign --}}
                <div class="p-6 pt-2 bg-gray-50/30 border-t border-gray-50 rounded-b-[24px]">
                    <form action="{{ route('disposisi-akhir.assign', $surat->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tugaskan / Reassign PIC</label>
                            <select name="pic_user_id" required
                                    class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-3 text-xs text-gray-700 focus:ring-1 focus:ring-[#2E7D5E] focus:border-[#2E7D5E] outline-none transition">
                                <option value="" disabled selected>Pilih PIC Kegiatan...</option>
                                @foreach($members as $member)
                                    @if($member->user)
                                        <option value="{{ $member->user->id }}" {{ $surat->pic_user_id === $member->user->id ? 'selected' : '' }}>
                                            {{ $member->user->name }} ({{ $member->jabatan_label }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#4F6560] text-white rounded-xl text-xs font-bold hover:bg-[#3d504c] transition shadow-sm">
                            <i data-lucide="user-check" class="w-4 h-4"></i> Simpan Disposisi PIC
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-[24px] border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.03)] p-16 text-center">
                <div class="w-16 h-16 bg-[#E8F5EE] rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-[#2E7D5E]"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Semua Terdisposisi</h3>
                <p class="text-sm text-gray-500">Tidak ada surat kegiatan baru yang menunggu disposisi saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
