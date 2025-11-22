<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\TransactionDetail;
use App\Models\Outlet;
use App\Models\Treatment;
use App\Models\TreatmentDetail;

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
            'name' => 'Fast Clean'
        ]);

        TreatmentDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'treatment_id' => 1,
            'name' => 'Fast Clean',
            'cost' => 30000,
            'processing_time' => 3,
            'process' => 'Process 1,Process 2, Process 3'
        ]);

        Treatment::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Deep Cleaning'
        ]);

        TreatmentDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'treatment_id' => 2,
            'name' => 'Reguler Mild',
            'cost' => 50000,
            'processing_time' => 3,
            'process' => 'Process 1,Process 2, Process 3'
        ]);

        TreatmentDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'treatment_id' => 2,
            'name' => 'Reguler Hard',
            'cost' => 70000,
            'processing_time' => 3,
            'process' => 'Process 1,Process 2, Process 3'
        ]);

        TreatmentDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'treatment_id' => 2,
            'name' => 'Premium Mild',
            'cost' => 80000,
            'processing_time' => 3,
            'process' => 'Process 1,Process 2, Process 3'
        ]);

        TreatmentDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'treatment_id' => 2,
            'name' => 'Premium Hard',
            'cost' => 100000,
            'processing_time' => 3,
            'process' => 'Process 1,Process 2, Process 3'
        ]);

        Treatment::create([
            'uuid' => fake()->unique()->uuid(),
            'name' => 'Deep Cleaning Kids'
        ]);

        Transaction::create([
            'uuid' => fake()->unique()->uuid(),
            'customer_id' => 1,
            'outlet_id' => 2,
            'transaction_type' => 'dropzone',
            'payment_method' => 'cash',
            'payment_time' => 'later',
            'transaction_start' => Carbon::now(),
            'transaction_end' => Carbon::now()->addDays(3),
            'payment_status' => 'unpaid',
            'total_items' => 2,
            'cost' => 105000,
            'total_cost' => 105000,
            'invoice_no' => '00001/ASC/2024',
            'transaction_status' => 'pending'
        ]);
        
        TransactionDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 1,
            'treatment_id' => 1,
            'treatment_details_id' => 1,
            'merk' => 'Nike Air Jordan',
            'type' => 'sepatu',
            'size' => 'EU-43',
            'cost' => 30000,
            'status' => 'pending',
            'description' => 'Lorem ipsum dolor sit amet 1'
        ]);
        
        TransactionDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 1,
            'treatment_id' => 2,
            'treatment_details_id' => 1,
            'merk' => 'Gucci',
            'type' => 'tas',
            'cost' => 75000,
            'status' => 'pending',
            'description' => 'Lorem ipsum dolor sit amet 2'
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
            'shipping_cost' => 20000,
            'total_cost' => 70000,
            'invoice_no' => '00002/ASC/2024',
            'transaction_status' => 'pending'
        ]);

        TransactionDetail::create([
            'uuid' => fake()->unique()->uuid(),
            'transaction_id' => 2,
            'treatment_id' => 2,
            'treatment_details_id' => 1,
            'merk' => 'Converse',
            'type' => 'sepatu',
            'cost' => 50000,
            'status' => 'pending',
            'description' => 'Lorem ipsum dolor sit amet 3'
        ]);
    }
}
