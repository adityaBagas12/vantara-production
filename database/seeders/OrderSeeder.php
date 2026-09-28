<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();
        if ($packages->isEmpty()) {
            return;
        }

        $sampleOrders = [
            [
                'order_code' => 'VAN-20261005-0001',
                'customer_name' => 'Budi Santoso (PT Tech Indonesia)',
                'customer_phone' => '081234567890',
                'customer_email' => 'budi@techindonesia.co.id',
                'event_address' => 'Grand Ballroom Hotel Indonesia Kempinski, Jakarta',
                'event_date' => '2026-10-05',
                'notes' => 'Membutuhkan rilis live streaming cepat ke YouTube & Zoom.',
                'total_price' => 8500000,
                'down_payment' => 4250000,
                'status' => 'confirmed',
                'package' => $packages->where('slug', 'paket-live-streaming-pro-multi-cam')->first() ?? $packages->first(),
            ],
            [
                'order_code' => 'VAN-20261012-0002',
                'customer_name' => 'Siti Nurhaliza',
                'customer_phone' => '082198765432',
                'customer_email' => 'siti.nurhaliza@gmail.com',
                'event_address' => 'Balai Kartini, Jakarta Selatan',
                'event_date' => '2026-10-12',
                'notes' => 'Acara Pernikahan (Wedding Reception).',
                'total_price' => 4500000,
                'down_payment' => 2000000,
                'status' => 'dp_received',
                'package' => $packages->where('slug', 'paket-dokumentasi-foto-video-acara')->first() ?? $packages->first(),
            ],
            [
                'order_code' => 'VAN-20261020-0003',
                'customer_name' => 'Hendra Kusuma',
                'customer_phone' => '085711223344',
                'customer_email' => 'hendra@creativehouse.com',
                'event_address' => 'BSD Grand Cave, Tangerang',
                'event_date' => '2026-10-20',
                'notes' => 'Shooting Commercial Video Ads.',
                'total_price' => 15000000,
                'down_payment' => 0,
                'status' => 'pending',
                'package' => $packages->where('slug', 'paket-cinematic-video-production')->first() ?? $packages->first(),
            ],
        ];

        foreach ($sampleOrders as $data) {
            $package = $data['package'];
            unset($data['package']);

            $order = Order::updateOrCreate(
                ['order_code' => $data['order_code']],
                $data
            );

            OrderItem::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'unit_price' => $package->price,
                    'quantity' => 1,
                    'subtotal' => $package->price,
                    'options_json' => $package->items,
                ]
            );
        }
    }
}
