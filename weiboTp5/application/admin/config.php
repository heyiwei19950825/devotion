<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/11
 * Time: 14:16
 */

return [
    'template'      => [
        'view_path'=>'../themes/admin/'
    ],
    'QQMusic'       =>[
        'hotSearch' =>[
            'name'      => '热门搜索',
            'method'    => 'GET',
            'params'    => [
                'g_tk' => '2033723290',//可选
                'jsonpCallback' => 'hotSearchKeysmod_top_search',//可选
                'loginUin' => '492007413',//可选
                'hostUin' => '0',//可选
                'format' => 'jsonp',//可选
                'inCharset' => 'utf8',//可选
                'outCharset' => 'utf-8',//可选
                'notice' => '0',//可选
                'platform' => 'yqq',//可选
                'needNewCode' => '0',//可选
            ],
            'url'       => 'https://c.y.qq.com/splcloud/fcgi-bin/gethotkey.fcg'
        ],

        'homeData'  =>[
            'name'      => '首页数据',
            'method'    => 'GET',
            'params'    => [
                'callback'      => 'recom5393339011888723',
                'g_tk'          => '2033723290',
                'jsonpCallback' => 'recom5393339011888723',
                'loginUin'      => '492007413',
                'hostUin'       => '0',
                'format'        => 'jsonp',
                'inCharset'     => 'utf8',
                'outCharset'    => 'utf-8',
                'notice'        => '0',
                'platform'      => 'yqq',
                'needNewCode'   => '0',
                'data'          => '{"comm":{"ct":24},"category":{"method":"get_hot_category","param":{"qq":""},"module":"music.web_category_svr"},"recomPlaylist":{"method":"get_hot_recommend","param":{"async":1,"cmd":2},"module":"playlist.HotRecommendServer"},"playlist":{"method":"get_playlist_by_category","param":{"id":8,"curPage":1,"size":40,"order":5,"titleid":8},"module":"playlist.PlayListPlazaServer"},"new_song":{"module":"QQMusic.MusichallServer","method":"GetNewSong","param":{"type":0}},"new_album":{"module":"music.web_album_library","method":"get_album_by_tags","param":{"area":7,"company":-1,"genre":-1,"type":-1,"year":-1,"sort":2,"get_tags":1,"sin":0,"num":40,"click_albumid":0}},"toplist":{"module":"music.web_toplist_svr","method":"get_toplist_index","param":{}},"focus":{"module":"QQMusic.MusichallServer","method":"GetFocus","param":{}}}'
            ],
            'url'       => 'https://u.y.qq.com/cgi-bin/musicu.fcg'
        ],

        'topMvData' =>[
            'name'      => '推荐MV',
            'method'    => 'GET',
            'params'    => [
                'g_tk' => '5381',
                'jsonpCallback' => 'MusicJsonCallback9822128199328322',
                'loginUin'      => '492007413',
                'hostUin'       => '0',
                'format'        => 'jsonp',
                'inCharset'     => 'utf8',
                'outCharset'    => 'GB2312',
                'notice'        => '0',
                'platform'      => 'yqq',
                'needNewCode'   => '0',
                'cmd'           => 'shoubo',
                'lan'           => 'all'
            ],
            'url' => 'https://c.y.qq.com/mv/fcgi-bin/getmv_by_tag'
        ],
        'categoryAlbum'          =>[
            'name'  =>  '分类歌单',
            'params'    => [
                'picmid' => '1',
                'rnd' => '0.07278768346894782',
                'g_tk' => '2033723290',
                'jsonpCallback' => 'getPlaylist',
                'loginUin' => '492007413',
                'hostUin' => '0',
                'format' => 'jsonp',
                'inCharset' => 'utf8',
                'outCharset' => 'utf-8',
                'notice' => '0',
                'platform' => 'yqq',
                'needNewCode' => '0',
                'categoryId' => '10000000',
                'sortId' => '5',
                'ein' => '29',
            ],
            'url'   => 'https://c.y.qq.com/splcloud/fcgi-bin/fcg_get_diss_by_tag.fcg'
        ]
    ],
    'Api'           =>[
        'switchQQMusicQuality'  => [
            'name'      => 'QQ音乐转换为高品质',
            'url'       => 'http://www.douqq.com/qqmusic/qqapi.php',
            'method'    => 'POST',
            'params'    =>  [
                'mid'   => 0
            ]
        ],
        'musicCategoryUrl'           =>[
            '',
            'https://y.qq.com/portal/playlist.html',

        ]
    ]
];