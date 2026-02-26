<?php

namespace App\Console\Commands;

use App\Models\Alat;
use App\Models\Material;
use App\Models\StockSnapshot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SnapshotStockDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:snapshot {tanggal?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily snapshot of stock (alat & material)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggal = $this->argument('tanggal') 
            ? Carbon::parse($this->argument('tanggal'))
            : Carbon::today();

        $totalAlatAvailable = Alat::sum('available');
        $totalMaterialStock = Material::sum('stock');

        StockSnapshot::updateOrCreate(
            ['tanggal' => $tanggal],
            [
                'total_alat_available' => $totalAlatAvailable,
                'total_material_stock' => $totalMaterialStock,
            ]
        );

        $this->info("Stock snapshot created for {$tanggal->format('d M Y')}");
        $this->info("Alat Available: {$totalAlatAvailable}");
        $this->info("Material Stock: {$totalMaterialStock}");

        return Command::SUCCESS;
    }
}
