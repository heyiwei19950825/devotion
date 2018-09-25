<?php

namespace app\admin\controller;

use app\common\helper;
use QL\Ext\PhantomJs;
use QL\QueryList;
use think\Controller;
use think\Request;
use app\common\model\Music as MusicModel;
use app\admin\lib\Music as MusicLib;

class Music extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if( $this->request->isPost() ){



        $musicModel = new MusicModel();




        //歌单列表
            //https://c.y.qq.com/splcloud/fcgi-bin/fcg_get_diss_by_tag.fcg?picmid=1&rnd=0.4248334486805254&g_tk=2033723290&jsonpCallback=getPlaylist&loginUin=492007413&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&categoryId=10000000&sortId=5&sin=0&ein=29

        //分类详情内容
        //https://u.y.qq.com/cgi-bin/musicu.fcg?callback=recom5393339011888723&g_tk=2033723290&jsonpCallback=recom5393339011888723&loginUin=492007413&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&data={"comm":{"ct":24},"playlist":{"method":"get_playlist_by_category","param":{"id":8,"curPage":1,"size":40,"order":5,"titleid":8},"module":"playlist.PlayListPlazaServer"}}






        //歌单数据  未知  https://c.y.qq.com/splcloud/fcgi-bin/fcg_musiclist_getmyfav.fcg?dirid=201&dirinfo=1&g_tk=2033723290&jsonpCallback=MusicJsonCallback9470119853749419&loginUin=492007413&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0
            $MusicLib = new MusicLib();
            $row = $MusicLib->getQQMusicHomeData();
        }else{


            return $this->fetch();
        }

    }

    /**
     * 获取音乐分类
     *
     * @return \think\Response
     */
    public function getCategory(){
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        //

        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
