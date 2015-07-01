<?php namespace Dajiayao\Console\Commands;

use Dajiayao\Model\Address;
use Illuminate\Console\Command;
use J20\Uuid\Uuid;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportRegion extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dajiayao:import-region';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'import region js into mysqldb';

	/**
	 * Create a new command instance.
	 *
	 * @return void
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
		$file = public_path('region.js');
        try{
            $json = file_get_contents($file);
        }catch (\Exception $e){
            echo $e->getMessage();

        }



        \DB::table('addresses')->truncate();
        eval($json);
        //province
        foreach($province as $p){
            $address = new Address();
            $address->id = $p[0];
            $address->guid = Uuid::v4(false);
            $address->address = $p[1][0];
            $address->parent_id = 0;
            $address->level = $p[2];
            $address->save();
        }

        $i = 0;
        $arrId = array();
        foreach($json as $v){
            if($v[3] == 3){
                continue;
            }


            $address = new Address();
            if(in_array($v[0],$arrId)){
                rsort($arrId);
                $id = $arrId[0]+1;
                \Log::info(sprintf("%s in arr ,+1 = %s",$v[0],$id));

            }else{
                $id = $v[0];
            }
            $address->id = $id;
            $address->guid = Uuid::v4(false);
            $address->address = $v[1][0];
            $address->parent_id = $v[2];
            $address->level = $v[3];

            array_push($arrId,$id);

            try{
                $address->save();

            }catch (\Exception $e){
                \Log::info(sprintf("erro msg: %s ",$e->getMessage()));
                continue;
            }

            echo sprintf("success insert one raw,ID: %s",$address->id);
            $i++;
            echo "\n";
        }

        echo sprintf("complete !! %s raws affected\n",$i);

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
		];
	}

}
