<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\Account;
use app\models\AccountSearch;
use app\models\Category;
use app\models\Counterparty;
use Carbon\Carbon;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function beforeAction( $action ) {
        if ( parent::beforeAction ( $action ) ) {
            if ( $action->id == 'error' ) {
                $this->layout = 'main_login';
            }
            return true;
        } 
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $date_to = Carbon::now()->endOfMonth()->format('Y-m-d');
        if($this->request->post()){
            $date_from = $this->request->post()["from_date"];
            $date_to = $this->request->post()["to_date"];
        }
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false;
        $dataProvider->query->limit(5);
        $AccountsearchModel = new AccountSearch();
        $AccountdataProvider = $AccountsearchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' =>  $searchModel,
            'dataProvider' => $dataProvider,
            'AccountsearchModel' => $AccountsearchModel,
            'AccountdataProvider' => $AccountdataProvider,
            'cat_chart' =>  Category::createChart($date_from, $date_to),
            'counterparty_chart' => Counterparty::createChart($date_from, $date_to),
            'main_chart' => Transaction::createChart($date_from, $date_to),
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
