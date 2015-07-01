<?php namespace Dajiayao\Console\Commands;

use Dajiayao\Library\Mq\MQ;
use Dajiayao\Library\Weixin\WeixinClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetBuyerMenu extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dajiayao:set-buyer-menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set the Buyer weixin mp menu';

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
        $menu = Config::get('weixin.menu.buyer');
		$data_string = urldecode(json_encode($menu));

        $mq = new MQ();
        $access_token = $mq->getWeixinAccessTokenByName('buyer');

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
