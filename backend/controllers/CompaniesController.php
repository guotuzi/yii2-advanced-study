<?php

namespace backend\controllers;

use Yii;
use backend\models\Companies;
use backend\models\Branches;
use backend\models\CompaniesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompaniesController implements the CRUD actions for Companies model.
 */
class CompaniesController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Companies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompaniesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Companies model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Companies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Companies();
        $branch = new Branches();

        if ($model->load(Yii::$app->request->post()) && $branch->load(Yii::$app->request->post())) {

            //get the instance of the file  获取文件实例
            $imgName = $model->company_name;

            // 判断 file 字段不为空的话，才保存文件路径等
            if(!empty($model->file)){
                $model->file = UploadedFile::getInstance($model, 'file');
                $model->file->saveAs( "uploads/".$imgName.'.'.$model->file->extension );  //文件实际保存的路径
                // save the path in the db column   在DB列中保存路径
                $model->logo = "uploads/".$imgName. '.'.$model->file->extension; //将文件路径保存到数据库中
            }

            $model->company_created_date = date('Y-m-d h:m:s');
            $model->save();

            // 设置保存同页面的 branch 内容用，要获取 company_id
            $branch->companies_company_id = $model->company_id;
            $branch->branch_created_date = date('Y-m-d h:m:s');
            $branch->save();

            return $this->redirect(['view', 'id' => $model->company_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'branch' => $branch,
            ]);
        }
    }

    /**
     * Updates an existing Companies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->company_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Companies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Companies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Companies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Companies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
