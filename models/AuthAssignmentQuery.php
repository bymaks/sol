<?php
namespace app\models;
/**
 * This is the ActiveQuery class for [[AuthAssignment]].
 *
 * @see AuthAssignment
 */
class AuthAssignmentQuery extends \yii\db\ActiveQuery
{
    public static function getDb()
    {
        return \Yii::$app->db;
    }
    public function all($db = null)
    {
        return parent::all($db);
    }
    public function one($db = null)
    {
        return parent::one($db);
    }
}