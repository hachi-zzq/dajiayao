<?php
/**
 * Created by Zhengqian.Zhu
 * Email: zhengqian@dajiayao.cc
 * Date: 15/6/4
 */
return [

    'greetings' => "感谢关注丫摇小店，寻找附近的“丫摇信标”，打开蓝牙，使用微信摇一摇即可购买商品。
通过“订单查询”可以查看最近购买的商品，如果想逛逛购买过的店铺请访问“店铺收藏”。访问“成为卖家”，体验人人都可以零进货开店的乐趣。
任何使用及订单问题，请直接回复，稍后人工客服为您解答。",

    'buyer' => [
        'appid' => env('BUYER.APPID', 'wxfb42b0ea75d27f1d'),
        'appsecret' => env('BUYER.APPSECRET', 'a62a7197273e81cd4a1d14715bca2ec6'),
        'wxid' => '',
        'name' => '丫摇小店'
    ],

    'seller' => [
        'appid' => env('SELLER.APPID','wxed8558e01d137ac3'),
        'appsecret' => env('SELLER.APPSECRET','88462decbbba678e2c6ace82b1e98d30'),
        'wxid' => '',
        'name' => '丫摇店主'
    ],

    'test' => [
        'appid' => env('TEST.APPID', 'wxe4df120141d368b3'),
        'appsecret' => env('TEST.APPSECRET', 'c5cba944816882115db29328fd033ecd'),
        'wxid' => '',
        'name' => '微信公众平台测试号'
    ],

    //TODO
    'template_id' => [

        'deliver' => '-bmHPAozYl4gZZsfmz7cYK6WSL-iBUyXTxlPaIUUtNc', // 通知买家已发货消息模板

        'new_order' => 'h5w8UQLjWCw-BzIUPgW6kTmugCIJPEYT5D4oD2lwVJE', // 下单，新订单生成通知买家消息模板

        'new_order_to_seller'=>'pUpNlyuZEZ_fDdifVt6rIKgYrFDrQ9k96QnwfcwJ3zg',//新订单通知卖家

        'cancel_order' => 'kANviOGJXcj2_jY_n7V2zds5bj0CtTbgzUdxM5g3Cmg' // 订单取消通知买家消息模板

    ],

    'industry' => '{"industry_id1":"1","industry_id2":"4"}',

    //TODO
    'menu' => [

        'buyer' => [
            'button' => [
                [
                    'name' => urlencode("了解"),
                    'sub_button' => [
                        [
                            'type' => 'view',
                            'name' => urlencode('今日爆款'),
                            'url' => 'http://shop.yayao.mobi/yayao'
                        ],
                        [
                            'type' => 'view',
                            'name' => urlencode('买家入门'),
                            'url' => 'http://www.yayao.mobi/'
                        ],
                        [
                            'type' => 'view',
                            'name' => urlencode('我要当店主'),
                            'url' => 'http://www.yayao.mobi/'
                        ]
                    ]
                ],
                [
                    'type' => 'view',
                    'name' => urlencode("订单历史"),
                    'url' => 'http://shop.yayao.mobi/buyer/orders/list'
                ],
                [
                    'type' => 'view',
                    'name' => urlencode("小店收藏"),
                    'url' => 'http://shop.yayao.mobi/buyer/favorites'
                ]
            ]
        ],

        'seller' => [
            'button' => [
                [
                    'type' => 'click',
                    'name' => urlencode("关于"),
                    'key' => 'BUYER_ABOUT'
                ],
                [
                    'type' => 'view',
                    'name' => urlencode("订单管理"),
                    'url' => 'http://www.yayao.mobi/'
                ],
                [
                    'type' => 'view',
                    'name' => urlencode("店铺收藏"),
                    'url' => 'http://www.yayao.mobi/'
                ]
            ]
        ]
    ]

];
