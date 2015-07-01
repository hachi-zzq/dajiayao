<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		$response = $this->call('GET', '/');

		$this->assertEquals(200, $response->getStatusCode());
	}


//    public function testMyname()
//    {
//        $json = '
//
//        {
//
//        "title":"周边主标题",
//
//        "description":"周边副标题",
//
//        "icon_url":"http://shp.qpic.cn/wx_shake_bus/0/1431503979e9dd2797018cad79186e03e8c5aec8dc/120",
//
//        "url":"http://www.baidu.com",
//
//        "comment":"comment"
//
//        }
//        ';
//        $ret = $this->call('POST','/rest/v1/weixin/page/create',[],[],[],[],$json);
//        $ret = json_decode($ret);
//        $this->assertEquals(10000, $ret->msgcode);
//    }


}
