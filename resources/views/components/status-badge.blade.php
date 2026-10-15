@props([
    'status' => 'pending',
    'dark' => false,
    'customLabel' => null,
])

@php
    $statusKey = strtolower($status);
    
    // Label mapping (Bahasa Indonesia per design)
    $label = $customLabel ?? match($statusKey) {
        'pending' => 'Menunggu',
        'dp_received' => 'DP Diterima',
        'confirmed' => 'ACC',
        'completed' => 'Selesai',
        'cancelled' => 'Ditolak',
        default => ucfirst($status),
    };

    // Styling per design rules
    if ($dark) {
        $classes = match($statusKey) {
            'pending' => 'bg-[#FFF1D6]/15 text-[#F5A524] border border-[#F5A524]/30',
            'dp_received' => 'bg-[#E4EEFF]/15 text-blue-300 border border-blue-400/30',
            'confirmed' => 'bg-[#E4F6EA]/15 text-emerald-300 border border-emerald-400/30',
            'completed' => 'bg-[#DDF3F1]/15 text-teal-300 border border-teal-400/30',
            'cancelled' => 'bg-[#FDECEC]/15 text-rose-300 border border-rose-400/30',
            default => 'bg-[#2A2E38] text-[#A9ADB9] border border-[#2A2E38]',
        };
    } else {
        $classes = match($statusKey) {
            'pending' => 'bg-[#FFF1D6] text-[#8A5300] border border-[#F1CF8E]',
            'dp_received' => 'bg-[#E4EEFF] text-[#1D4F91] border border-[#B3CCF2]',
            'confirmed' => 'bg-[#E4F6EA] text-[#17653A] border border-[#A9DDBB]',
            'completed' => 'bg-[#DDF3F1] text-[#0F5F59] border border-[#A6D9D4]',
            'cancelled' => 'bg-[#FDECEC] text-[#8F1D1D] border border-[#F3B9B9]',
            default => 'bg-gray-100 text-gray-700 border border-gray-200',
        };
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center px-3 py-1 text-xs font-semibold rounded-full tracking-wide {$classes}"]) }}>
    {{ $label }}
</span>
