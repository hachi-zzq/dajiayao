<?php namespace Dajiayao\Console\Commands;

use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetSellerMenu extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dajiayao:set-seller-menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set the Seller weixin mp menu';

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
        $this->info('TODO');
        $menu = Config::get('weixin.menu.seller');
        $data_string = urldecode(json_encode($menu));

        $mq = new MQ();
        $access_token = $mq->getWeixinAccessTokenByName('seller');

        $wxClient = new WeixinClient();
        $rt = $wxClient->setMenu($data_string, $access_token);
        if ($rt->errcode == 0) {
            $this->info('success');
        } else {
            $this->error(sprintf("failed, errcode: %d, errmsg: %s", $rt->errcode, $rt->errmsg));
        }
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
