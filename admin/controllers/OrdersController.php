<?php

namespace admin\controllers;

use admin\models\Runners;
use admin\models\User;
use Yii;
use admin\models\Orders;
use admin\models\OrdersSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSave(){
        $items = Yii::$app->request->post('products_ids');
        $name = Yii::$app->request->post('name');
        $address = Yii::$app->request->post('address');
        $card = Yii::$app->request->post('card');
        $delivery = Yii::$app->request->post('delivery');
        $phone = Yii::$app->request->post('phone');
        $taken = Yii::$app->request->post('taken');

        $model = new Orders();
        $model->client_name = $name;
        $model->client_address = $address;
        $model->is_card = $card;
        $model->delivery_price = $delivery;
        $model->client_phone = $phone;
        $model->taken = $taken;
        if (sizeof($items)>1){
            $model->items = implode(' ', $items);
        } else{
            $model->items = $items[0];
        }
        $this->Fire();
        $model->save();

        return $this->redirect('index');
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->Fire();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function Fire(){
        $service = new FirebaseNotifications(['authKey' =>
            'AAAAQP3DF58:APA91bFtdKrL5OaKFd-tXmygfrm_nG607zD9oZRELzQPwb1K_T1OQcSyBXjdJpAeHLGQMchajRvAkX3EGSvXP7YpkfxZdfg_AJ_EOQ1hGJiOb1cEdfcmsEpdvmb8VVByGGglSZyV7vhG']);

        $all_users = Runners::find()->where(['!=','device_id','Null'])->andwhere(['!=','device_id',' '])->all();
        $tokens = [];
        foreach ($all_users as $users) {
            $tokens[] = $users['device_id'];
        }
        $message = array('title' => 'EzShop', 'body' => 'Новый заказ!');
        $service->sendNotification($tokens, $message);

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
