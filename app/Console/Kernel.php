protected $commands = [
    \App\Console\Commands\ProcessRecurrences::class,
];

protected function schedule(Schedule $schedule)
{
    $schedule->command('recurrences:process')->daily();
}