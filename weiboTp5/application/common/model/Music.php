<?php

namespace app\common\model;

use think\Exception;
use think\Model;

class Music extends Model
{
    protected $autoWriteTimestamp   = 'datetime';
    protected $createTime           = 'create_time';

    public function createData( $music= [] ){
        $newSong    = $music['newSong'];

        foreach( $newSong as $k=> $v){
            $createData[] = $v;
                try{
                    $row = $this->allowField(true)->saveAll( $newSong, false );
                }catch ( Exception $e ){
                    if( !strstr($e,'PRIMARY' )){
                        continue;
                    }
                }
        }

    }
}
