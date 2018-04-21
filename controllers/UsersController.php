<?php

namespace app\controllers;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\AuthAssignment;
use app\models\AuthItem;
use app\models\System;
use app\models\User;
use app\models\Users;
use app\models\UserSearch;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create',],
                        'allow' => true,
                        'roles' => ['Admin',],
                    ],
                    [
                        'actions' => ['index', 'view', 'update'],
                        'allow' => true,
                        'roles' => ['Booker',],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        //TODO поправить этот кусок
        if (!empty(Yii::$app->request->post('hasEditable'))) {
            //print_r(Yii::$app->request->post());
            $request = Yii::$app->request->post();
            if($request['editableAttribute']=='roleName'){
                $priority = System::getRolePriority();
                $currentUser = Users::find()->where(['id'=>Yii::$app->user->id])->one();
                $user = Users::find()->where(['id'=>$request['editableKey']])->one();
                if(!empty($user) && !empty($user->role)){

                    if($priority[$user->role->item_name]>$priority[$currentUser->role->item_name] || $user->id==Yii::$app->user->id){//может обновлять только тех у кого приоритет меньше
                        if(!empty(Yii::$app->request->post('AuthAssignment'))){
                            $auth = AuthAssignment::find()->where(['user_id'=>$user->id])->one();
                            if(empty($auth)){
                                $auth = new AuthAssignment();
                            }
                            $auth->load(Yii::$app->request->post());
                            if($auth->save(true)){
                                $output = $auth->itemName->ru_name; $message = 'Сохранена';
                            }
                        }
                        else{
                            $output = ''; $message = 'Не найдена новая роль';
                        }
                    }
                    else{
                        $output = ''; $message = 'Не хватает прав';
                    }
                }
                else{
                    $output = ''; $message = 'Не найден пользователь';
                }
                $out = Json::encode(['output'=>$output, /*'message'=>$message*/]);
                echo $out;
                return;
            }
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Users();
        $modelRole = AuthItem::find()->all();
        var_dump(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(true)) {
                $auth = AuthAssignment::find()->where(['user_id' => $model->id])->one();
                if (empty($auth)) {
                    $auth = new AuthAssignment();
                }
                $auth->load(Yii::$app->request->post());
                $auth->user_id = $model->id;
                $auth->description = 'set from web';
                $auth->save(true);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                System::mesprint($model->errors);die();
                System::txtLogs($model->errors, 'createUsers');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'modelRole' => $modelRole,
        ]);
    }
    public function actionUpdate($id)
    {
        $priority = System::getRolePriority();
        $currentUser = Users::find()->where(['id'=>Yii::$app->user->id])->one();
        //при обновдлении не менять роль
        $model = $this->findModel($id);
        if(!empty($model->role)){
            if($priority[$model->role->item_name]>$priority[$currentUser->role->item_name] || $model->id==Yii::$app->user->id){//может обновлять только тех у кого приоритет меньше
                $modelRole = AuthItem::find()->all();
                if(Yii::$app->request->isPost){
                    if(!empty(Yii::$app->request->post('AuthAssignment'))){
                        $auth = AuthAssignment::find()->where(['user_id'=>$id])->one();
                        if(empty($auth)){
                            $auth = new AuthAssignment();
                        }
                        $auth->load(Yii::$app->request->post());
                        $auth->save(true);
                    }
                }
                if ($model->load(Yii::$app->request->post())) {
                    if($model->save(true)){
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
                return $this->render('update', [
                    'model' => $model,
                    'modelRole' => $modelRole,
                ]);
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
