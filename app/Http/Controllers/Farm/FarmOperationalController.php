<?php

namespace App\Http\Controllers\Farm;

use App\Http\Controllers\Controller;
use App\Models\FarmBatch;
use App\Models\FarmCoop;
use App\Models\FarmFeedLog;
use App\Models\FarmHarvestLog;
use App\Models\FarmHealthLog;
use App\Models\FarmProductionLog;
use App\Models\FarmVaccineSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmOperationalController extends Controller
{
    public function index(Request $request)
    {
        $coops = FarmCoop::orderBy('name')->get();
        $coopId = $request->get('coop_id');
        $tab = $request->get('tab', 'batches');

        // 1. Batches Query
        $batchesQuery = FarmBatch::with(['coop', 'feedLogs', 'harvestLogs'])->latest('entry_date');
        if ($coopId) {
            $batchesQuery->where('farm_coop_id', $coopId);
        }
        $batches = $batchesQuery->get();

        // 2. Feed Logs Query
        $feedLogsQuery = FarmFeedLog::with(['batch', 'coop'])->latest('log_date');
        if ($coopId) {
            $feedLogsQuery->where('farm_coop_id', $coopId);
        }
        if ($request->filled('date_from')) {
            $feedLogsQuery->whereDate('log_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $feedLogsQuery->whereDate('log_date', '<=', $request->date_to);
        }
        $feedLogs = $feedLogsQuery->paginate(15, ['*'], 'feed_page');

        // 3. Health & Vaccine Query
        $healthLogsQuery = FarmHealthLog::with(['batch', 'coop'])->latest('log_date');
        if ($coopId) {
            $healthLogsQuery->where('farm_coop_id', $coopId);
        }
        $healthLogs = $healthLogsQuery->paginate(15, ['*'], 'health_page');

        $vaccinesQuery = FarmVaccineSchedule::with(['batch', 'coop'])->latest('scheduled_date');
        if ($coopId) {
            $vaccinesQuery->where('farm_coop_id', $coopId);
        }
        $vaccineSchedules = $vaccinesQuery->get();

        // Vaccine Reminder (Pending & scheduled within next 7 days or overdue)
        $upcomingVaccines = FarmVaccineSchedule::with(['batch', 'coop'])
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '<=', now()->addDays(7))
            ->orderBy('scheduled_date')
            ->get();

        // 4. Production Query
        $productionLogsQuery = FarmProductionLog::with(['batch', 'coop'])->latest('log_date');
        if ($coopId) {
            $productionLogsQuery->where('farm_coop_id', $coopId);
        }
        $productionLogs = $productionLogsQuery->paginate(15, ['*'], 'prod_page');

        // 5. Harvest Query
        $harvestLogsQuery = FarmHarvestLog::with(['batch', 'coop'])->latest('harvest_date');
        if ($coopId) {
            $harvestLogsQuery->where('farm_coop_id', $coopId);
        }
        $harvestLogs = $harvestLogsQuery->paginate(15, ['*'], 'harvest_page');

        // Stats summary
        $totalActivePop = FarmBatch::where('status', 'aktif')->sum('current_population');
        $mortalityMonth = FarmHealthLog::whereMonth('log_date', now()->month)->sum('mortality_count');
        $todayEggs      = FarmProductionLog::whereDate('log_date', now()->toDateString())->sum('total_egg_count');
        
        // FCR rata-rata broiler aktif
        $activeBroilerBatches = FarmBatch::where('status', 'aktif')->where('type', 'broiler')->get();
        $totalFcr = 0;
        $broilerCount = 0;
        foreach ($activeBroilerBatches as $b) {
            $f = $b->calculateFcr();
            if ($f > 0) {
                $totalFcr += $f;
                $broilerCount++;
            }
        }
        $avgFcr = $broilerCount > 0 ? round($totalFcr / $broilerCount, 2) : 0;

        // Active batches for select options
        $activeBatches = FarmBatch::with('coop')->where('status', 'aktif')->get();

        return view('farm.operational.index', compact(
            'coops', 'tab', 'batches', 'feedLogs', 'healthLogs', 'vaccineSchedules',
            'upcomingVaccines', 'productionLogs', 'harvestLogs', 'totalActivePop',
            'mortalityMonth', 'todayEggs', 'avgFcr', 'activeBatches'
        ));
    }

    // 1. Submodul Populasi Batch
    public function storeBatch(Request $request)
    {
        $request->validate([
            'farm_coop_id'       => 'required|exists:farm_coops,id',
            'batch_code'         => 'required|string|unique:farm_batches,batch_code',
            'type'               => 'required|in:broiler,layer',
            'entry_date'         => 'required|date',
            'initial_population' => 'required|integer|min:1',
            'target_harvest_date'=> 'nullable|date|after_or_equal:entry_date',
        ]);

        FarmBatch::create([
            'farm_coop_id'       => $request->farm_coop_id,
            'batch_code'         => strtoupper($request->batch_code),
            'type'               => $request->type,
            'entry_date'         => $request->entry_date,
            'initial_population' => $request->initial_population,
            'current_population' => $request->initial_population,
            'target_harvest_date'=> $request->target_harvest_date,
            'status'             => 'aktif',
            'notes'              => $request->notes,
        ]);

        return redirect()->route('farm.operational.index', ['tab' => 'batches'])
            ->with('success', 'Batch / siklus populasi baru berhasil ditambahkan.');
    }

    public function closeBatch(FarmBatch $farmBatch)
    {
        $farmBatch->update(['status' => 'panen_selesai']);
        return redirect()->route('farm.operational.index', ['tab' => 'batches'])
            ->with('success', 'Batch populasi berhasil ditutup (panen selesai).');
    }

    // 2. Submodul Pemberian Pakan
    public function storeFeed(Request $request)
    {
        $request->validate([
            'farm_batch_id' => 'required|exists:farm_batches,id',
            'log_date'      => 'required|date',
            'feed_type'     => 'required|string|max:100',
            'quantity_kg'   => 'required|numeric|min:0.01',
        ]);

        $batch = FarmBatch::findOrFail($request->farm_batch_id);

        FarmFeedLog::create([
            'farm_batch_id' => $batch->id,
            'farm_coop_id'  => $batch->farm_coop_id,
            'log_date'      => $request->log_date,
            'feed_type'     => $request->feed_type,
            'quantity_kg'   => $request->quantity_kg,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('farm.operational.index', ['tab' => 'feed'])
            ->with('success', 'Pemberian pakan harian berhasil dicatat.');
    }

    public function destroyFeed(FarmFeedLog $farmFeedLog)
    {
        $farmFeedLog->delete();
        return redirect()->route('farm.operational.index', ['tab' => 'feed'])
            ->with('success', 'Log pakan berhasil dihapus.');
    }

    // 3. Submodul Kesehatan & Mortalitas
    public function storeHealth(Request $request)
    {
        $request->validate([
            'farm_batch_id'   => 'required|exists:farm_batches,id',
            'log_date'        => 'required|date',
            'mortality_count' => 'required|integer|min:0',
            'cull_count'      => 'nullable|integer|min:0',
        ]);

        $batch = FarmBatch::findOrFail($request->farm_batch_id);
        $mortality = (int)$request->mortality_count;
        $cull = (int)($request->cull_count ?? 0);
        $totalLoss = $mortality + $cull;

        DB::transaction(function () use ($batch, $request, $mortality, $cull, $totalLoss) {
            FarmHealthLog::create([
                'farm_batch_id'   => $batch->id,
                'farm_coop_id'    => $batch->farm_coop_id,
                'log_date'        => $request->log_date,
                'mortality_count' => $mortality,
                'cull_count'      => $cull,
                'cause'           => $request->cause,
                'treatment_notes' => $request->treatment_notes,
            ]);

            // Update sisa populasi hidup real-time
            $newPop = max(0, $batch->current_population - $totalLoss);
            $batch->update(['current_population' => $newPop]);
        });

        return redirect()->route('farm.operational.index', ['tab' => 'health'])
            ->with('success', 'Catatan kesehatan/mortalitas disimpan & populasi real-time telah diperbarui.');
    }

    public function destroyHealth(FarmHealthLog $farmHealthLog)
    {
        $farmHealthLog->delete();
        return redirect()->route('farm.operational.index', ['tab' => 'health'])
            ->with('success', 'Catatan kesehatan dihapus.');
    }

    public function storeVaccine(Request $request)
    {
        $request->validate([
            'farm_batch_id'  => 'required|exists:farm_batches,id',
            'vaccine_name'   => 'required|string|max:150',
            'scheduled_date' => 'required|date',
        ]);

        $batch = FarmBatch::findOrFail($request->farm_batch_id);

        FarmVaccineSchedule::create([
            'farm_batch_id'  => $batch->id,
            'farm_coop_id'   => $batch->farm_coop_id,
            'vaccine_name'    => $request->vaccine_name,
            'scheduled_date' => $request->scheduled_date,
            'recurring_days' => $request->recurring_days,
            'status'         => 'pending',
            'notes'          => $request->notes,
        ]);

        return redirect()->route('farm.operational.index', ['tab' => 'health'])
            ->with('success', 'Jadwal vaksinasi berhasil ditambahkan.');
    }

    public function completeVaccine(FarmVaccineSchedule $farmVaccineSchedule)
    {
        $farmVaccineSchedule->update(['status' => 'selesai']);
        return redirect()->route('farm.operational.index', ['tab' => 'health'])
            ->with('success', 'Status vaksinasi berhasil diubah menjadi selesai.');
    }

    public function destroyVaccine(FarmVaccineSchedule $farmVaccineSchedule)
    {
        $farmVaccineSchedule->delete();
        return redirect()->route('farm.operational.index', ['tab' => 'health'])
            ->with('success', 'Jadwal vaksinasi dihapus.');
    }

    // 4. Submodul Produksi
    public function storeProduction(Request $request)
    {
        $request->validate([
            'farm_batch_id' => 'required|exists:farm_batches,id',
            'log_date'      => 'required|date',
        ]);

        $batch = FarmBatch::findOrFail($request->farm_batch_id);

        if ($batch->type === 'layer') {
            $request->validate([
                'egg_count_a' => 'required|integer|min:0',
                'egg_count_b' => 'required|integer|min:0',
                'egg_count_c' => 'required|integer|min:0',
            ]);

            $totalEggs = (int)$request->egg_count_a + (int)$request->egg_count_b + (int)$request->egg_count_c;
            $eggRate = $batch->current_population > 0 
                ? round(($totalEggs / $batch->current_population) * 100, 2)
                : 0;

            FarmProductionLog::create([
                'farm_batch_id'       => $batch->id,
                'farm_coop_id'        => $batch->farm_coop_id,
                'log_date'            => $request->log_date,
                'egg_count_a'         => $request->egg_count_a,
                'egg_count_b'         => $request->egg_count_b,
                'egg_count_c'         => $request->egg_count_c,
                'total_egg_count'     => $totalEggs,
                'total_egg_weight_kg' => $request->total_egg_weight_kg ?? 0,
                'egg_production_rate' => $eggRate,
                'notes'               => $request->notes,
            ]);
        } else {
            $request->validate([
                'avg_weight_kg' => 'required|numeric|min:0.01',
            ]);

            FarmProductionLog::create([
                'farm_batch_id' => $batch->id,
                'farm_coop_id'  => $batch->farm_coop_id,
                'log_date'      => $request->log_date,
                'avg_weight_kg' => $request->avg_weight_kg,
                'notes'         => $request->notes,
            ]);
        }

        return redirect()->route('farm.operational.index', ['tab' => 'production'])
            ->with('success', 'Data produksi harian berhasil disimpan.');
    }

    public function destroyProduction(FarmProductionLog $farmProductionLog)
    {
        $farmProductionLog->delete();
        return redirect()->route('farm.operational.index', ['tab' => 'production'])
            ->with('success', 'Log produksi dihapus.');
    }

    // 5. Submodul Panen / Afkir
    public function storeHarvest(Request $request)
    {
        $request->validate([
            'farm_batch_id'   => 'required|exists:farm_batches,id',
            'harvest_date'    => 'required|date',
            'type'            => 'required|in:panen_broiler,afkir_layer',
            'chicken_count'   => 'required|integer|min:1',
            'total_weight_kg' => 'required|numeric|min:0.1',
        ]);

        $batch = FarmBatch::findOrFail($request->farm_batch_id);
        $count = (int)$request->chicken_count;
        $totalWeight = (float)$request->total_weight_kg;
        $avgWeight = round($totalWeight / $count, 3);
        $refPrice = (float)($request->reference_price_per_kg ?? 0);

        DB::transaction(function () use ($batch, $request, $count, $totalWeight, $avgWeight, $refPrice) {
            FarmHarvestLog::create([
                'farm_batch_id'          => $batch->id,
                'farm_coop_id'           => $batch->farm_coop_id,
                'harvest_date'           => $request->harvest_date,
                'type'                   => $request->type,
                'chicken_count'          => $count,
                'total_weight_kg'        => $totalWeight,
                'avg_weight_kg'          => $avgWeight,
                'reference_price_per_kg' => $refPrice,
                'status_penjualan'       => 'tersedia',
                'notes'                  => $request->notes,
            ]);

            // Update sisa populasi hidup real-time
            $newPop = max(0, $batch->current_population - $count);
            $batch->update(['current_population' => $newPop]);
        });

        return redirect()->route('farm.operational.index', ['tab' => 'harvest'])
            ->with('success', 'Data panen/afkir berhasil dicatat. Stok panen berstatus "tersedia".');
    }

    public function destroyHarvest(FarmHarvestLog $farmHarvestLog)
    {
        $farmHarvestLog->delete();
        return redirect()->route('farm.operational.index', ['tab' => 'harvest'])
            ->with('success', 'Catatan panen dihapus.');
    }
}
