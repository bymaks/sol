<?php
namespace app\models;
use app\models\Telegramm;
use Yii;
class System extends \yii\db\ActiveRecord
{
    public static function mesprint($t){
        echo "<pre>";
        print_r($t);
        echo "</pre>";
    }
    public static function sendSmsMTS($phone, $message){
        $phone = '7'.preg_replace('/\D|(\G7)|(\G8)/','',$phone);
        // Загрузка данных;
        $c = curl_init(Yii::$app->params['smsUrl']);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 30);
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'operation=send&login='.Yii::$app->params['smsLogin'].'&onum='.Yii::$app->params['smsOnum'].'&unum='.str_replace('+', '', $phone).'&msg='.urlencode($message).'&sign='.sha1(str_replace('+', '', $phone).urlencode($message).Yii::$app->params['smsPass']));
        $data = curl_exec($c);
        curl_close($c);
        // Вывод данных;
        return $data;
    }

    public static function sendSms($phone, $message){
        $phone = '7'.preg_replace('/\D|(\G7)|(\G8)/','',$phone);
        $c = curl_init(Yii::$app->params['beeline']['sms']['hostname']);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 30);
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'CLIENTADR=false&HTTP_ACCEPT_LANGUAGE=false&action=post_sms&message='.mb_convert_encoding($message, 'CP-1251').'&sender=SENDSMS&sender=EXTREMEFIT&target='.$phone.'&user='.Yii::$app->params['beeline']['sms']['login'].'&pass='.Yii::$app->params['beeline']['sms']['password']);
        $data = curl_exec($c);
        curl_close($c);
        // Вывод данных;
        return $data;
    }


    public static function sendTelegrammPerconal($text =''){
        if(!empty($text)){
            $tel = new Telegramm();
            $tel->sendMessage(106705570, $text);//YA
        }
        return false;
    }

    public static function txtLogs($obj, $model){
        $file = "----------------------------------------------------\n------------------------START-----------------------\n";
        $fileName =  $model.'_'.time().'_'.rand(0, 1000).'.txt';
        $file.=  time(). '--'.Date('Y.m.d H:i:s'."\n", time()). "\n";
        $file.= var_export($obj, true);
        $dirName =$_SERVER['DOCUMENT_ROOT'] . '/logs/errors/'.Date('Y-m-d', time());
        if(!file_exists($dirName)){
            mkdir($dirName);
        }
        file_put_contents($dirName.'/'.$fileName, $file."\n");
    }
    public static function txtDebug($obj, $model){
        $file = "----------------------------------------------------\n------------------------START-----------------------\n";
        $fileName =  $model.'_'.time().'_'.rand(0, 1000).'.txt';
        $file.=  time(). '--'.Date('Y.m.d H:i:s'."\n", time()). "\n";
        $file.= var_export($obj, true);
        $dirName =$_SERVER['DOCUMENT_ROOT'] . '/logs/debug/'.Date('Y-m-d', time());
        if(!file_exists($dirName)){
            mkdir($dirName);
        }
        file_put_contents($dirName.'/'.$fileName, $file."\n");
    }
    public static function txtLogsConsole($obj, $model){
        $file = "----------------------------------------------------\n------------------------START-----------------------\n";
        $fileName =  $model.'_'.Date('Y-m-d',time()).'_'.rand(0, 1000).'.txt';
        $file.=  time(). '--'.Date('Y.m.d H:i:s'."\n", time()). "\n";
        $file.= var_export($obj, true);
        $dirName ='/home/ef/logs/console_'.Date('Y-m-d', time());
        if(!file_exists($dirName)){
            mkdir($dirName);
        }
        file_put_contents($dirName.'/'.$fileName, $file."\n");
    }
    // Окончание для числительных;
    public static function numToStr($num, $end1, $end2, $end3) {
        $num100 = $num % 100;
        $num10 = $num % 10;
        if ($num100 >= 5 && $num100 <= 20) $end = $end3;
        else if ($num10 == 0) $end = $end3;
        else if ($num10 == 1) $end = $end1;
        else if ($num10 >= 2 && $num10 <= 4) $end = $end2;
        else if ($num10 >= 5 && $num10 <= 9) $end = $end3;
        else $end = $end3;
        return number_format($num, 0, '.', ' ').' '.$end;
    }
    // Сокращеный чисел;
    public static function numberSize($size)
    {
        $name = array("", "К", "М", "Г", "Т", "П", "Э", "З", "И");
        return $size ? round($size / pow(1000, ($i = floor(log($size, 1000)))), 2) .' '. $name[$i] : '0';
    }
    public static function getRolePriority($name=false, $result=[]){
        if(empty($name)){
            $authItems = AuthItem::find()
                ->select('auth_item.*')
                ->leftJoin('auth_item_child', 'auth_item_child.child = auth_item.name')
                ->groupBy('auth_item.name')
                ->having(['=','COUNT(auth_item_child.parent)',0])
                ->where('1=1')->all();
        }
        else{
            $authItems = AuthItem::find()
                ->select('auth_item.*')
                ->from('auth_item, auth_item_child')
                ->where('auth_item.name = auth_item_child.child')
                ->andWhere(['auth_item_child.parent' => $name])
                ->groupBy('auth_item.name')
                ->all();
        }
        if(!empty($authItems)){
            foreach ($authItems as $authItem){
                $result[$authItem->name]=count($result);
                $result = self::getRolePriority($authItem->name,$result);
            }
        }
        return $result;
    }

    public static function refreshSummaryOrderInfo($orderSession=[]){
        $result = [];
        $summ=0;
        $discont =0;//скидка
        $minuts = 0; // всего минут
        $seasonTiket=false;
        if(!empty($orderSession)){
            if(!empty($orderSession['order']['items'])){
                if(!empty($orderSession['order']['seasonTiket'])){
                    $seasonTiket = SeasonTikets::find()->where(['id'=>$orderSession['order']['seasonTiket'], 'status'=>1])->andWhere(['>', 'minute_balance', 0])->one();
                    if(!empty($seasonTiket)){
                        unset($orderSession['order']['seasonTiket']);
                    }
                }
                foreach ($orderSession['order']['items'] as $item){
                    if(!empty($item['goodId'])){
                        $good = Goods::find()->where(['id'=>$item['goodId']])->one();
                        if(!empty($good)){
                            $summ += $good->price*$item['count'];
                            if(in_array($good->category_id, [Yii::$app->params['categoryMinut']])){
                                $minuts += $item['count'];
                                //считаем минуты минус минуты с абонемента
                                $summ += $good->price*( (($item['count']-$seasonTiket->minute_balance)<0?0:$item['count']-$seasonTiket->minute_balance));
                            }
                            else{
                                $summ += $good->price*$item['count'];
                            }
                        }
                    }
                }
                $result['order']['items']=$orderSession['order']['items'];
            }
        }
        $result['order']['createBy']=Yii::$app->user->id;
        $result['order']['discont']=$discont;// скидак
        $result['order']['seasonTiket'] = (!empty($orderSession['order']['seasonTiket'])?$orderSession['order']['seasonTiket']:false);
        $result['order']['minuts'] = $minuts;
        $result['order']['summ'] = $summ;
        $result['order']['unique']=password_hash(rand(1,10000), PASSWORD_BCRYPT);
        return $result;
    }

}