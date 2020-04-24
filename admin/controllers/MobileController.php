<?php


namespace admin\controllers;


use common\models\CategoryContentModel;
use common\models\MobileNotes;
use common\models\MobileUsers;
use yii\web\Controller;
use function GuzzleHttp\Promise\all;

class MobileController extends Controller
{
    public function actionLogin(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $user = MobileUsers::findOne(['login'=>$login, 'password'=>$password]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($user){
            return [
                'success' => true,
                'user' => $user
            ];
        }
        return [
            'success' => false
        ];
    }
    public function actionRegister(){
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');

        $user = new MobileUsers(['login'=>$login, 'password'=>$password]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($user->save()){
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }
    public  function actionGetCatalog(){}

    public function actionAddNote(){
        $note = \Yii::$app->request->post('note');
        $user_id = \Yii::$app->request->getHeaders()['token'];
        $notes_count = MobileNotes::find()->where(['user_id'=>$user_id])->count();
        if ($notes_count >= 6){
            $min_id = MobileNotes::find()->where(['done' => 1, 'user_id' => $user_id])->min('id');
            $delete_done = \Yii::$app
                ->db
                ->createCommand()
                ->delete('mobile.notes', ['done' => 1, 'id' => $min_id, 'user_id' => $user_id]);
            if ($delete_done->execute()==0){
                return [
                    'success' => false
                ];
            };
        }
        $notes = new MobileNotes();
        $notes->note = $note;
        $notes->user_id = $user_id;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($notes->save()){
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }

    public function actionGetCategoryItem($id){
        $items = CategoryContentModel::find()->where(['category_id'=>$id])->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $items;
    }

    public function actionLike(){
        $id = \Yii::$app->request->post('id');
        $user_id = \Yii::$app->request->getHeaders()['token'];

        $user = MobileUsers::findOne(['id'=>$user_id]);
        $user->favourite .= $id . ";";
        $user->save();
    }

    public function actionRemoveFav(){
        $user_id = \Yii::$app->request->getHeaders()['token'];
        $user = MobileUsers::findOne(['id'=>$user_id]);
        $id = \Yii::$app->request->post('id');

        $favs = explode(";", $user->favourite);
        $res = $this->remove_element($favs, $id);
        $user->favourite = implode(';', $res);

        $user->save();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success'=>true];
    }
    function remove_element($array,$value) {
        return array_diff($array, (is_array($value) ? $value : array($value)));
    }

    public function actionGetFavourite(){
        $user_id = \Yii::$app->request->getHeaders()['token'];
        $user = MobileUsers::findOne(['id'=>$user_id]);

        $content = CategoryContentModel::find()->where(['in', 'id', explode(';',$user->favourite)])->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $content;
    }

    public function actionNoteDone(){
        $id = \Yii::$app->request->post('id');
        $done = \Yii::$app->request->post('done');
        $notes = MobileNotes::findOne(['id'=>$id]);
        $notes->done = $done;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($notes->save()){
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }

    public function actionGetNotes(){
        $user_id = \Yii::$app->request->getHeaders()['token'];
        $notes = MobileNotes::findAll(['user_id'=>$user_id]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $notes;
    }

    public function actionDeleteNote(){
        $id = \Yii::$app->request->post('id');
        $notes = MobileNotes::deleteAll(['id'=>$id]);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($notes){
            return [
                'success' => true
            ];
        }
        return [
            'success' => false
        ];
    }

    public function actionEditProfile(){
        $name = \Yii::$app->request->post("name");
        $surname = \Yii::$app->request->post("surname");
        $age = \Yii::$app->request->post("age");
        $description = \Yii::$app->request->post("description");
        $user_id = \Yii::$app->request->getHeaders()['token'];

        $user = MobileUsers::findOne(['id'=>$user_id]);
        $user->name = $name;
        $user->surname = $surname;
        $user->description = $description;
        $user->age = $age;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($user->save())
            return [
                'success' => true
            ];
    }
}