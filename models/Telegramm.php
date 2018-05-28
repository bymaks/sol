<?php
namespace app\models;
use Yii;
use yii\base\Model;
/**
 * ContactForm is the model behind the contact form.
 */
class Telegramm extends Model
{
    const BOT_URL = 'https://api.telegram.org/bot346066986:AAENrVyQ5SI4Iak82A3I2Sra0hMzMAWbIr0/';
    const STATUS_ENABLE = 1;
    //функции для нового бота зарегеного на меня
    public function sendMessage($chatId = false, $text = false){
        if(!$chatId && !$text){
            return false;
        }
        $params['chat_id']=$chatId;
        $params['text']=$text;
        $result = $this->sendQuery('sendMessage?'.http_build_query($params));
        if($result['ok']==1){
            return true;
        }
        return false;
    }
    private function sendQuery($url){
        $curl = curl_init(self::BOT_URL.$url);
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPGET => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );
        curl_setopt_array($curl, $options);
        $data = curl_exec($curl);
        $result=json_decode($data, true);
        curl_close($curl);
        return $result;
    }
}