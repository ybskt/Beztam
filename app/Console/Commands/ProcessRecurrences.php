<?php

namespace App\Console\Commands;

use App\Models\MonthlyRecurrence;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProcessRecurrences extends Command
{
    protected $signature = 'recurrences:process';
    protected $description = 'Process monthly recurring budgets/expenses';

    public function handle()
    {
        $recurrences = MonthlyRecurrence::whereDate('next_occurrence', '<=', now())->get();

        foreach ($recurrences as $recurrence) {
            $model = $recurrence->recurrable;
            $newEntry = $model->replicate();
            $newEntry->date = now();
            $newEntry->save();

            $recurrence->update([
                'next_occurrence' => Carbon::now()->addMonth(),
                'occurrence_count' => $recurrence->occurrence_count + 1
            ]);
        }

        $this->info('Processed ' . $recurrences->count() . ' recurrences.');
    }
}