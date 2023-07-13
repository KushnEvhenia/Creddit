<?php

namespace backend\controllers;

use app\models\Transaction;
use app\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;
use app\models\Account;
/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * @inheritDoc
     */
    
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Transaction models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $model = new Transaction();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Transaction();
        $model->scenario = Transaction::SCENARIO_TRANSFER;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $account_receiver = Account::find()->where(['id' => $model->id_to])->one();
                $account_receiver->current_amount += $model->amount_to;
                $account_receiver->update();
                $account_sender = Account::find()->where(['id' => $model->id_from])->one();
                $account_sender->current_amount -= $model->amount_from;
                $account_sender->update();
                $model->type = 'Transfer';
                $model->user_id = \Yii::$app->user->id;
                $model->file = UploadedFile::getInstances($model, 'file');
                $files_arr = $model->file;
                $file_str = '';
                foreach( $files_arr as $file){
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                    $file_str = $file_str.$file->name.',';
                }
                
                $model->file = $file_str;
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
        
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionExpense($id){

        if($id !== ''){
            $title = "Update Transaction: ";
            $model = $model = $this->findModel($id);
            $action = 'update';
            $model->updated_id = \Yii::$app->user->id;
            $model->id_to = null;
            $model->amount_to = null;

        }
        else{
            $title = 'Create Transaction';
            $model = new Transaction();
            $action = 'create';
            $model->user_id = \Yii::$app->user->id;
        }
        
        $model->scenario = Transaction::SCENARIO_EXPENSE;
        $files = $model->file;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $account_sender = Account::find()->where(['id' => $model->id_from])->one();
            $account_sender->current_amount -= $model->amount_from;
            $account_sender->update();
            $model->type = 'Expense';
            if(!empty(UploadedFile::getInstances($model, 'file'))){
                $model->file = UploadedFile::getInstances($model, 'file');
                $files_arr = $model->file;
                $file_str = '';
                foreach($files_arr as $file){
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                    $file_str = $file_str.$file->name.',';
                }
                $model->file = $file_str;
            }else{
                $model->file = $files;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        
        else {
            $model->loadDefaultValues();
        }

        }

        return $this->render('expense', [
            'model' => $model, 
            'title' => $title,
            'action' => $action,
        ]);

    }

    public function actionIncome($id){

        if($id !== ''){
            $title = "Update Transaction: ";
            $model = $model = $this->findModel($id);
            $action = 'update';
            $model->updated_id = \Yii::$app->user->id;
            $model->id_from = null;
            $model->amount_from = null;
        }
        else{
            $title = 'Create Transaction';
            $model = new Transaction();
            $action = 'create';
            $model->user_id = \Yii::$app->user->id;
        }

        $model->scenario = Transaction::SCENARIO_INCOME;
        $files = $model->file;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $account_receiver = Account::find()->where(['id' => $model->id_to])->one();
            $account_receiver->current_amount += $model->amount_to;
            $account_receiver->update();
            $model->type = 'Income';
            if(!empty(UploadedFile::getInstances($model, 'file'))){
                $model->file = UploadedFile::getInstances($model, 'file');
                $files_arr = $model->file;
                $file_str = '';
                foreach($files_arr as $file){
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                    $file_str = $file_str.$file->name.',';
                }
                $model->file = $file_str;
            }else{
                $model->file = $files;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                $model->loadDefaultValues();
            }

        }

        return $this->render('income', [
            'model' => $model, 
            'title' => $title,
            'action' => $action,
        ]);

    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
    
        $model = $this->findModel($id);
        $files = $model->file;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updated_id = \Yii::$app->user->id;
            if(!empty(UploadedFile::getInstances($model, 'file'))){
                $model->file = UploadedFile::getInstances($model, 'file');
                $files_arr = $model->file;
                $file_str = '';
                foreach($files_arr as $file){
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                    $file_str = $file_str.$file->name.',';
                }
                $model->file = $file_str;
            }else{
                $model->file = $files;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
