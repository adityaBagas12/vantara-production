<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            // =============================================
            // KATEGORI: SOUND SYSTEM
            // =============================================
            [
                'name' => 'Paket Lamaran / Akad Nikah',
                'slug' => 'paket-lamaran-akad-nikah',
                'category' => 'Sound System',
                'description' => 'Paket sound system minimalis dan elegan untuk acara lamaran atau akad nikah di rumah maupun gedung kecil. Suara jernih dan tata letak rapi.',
                'image_path' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '2 Unit Speaker Beta Three + Stand',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    'Kru Teknisi Sound 1 Orang',
                ],
                'price' => 600000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Mini Karaoke',
                'slug' => 'paket-mini-karaoke',
                'category' => 'Sound System',
                'description' => 'Paket sound system serbaguna untuk gathering, ulang tahun, arisan, atau karaoke keluarga. Kapasitas memadai untuk ruangan sedang.',
                'image_path' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '4 Unit Speaker Beta Three + Stand',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    'Kru Teknisi Sound 1 Orang',
                ],
                'price' => 1000000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Mini Karaoke Plus Subwoofer',
                'slug' => 'paket-mini-karaoke-subwoofer',
                'category' => 'Sound System',
                'description' => 'Sound system berkualitas dengan tambahan 2 unit subwoofer 18" untuk bass yang lebih bertenaga. Cocok untuk pesta ulang tahun atau gathering outdoor.',
                'image_path' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '2 Unit Speaker Beta Three + Stand',
                    '2 Unit Subwoofer 18" (Double Decker)',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    'Kru Teknisi Sound 1 Orang',
                ],
                'price' => 1300000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Mini Orgen Tunggal',
                'slug' => 'paket-mini-orgen-tunggal',
                'category' => 'Sound System',
                'description' => 'Paket sound system 3000 Watt lengkap dengan orgen tunggal untuk hiburan pernikahan atau khitanan skala kecil hingga menengah.',
                'image_path' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Sound System 3000 Watt Full Set',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    '1 Unit Orgen / Keyboard',
                    'Operator Orgen & Kru Teknisi',
                ],
                'price' => 1600000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Orgen Tunggal',
                'slug' => 'paket-orgen-tunggal',
                'category' => 'Sound System',
                'description' => 'Paket sound system 5000 Watt dengan orgen tunggal full-power untuk pernikahan, khitanan, atau acara besar yang membutuhkan hiburan live music meriah.',
                'image_path' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Sound System 5000 Watt Full Set',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    '1 Unit Orgen / Keyboard Full Feature',
                    'Operator Orgen & Kru Teknisi Sound',
                ],
                'price' => 2500000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Orgen Tunggal + Saxophone / Wedding Singer',
                'slug' => 'paket-orgen-plus-saxophone-singer',
                'category' => 'Sound System',
                'description' => 'Paket orgen tunggal 5000 Watt dengan pilihan tambahan saxophonist atau wedding singer untuk suasana acara yang lebih berkesan dan romantis.',
                'image_path' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Sound System 5000 Watt Full Set',
                    'Mixer Audio Professional',
                    '4 Unit Mic Wireless',
                    '1 Unit Orgen / Keyboard Full Feature',
                    'Pilihan: Saxophonist ATAU Wedding Singer (1 Orang)',
                    'Kru Teknisi Sound',
                ],
                'price' => 3000000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Orgen Tunggal + Saxophone & Wedding Singer',
                'slug' => 'paket-orgen-saxophone-dan-singer',
                'category' => 'Sound System',
                'description' => 'Paket premium orgen tunggal 5000 Watt dengan saxophonist dan wedding singer sekaligus — hiburan live music lengkap untuk pernikahan mewah.',
                'image_path' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Sound System 5000 Watt Full Set',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    '1 Unit Orgen / Keyboard Full Feature',
                    '1 Orang Saxophonist Professional',
                    '1 Orang Wedding Singer',
                    'Kru Teknisi Sound',
                ],
                'price' => 3500000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Nasyid',
                'slug' => 'paket-nasyid',
                'category' => 'Sound System',
                'description' => 'Paket sound system dengan tim nasyid minimal 3 personil. Cocok untuk acara pernikahan Islami, pengajian, atau acara keagamaan yang membutuhkan hiburan nasyid live.',
                'image_path' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Sound System Full Set',
                    'Mixer Audio Professional',
                    '3 Unit Mic Wireless',
                    'Tim Nasyid Minimal 3 Personil',
                    'Kru Teknisi Sound',
                ],
                'price' => 3000000,
                'is_featured' => false,
                'is_active' => true,
            ],

            // =============================================
            // KATEGORI: LIGHTING & SPECIAL EFFECT
            // =============================================
            [
                'name' => 'VA Sparkle — Lighting & Special Effect',
                'slug' => 'va-sparkle-lighting',
                'category' => 'Lighting & Special Effect',
                'description' => 'Paket lighting dasar dengan moving beam dan efek dry ice untuk momen magis di acara pernikahan. Suasana aula menjadi lebih dramatis dan memukau.',
                'image_path' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '2 Unit Moving Beam',
                    'Dry Ice Machine (Efek Asap Lantai)',
                    'Firework / Cold Pyro 1x Sepasang',
                    'Kru Teknisi Lighting',
                ],
                'price' => 2500000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'VA Glow — Lighting & Special Effect',
                'slug' => 'va-glow-lighting',
                'category' => 'Lighting & Special Effect',
                'description' => 'Paket lighting menengah dengan 4 unit moving beam, dry ice, dan firework. Ideal untuk resepsi pernikahan gedung menengah dengan pencahayaan yang lebih dramatis.',
                'image_path' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '4 Unit Moving Beam',
                    'Dry Ice Machine (Efek Asap Lantai)',
                    'Firework / Cold Pyro 1x Sepasang',
                    'Kru Teknisi Lighting',
                ],
                'price' => 3000000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VA Radiance — Lighting & Special Effect',
                'slug' => 'va-radiance-lighting',
                'category' => 'Lighting & Special Effect',
                'description' => 'Paket lighting premium dengan 6 unit moving beam untuk ballroom besar. Efek cahaya yang memesona di setiap sudut ruangan acara resepsi Anda.',
                'image_path' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '6 Unit Moving Beam',
                    'Dry Ice Machine (Efek Asap Lantai)',
                    'Firework / Cold Pyro 1x Sepasang',
                    'Kru Teknisi Lighting',
                ],
                'price' => 3500000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VA Grand Illumination — Lighting Lengkap',
                'slug' => 'va-grand-illumination',
                'category' => 'Lighting & Special Effect',
                'description' => 'Paket lighting terlengkap: moving beam, dry ice, double firework, dan 8 unit parled warna-warni. Untuk pernikahan mewah di ballroom besar atau outdoor venue.',
                'image_path' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '6 Unit Moving Beam',
                    'Dry Ice Machine (Efek Asap Lantai)',
                    'Firework / Cold Pyro 2x Sepasang',
                    '8 Unit Parled Multicolor',
                    'Kru Teknisi Lighting',
                ],
                'price' => 4000000,
                'is_featured' => false,
                'is_active' => true,
            ],

            // =============================================
            // KATEGORI: VIDEOBOOTH 360
            // =============================================
            [
                'name' => 'VA Exclusive 360 — Videobooth 360°',
                'slug' => 'va-exclusive-videobooth-360',
                'category' => 'Videobooth 360',
                'description' => 'Sewa videobooth 360° lengkap dengan iPhone 14, lighting profesional, dan properti foto menarik. Hiburan interaktif yang membuat tamu undangan antusias di setiap acara.',
                'image_path' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Mesin Videobooth 360° Profesional',
                    'iPhone 14 sebagai Kamera Utama',
                    'Lighting Set Pro (Softbox Portable)',
                    'Tiang Pembatas / Velvet Rope',
                    'Monitor LCD 24 Inch (Preview Real-Time)',
                    'Properti Photo Beragam',
                    'Operator Videobooth',
                ],
                'price' => 1800000,
                'is_featured' => true,
                'is_active' => true,
            ],

            // =============================================
            // KATEGORI: BAND WEDDING
            // =============================================
            [
                'name' => 'VA Harmony — Band Wedding',
                'slug' => 'va-harmony-band-wedding',
                'category' => 'Band Wedding',
                'description' => 'Paket band pernikahan lengkap dengan vokalis pria/wanita, keyboard, bass, dan drum. Hiburan live music band untuk resepsi pernikahan yang berkesan.',
                'image_path' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Vokalis Male & Female',
                    'Keyboard / Synthesizer',
                    'Bass Elektrik',
                    'Drum Kit',
                    'Sound System Full Set',
                    'Instrumen Band Lengkap',
                    'Kru Teknisi Sound',
                ],
                'price' => 5000000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VA Symphony — Band Wedding Plus Gitar/Sax',
                'slug' => 'va-symphony-band-wedding',
                'category' => 'Band Wedding',
                'description' => 'Band pernikahan dengan tambahan gitaris atau saxophonist untuk melodi yang lebih kaya dan suasana pernikahan yang elegan dan romantis.',
                'image_path' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    'Vokalis Male & Female',
                    'Keyboard / Synthesizer',
                    'Pilihan: Gitar Elektrik ATAU Saxophone',
                    'Bass Elektrik',
                    'Drum Kit',
                    'Sound System Full Set',
                    'Instrumen Band Lengkap',
                    'Kru Teknisi Sound',
                ],
                'price' => 5500000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'VA Grande — Band Wedding (2 Vokalis)',
                'slug' => 'va-grande-band-wedding',
                'category' => 'Band Wedding',
                'description' => 'Band wedding dengan 2 vokalis utama, gitaris, dan saxophonist. Kemewahan hiburan live music terlengkap untuk ballroom pernikahan berskala besar.',
                'image_path' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '2 Orang Vokalis (Male & Female)',
                    'Keyboard / Synthesizer',
                    'Gitar Elektrik / Saxophone',
                    'Bass Elektrik',
                    'Drum Kit',
                    'Sound System Full Set',
                    'Instrumen Band Lengkap',
                    'Kru Teknisi Sound',
                ],
                'price' => 6500000,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'VA Royal — Band Wedding Premium',
                'slug' => 'va-royal-band-wedding',
                'category' => 'Band Wedding',
                'description' => 'Paket band pernikahan paling premium: 2 vokalis, keyboard, gitar, saxophone, bass, dan drum. Pengalaman konser live music mewah di hari spesial Anda.',
                'image_path' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop',
                'items' => [
                    '2 Orang Vokalis (Male & Female)',
                    'Keyboard / Synthesizer',
                    'Gitar Elektrik',
                    'Saxophone',
                    'Bass Elektrik',
                    'Drum Kit',
                    'Sound System Full Set',
                    'Instrumen Band Lengkap',
                    'Kru Teknisi Sound',
                ],
                'price' => 7000000,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(['slug' => $pkg['slug']], $pkg);
        }
    }
}
