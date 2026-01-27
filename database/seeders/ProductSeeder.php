<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Ukiran Relief Ramayana',
                'description' => 'Ukiran kayu jati kualitas super dengan detail relief kisah Ramayana. Cocok untuk hiasan dinding ruang tamu mewah.',
                'price' => 2500000,
                'image_path' => 'https://images.unsplash.com/photo-1615800001861-6d7c6b9e9e6e?auto=format&fit=crop&w=800&q=80', // Replace with relevant image if available
                'seller_name' => 'Jepara Art Furn',
                'seller_contact' => '081234567890'
            ],
            [
                'name' => 'Kain Tenun Troso Motif Rangrang',
                'description' => 'Kain tenun asli Troso Jepara dengan motif Rangrang yang cerah dan elegan. Bahan nyaman dipakai.',
                'price' => 150000,
                'image_path' => 'https://images.unsplash.com/photo-1605218427368-35b88219572b?auto=format&fit=crop&w=800&q=80',
                'seller_name' => 'Tenun Troso Mandiri',
                'seller_contact' => '089876543210'
            ],
            [
                'name' => 'Satu Set Kursi Teras Betawi',
                'description' => 'Kursi teras model Betawi dari kayu jati solid. Finishing natural melamine. Kuat dan tahan lama.',
                'price' => 1200000,
                'image_path' => 'https://images.unsplash.com/photo-1599388145876-0f81d8564264?auto=format&fit=crop&w=800&q=80',
                'seller_name' => 'Mebel Jati Abadi',
                'seller_contact' => '085678901234'
            ],
            [
                'name' => 'Vas Bunga Gerabah Mayong',
                'description' => 'Vas bunga estetik dari gerabah Mayong Lor. Finishing halus dengan motif minimalis.',
                'price' => 45000,
                'image_path' => 'https://images.unsplash.com/photo-1612152605347-8b0596d6e3f9?auto=format&fit=crop&w=800&q=80',
                'seller_name' => 'Griyo Gerabah',
                'seller_contact' => '081122334455'
            ],
            [
                'name' => 'Patung Kuda Jengke',
                'description' => 'Patung kuda jengke simbol semangat dan keberanian. Dibuat oleh pengrajin profesional Mulyoharjo.',
                'price' => 850000,
                'image_path' => 'https://images.unsplash.com/photo-1594916895393-27a3c7c25c27?auto=format&fit=crop&w=800&q=80',
                'seller_name' => 'Sanggar Seni Ukir',
                'seller_contact' => '087788990011'
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            Product::create($product);
        }
    }
}
