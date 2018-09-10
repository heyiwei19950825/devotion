<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 16:13
 */

namespace app\modules\mch\models;


use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\models\Share;
use app\models\User;
use app\models\YyOrder;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class ShareListForm extends Model
{
    public $store_id;

    public $page;
    public $limit;
    public $status;
    public $keyword;
    public $seller_comments;

    public function rules()
    {
        return [
            [['keyword', 'seller_comments'], 'trim'],
            [['page', 'limit', 'status'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page'], 'default', 'value' => 1],
            [['seller_comments'], 'string'],
        ];
    }


    public function getList()
    {
        if ($this->validate()) {
            //清楚错误数据
            $error_user = User::find()->alias('u')->where(['u.store_id' => $this->store_id, 'u.is_delete' => 0, 'u.is_distributor' => 2])
                ->leftJoin(Share::tableName() . ' s', 's.user_id=u.id and s.is_delete=0')->andWhere('s.id is null')->select('u.id')->asArray()->column();
            User::updateAll(['is_distributor' => 0], ['in', 'id', $error_user]);

            $query = Share::find()->alias('s')
                ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
                ->leftJoin('{{%user}} u', 'u.id=s.user_id')
                ->andWhere(['u.is_delete' => 0])
                ->andWhere(['in', 's.status', [0, 1]]);
            if ($this->keyword) {
                $query->andWhere([
                    'or',
                    ['like', 's.name', $this->keyword],
                    ['like', 'u.nickname', $this->keyword],
                ]);
            }
            if ($this->status == 0 && $this->status != '') {
                $query->andWhere(['s.status' => 0]);
            }
            if ($this->status == 1) {
                $query->andWhere(['s.status' => 1]);
            }
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
            {
                $orderCount = Order::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0, 'is_recycle' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $msOrderCount = MsOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0, 'is_cancel' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $ptOrderCount = PtOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
                $yyOrderCount = YyOrder::find()->where([
                    'store_id' => $this->store_id, 'is_delete' => 0
                ])->andWhere('user_id = u.id')->select('count(1)');
            }
            $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('s.status ASC,s.addtime DESC')
                ->select([
                    's.*', 'u.nickname', 'u.avatar_url', 'u.time', 'u.price', 'u.total_price', 'u.id user_id', 'u.parent_id',
                    'order_count' => $orderCount, 'ms_order_count' => $msOrderCount, 'pt_order_count' => $ptOrderCount, 'yy_order_count' => $yyOrderCount, 
                    'parent_nickname' => User::find()->alias('parent')->where('parent.id = u.parent_id')->select('nickname')
                ])
                ->orderBy(['total_price' => SORT_DESC])->asArray()->all();
            /*
            foreach ($list as $index => &$value) {
                //$value['parent'] = User::findOne(['id'=>$value['parent_id']]);
                $value['first'] = 0;
                $value['second'] = 0;
                $value['third'] = 0;
                $f_children = Share::getChildren($value['user_id']);
                if (!$f_children)
                    continue;
                $value['first'] += count($f_children);
                foreach ($f_children as $A => $a) {
                    $s_children = Share::getChildren($a['id']);
                    if (!$s_children)
                        continue;
                    $value['second'] += count($s_children);
                    foreach ($s_children as $b) {
                        $t_children = Share::getChildren($b['id']);
                        if (!$t_children)
                            continue;
                        $value['third'] += count($t_children);
                    }
                }
            }
            return [$list, $pagination];
            */

            $team_list = $this->getTeam();
            foreach($list as $index=>&$value){
                $value['first'] = 0;
                $value['second'] = 0;
                $value['third'] = 0;
                foreach($team_list as $team){
                    if($team['user_id'] == $value['user_id']){
                        $value['first'] = isset($team['firstChildren']) ? count($team['firstChildren']) : 0;
                        $value['second'] = isset($team['secondChildren']) ? count($team['secondChildren']) : 0;
                        $value['third'] = isset($team['thirdChildren']) ? count($team['thirdChildren']) : 0;
                        break;
                    }
                }

            }
            return [
                'list'=>$list,
                'team_list'=>$team_list,
                'pagination'=>$pagination
            ];
        } else {
            return $this->getModelError();
        }
    }

    //无效
    public function getList1()
    {
        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
            ->leftJoin(User::tableName() . ' u', 'u.id=s.user_id')
            ->joinWith('firstChildren')->groupBy('s.id');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('s.addtime DESC')
            ->select(['s.*', 'u.nickname', 'u.avatar_url', 'u.time', 'u.price', 'u.total_price',])->asArray()->all();
        $new_list = $list;
        foreach ($list as $index => $value) {
            $list[$index]['first'] = count($value['firstChildren']);
            foreach ($value['firstChildren'] as $i => $v) {
                $list[$index]['firstChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                foreach ($new_list as $j => $item) {
                    if ($v['id'] == $item['user_id']) {
                        $list[$index]['second'] = $new_list[$j]['firstChildren'];
                    }
                }
            }
        }
        $new_list = $list;
        foreach ($list as $index => $value) {
            if (isset($value['secondChildren']) && is_array($value['secondChildren'])) {
                foreach ($value['secondChildren'] as $i => $v) {
                    $list[$index]['secondChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                    foreach ($new_list as $j => $item) {
                        if ($v['id'] == $item['user_id']) {
                            $list[$index]['thirdChildren'] = $new_list[$j]['firstChildren'];
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function getTeam()
    {
        //获取有一级下线的分销商
        $query = Share::find()->alias('s')
            ->where(['s.is_delete' => 0, 's.store_id' => $this->store_id])
//            ->leftJoin(User::tableName() . ' u', 'u.id=s.user_id')
            ->joinWith('firstChildren')->groupBy('s.id');
        $userQuery = User::find()->where('s.user_id=id')->select('nickname');
        $list = $query->select([
            's.*','nickname'=>$userQuery
        ])->asArray()->all();
        $new_list = $list;
        //获取二级下线
        foreach ($list as $index => $value) {
            $res = [];
            foreach ($value['firstChildren'] as $i => $v) {
                $list[$index]['firstChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                foreach ($new_list as $j => $item) {
                    if ($v['id'] == $item['user_id']) {
//                            $list[$index]['secondChildren'] = $new_list[$j]['firstChildren'];
                        $res = array_merge($res, $new_list[$j]['firstChildren']);
                    }
                }
            }
            $list[$index]['secondChildren'] = $res;
        }
        $new_list = $list;
        foreach ($list as $index => $value) {
            $res = [];
            if (isset($value['secondChildren']) && is_array($value['secondChildren'])) {
                foreach ($value['secondChildren'] as $i => $v) {
                    $list[$index]['secondChildren'][$i]['time'] = date('Y-m-d', $v['addtime']);
                    foreach ($new_list as $j => $item) {
                        if ($v['id'] == $item['user_id']) {
//                            $list[$index]['thirdChildren'] = $new_list[$j]['firstChildren'];
                            $res = array_merge($res, $new_list[$j]['firstChildren']);
                        }
                    }
                }
            }
            $list[$index]['thirdChildren'] = $res;
        }
        return $list;
    }


    public function getCount()
    {
        $list = Share::find()
            ->select([
                'sum(case when status = 0 then 1 else 0 end) count_1',
                'sum(case when status = 1 then 1 else 0 end) count_2',
                'sum(case when status != 2 then 1 else 0 end) total'
            ])
            ->where(['is_delete' => 0, 'store_id' => $this->store_id])->asArray()->one();
        return $list;
    }
}