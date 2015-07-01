<?php namespace Dajiayao\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'Dajiayao\Console\Commands\Inspire',
		'Dajiayao\Console\Commands\ImportRegion',
        'Dajiayao\Console\Commands\InitShops',
        'Dajiayao\Console\Commands\SetBuyerMenu',
        'Dajiayao\Console\Commands\SetSellerMenu'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();
	}

}
