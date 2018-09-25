<?php
/**
 * Created by PhpStorm.
 * User: 贺宜伟【ewei】
 * Date: 2018/9/19
 * Time: 10:42
 */

namespace app\admin\lib;
use app\common\helper;
use app\common\model\Music as MusicModel;

class Music extends LibBase
{
    //抓取 QQ音乐首页信息
    function getQQMusicHomeData(){
        //首页数据抓取
        $QQMusicConfig      = config('QQMusic');
        $homeDataConfig     = $QQMusicConfig['homeData'];
        $data = helper::http_curl($homeDataConfig['url'], $homeDataConfig['params'],$homeDataConfig['method'] );
        $data = json_decode( trim(str_replace($homeDataConfig['params']['callback'].'(','',$data),')'), true);

        if( $data['code'] == 0 ){//查询成功数据处理
            $hotCategory = $data['category']['data']['category'][0]['items'];//热门分类
            $newSong = $data['new_song']['data']['song_list'];//新歌列表
            $playList = $data['playlist']['data']['v_playlist'];//播放列表
            $recomPlaylist = $data['recomPlaylist']['data']['v_hot'];//推荐歌曲
            $focus = $data['focus']['data']['content'];//轮播图

            //获取多品质音乐
            foreach( $newSong as $k=> &$v ){
                $newUrl = helper::http_curl('http://www.douqq.com/qqmusic/qqapi.php',['mid'=>$v['mid']],'POST');
                $v['url'] = $newUrl;
            }

            $musicData = [
                'hotCategory'   => $hotCategory,
                'newSong'   => $newSong,
                'playList'   => $playList,
                'recomPlaylist'   => $recomPlaylist,
                'focus'   => $focus,
            ];

            $MusicModel = new MusicModel();
            $row = $MusicModel->createData( $musicData );

            return $row;
        }else{

            return false;
        }

    }
}