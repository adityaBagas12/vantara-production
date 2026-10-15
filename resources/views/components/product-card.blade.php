@props([
    'package',
    'available' => true,
])

<div {{ $attributes->merge(['class' => 'group relative bg-[#15171D] border border-[#2A2E38] rounded-[20px] p-5 flex flex-col justify-between hover:border-[#F5A524]/40 transition-all duration-200 hover:-translate-y-1 hover:shadow-2xl hover:shadow-black/60']) }}>
    
    <div>
        <!-- Status Pill Top Left -->
        <div class="flex items-center justify-between mb-4">
            @if ($package->is_active ?? true)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/60 text-emerald-400 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Tersedia
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#1C1F27] text-[#A9ADB9] border border-[#2A2E38]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#A9ADB9]"></span>
                    Sedang disewa
                </span>
            @endif

            @if ($package->is_featured)
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#F5A524] bg-[#F5A524]/10 border border-[#F5A524]/30 px-2.5 py-0.5 rounded-full">
                    Unggulan
                </span>
            @endif
        </div>

        <!-- Product Image / Icon Visual Preview -->
        <div class="w-full h-44 rounded-[16px] bg-[#1C1F27] border border-[#2A2E38]/60 overflow-hidden relative mb-5 flex items-center justify-center group-hover:border-[#F5A524]/20 transition-colors">
            @if ($package->image_path)
                <img src="{{ $package->image_path }}" 
                     alt="{{ $package->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <!-- Fallback Icon if image fails -->
                <div class="hidden absolute inset-0 bg-gradient-to-br from-[#1C1F27] to-[#15171D] items-center justify-center">
                    <svg class="w-16 h-16 text-[#F5A524]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                </div>
            @else
                <!-- Aesthetic Ambient Background with Icon -->
                <div class="w-full h-full bg-gradient-to-b from-[#1C1F27] to-[#15171D] flex items-center justify-center relative">
                    <div class="w-24 h-24 rounded-full bg-[#F5A524]/5 blur-xl absolute"></div>
                    <svg class="w-14 h-14 text-[#F5A524]/70 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                </div>
            @endif
        </div>

        <!-- Category & Title -->
        <div class="text-xs font-medium text-[#A9ADB9] mb-1.5">
            {{ $package->category ?? 'Layanan Event' }}
        </div>
        <h3 class="text-lg font-bold text-[#F2EFE9] font-['Bricolage_Grotesque',sans-serif] leading-snug line-clamp-2 mb-2">
            {{ $package->name }}
        </h3>

        <!-- Price -->
        <div class="text-base font-bold text-[#F2EFE9] font-mono-num mb-5">
            Rp {{ number_format($package->price, 0, ',', '.') }}
            <span class="text-xs font-normal text-[#A9ADB9] font-sans">/ hari</span>
        </div>
    </div>

    <!-- Card Action Buttons -->
    <div class="grid grid-cols-2 gap-2.5 pt-2">
        <a href="{{ route('packages.show', $package->slug) }}" 
           class="inline-flex items-center justify-center h-[42px] min-h-[40px] rounded-full bg-transparent border border-[#2A2E38] text-[#F2EFE9] text-xs font-semibold hover:border-[#F5A524] hover:text-[#F5A524] transition-colors">
            Detail
        </a>

        <form action="{{ route('cart.store') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" 
                    class="w-full inline-flex items-center justify-center h-[42px] min-h-[40px] rounded-full bg-[#F5A524] text-[#1A1204] text-xs font-bold hover:bg-[#E0931B] transition-colors shadow-sm">
                Booking
            </button>
        </form>
    </div>

</div>
