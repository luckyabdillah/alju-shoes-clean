<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\DetailTransaction;
use App\Models\Outlet;
use App\Models\Treatment;

use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Campaign::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Campaign 1',
            'img' => 'asdasd.jpg',
            'alt' => 'Promo Bulan Juli 2024',
            'description' => 'Promo Bulan Juli 2024'
        ]);

        Campaign::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Campaign 2',
            'img' => 'qweqwe.jpg',
            'alt' => 'Promo Bulan Agustus 2024',
            'description' => 'Promo Bulan Agustus 2024'
        ]);

        Gallery::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Gallery 1',
            'img' => '123123.jpg',
            'alt' => 'Foto sepatu customer Nike Air',
            'description' => 'Foto sepatu customer Nike Air'
        ]);

        Gallery::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Gallery 2',
            'img' => '234234.jpg',
            'alt' => 'Foto tas customer',
            'description' => 'Foto tas customer'
        ]);

        Outlet::create([
            'uuid' => fake()->unique()->uuid(),
            'outlet_name' => 'Workshop',
            'outlet_code' => 'WKSP',
        ]);

        Outlet::create([
            'uuid' => fake()->unique()->uuid(),
            'outlet_name' => 'Dropzone Batavia',
            'outlet_code' => 'BVR',
        ]);

        Customer::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Lucky Abdillah',
            'whatsapp_number' => '6281283890098',
            'address' => 'Perumahan Cendana Tahap 3, RT002/RW009, Batam, Kepulauan Riau',
            'last_order' => Carbon::now()
        ]);

        Customer::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Miqdam Hambali',
            'whatsapp_number' => '623805839153',
            'address' => 'Sukabumi selalu di hati'
        ]);

        Treatment::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Deep Clean',
            'cost' => 50000,
        ]);

        Treatment::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Deep Clean Pro Max',
            'cost' => 75000,
        ]);

        Transaction::create([
            'uuid' => fake()->unique()->uuid(),
            'customer_id' => 1,
            'outlet_id' => 2,
            'transaction_type' => 'dropoff',
            'payment_method' => 'cash',
            'payment_time' => 'later',
            'transaction_start' => Carbon::now(),
            'transaction_end' => Carbon::now()->addDays(3),
            'payment_status' => 'unpaid',
            'total_items' => 2,
            'cost' => 125000,
            'total_amount' => 125000,
            'invoice_no' => 'INV/I/BVR/1',
            'transaction_status' => 'pending'
        ]);
        
        DetailTransaction::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 1,
            'item_name' => 'Sepatu Nike Air Jordan',
            'type' => 'sepatu',
            'size' => 'EU-43',
            'treatment_id' => 1,
            'amount' => 50000,
            'status' => 'pending'
        ]);
        
        DetailTransaction::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 1,
            'item_name' => 'Tas Gucci',
            'type' => 'tas',
            'treatment_id' => 2,
            'amount' => 75000,
            'status' => 'pending'
        ]);
        
        Transaction::create([
            'uuid' => fake()->unique()->uuid(),
            'customer_id' => 2,
            'outlet_id' => 1,
            'transaction_type' => 'pickup-delivery',
            'payment_method' => 'cash',
            'payment_time' => 'now',
            'transaction_start' => Carbon::now()->addMonths(2),
            'transaction_end' => Carbon::now()->addMonths(2),
            'payment_status' => 'unpaid',
            'total_items' => 1,
            'cost' => 50000,
            'other_cost' => 20000,
            'total_amount' => 70000,
            'invoice_no' => 'INV/I/WKSP/1',
            'transaction_status' => 'pending'
        ]);

        DetailTransaction::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 2,
            'item_name' => 'Converse Abu-abu',
            'type' => 'sepatu',
            'treatment_id' => 1,
            'amount' => 50000,
            'status' => 'pending'
        ]);
    }
}
