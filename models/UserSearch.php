<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

class UserSearch extends Users
{
    public function rules(){
        return [
            //[['phone'],  'match', 'pattern'=>'/^[0-9]*$/', 'message'=>'Только цифры'],
            [['id', 'telegramm', 'gender', 'time_out', 'status'], 'integer'],
            [['login',  'email', 'first_name', 'second_name', 'last_name', 'created_at', 'updated_at', 'roleName','phone'], 'safe'],
            [['birthday', 'create_by_user', 'update_by_user',],'safe'],
        ];
    }
    public function scenarios(){
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Users::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'gender' => $this->gender
        ]);
        if(isset($this->birthday) && !empty($this->birthday)){
            $age = $this->birthday;
            $birthyear_begin = date('Y-m-d 00:00:00',strtotime('-'.($this->birthday + 1).' years'));
            $birthyear_end = date('Y-m-d 23:59:59',strtotime('-'.($this->birthday).' years'));
            $query->andFilterWhere(['>=', 'birthday', $birthyear_begin]);
            $query->andFilterWhere(['<=', 'birthday', $birthyear_end]);
        }
        if(isset($this->phone) && !empty(preg_replace('/^[\D|(\G7)|(\G8)]|(\D*)/','',$this->phone))){
            $this->phone = preg_replace('/^[\D|(\G7)|(\G8)]|(\D*)/','',$this->phone);
            $query ->andWhere(['like', 'phone', $this->phone]);
        }

        $query->andFilterWhere(['like', 'login', $this->login])
            ->orFilterWhere(['like', 'first_name', $this->first_name])
            ->orFilterWhere(['like', 'second_name', $this->first_name])
            ->orFilterWhere(['like', 'last_name', $this->first_name]);
//        var_dump($query->createCommand()->getRawSql());die();
        return $dataProvider;
    }
}