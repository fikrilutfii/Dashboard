<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = base_path('data master.csv');

        if (!File::exists($csvFile)) {
            $this->command->error("File CSV not found at: $csvFile");
            return;
        }

        $this->command->info("Reading CSV file...");

        $fileStream = fopen($csvFile, 'r');
        
        // Skip Header
        fgetcsv($fileStream);

        $count = 0;
        while (($row = fgetcsv($fileStream)) !== false) {
            // Mapping: Column 0 = Code, Column 1 = Name
            $code = trim($row[0] ?? '');
            $name = trim($row[1] ?? '');

            if (empty($code) || empty($name)) {
                continue;
            }

            // Fix Scientific Notation if present (simple check)
            if (strpos($code, 'E+') !== false) {
                $code = number_format((float)$code, 0, '', '');
            }

            Product::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'unit' => 'pcs', // Default
                    'price' => 0      // Default
                ]
            );

            $count++;
            if ($count % 100 === 0) {
                $this->command->info("Imported $count records...");
            }
        }

        fclose($fileStream);
        $this->command->info("Done! Total imported: $count items.");
    }
}
