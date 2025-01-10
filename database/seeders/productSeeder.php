<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class productSeeder extends Seeder
{

    public function run(): void
    {
        //Books

        Product::create([
            'id' => 1,
            'title' => 'To Kill a Mockingbird',
            'creator' => 'Harper Lee',
            'year' => 1960,
            'price' => 10.000,
            'ISBN' => '1234',
            'stock' => 100,
            'isEnable' => true,
            'type' => 'book',
        ]);

        Product::create([
            'id' => 2,
            'title' => '1984',
            'creator' => 'George Orwell',
            'year' => 1949,
            'price' => 12.300,
            'ISBN' => '5678',
            'stock' => 150,
            'isEnable' => true,
            'type' => 'book',
        ]);

        Product::create([
            'id' => 3,
            'title' => 'Pride and Prejudice',
            'creator' => 'Jane Austen',
            'year' => 1813,
            'price' => 5.000,
            'ISBN' => '91011',
            'stock' => 120,
            'isEnable' => true,
            'type' => 'book',
        ]);

        // Movies

        Product::create([
            'id' => 4,
            'title' => 'Inception',
            'creator' => 'Christopher Nolan',
            'year' => 2010,
            'price' => 2.000,
            'ISBN' => null,
            'stock' => 50,
            'isEnable' => true,
            'type' => 'movie',
        ]);

        Product::create([
            'id' => 5,
            'title' => 'The Matrix',
            'creator' => 'Lana Wachowski, Lilly Wachowski',
            'year' => 1999,
            'price' => 4.000,
            'ISBN' => null,
            'stock' => 75,
            'isEnable' => true,
            'type' => 'movie',
        ]);

        Product::create([
            'id' => 6,
            'title' => 'The Shawshank Redemption',
            'creator' => 'Frank Darabont',
            'year' => 1994,
            'price' => 12.000,
            'ISBN' => null,
            'stock' => 40,
            'isEnable' => true,
            'type' => 'movie',
        ]);
    }
}
