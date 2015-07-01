<?php namespace Dajiayao\Console\Commands;

use Dajiayao\Model\Seller;
use Dajiayao\Model\Shop;
use Dajiayao\Services\ShopService;
use Illuminate\Console\Command;
//use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Input\InputArgument;

class InitShops extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dajiayao:init-shops';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Init shop only when there are no shops in DB';

	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->info('Start to create a shop, please wait...');
        $shop = Shop::where('seller_id', 1)->first();
        if (count($shop) > 0) {
            $this->info('A shop exist, seller_id = 1');
            return;
        }
        $seller = Seller::find(1);
        $shopService = new ShopService();
        $shopService->createShop($seller, 1, 1, '测试小店', '测试小店', 'test', 'http://mmbiz.qpic.cn/mmbiz/4atXZMyxUkdTkThhj8icicfHpd07oaflgJpwwLficHRaLZQzYU8C7A3gWpua4dogmUefNln5K4ChSU9LeCanib6vEw/0?wx_fmt=png');
        $this->info('Finished');
        return;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
