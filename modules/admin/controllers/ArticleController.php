<?php

namespace app\modules\admin\controllers;

use app\models\Article;
use app\models\ArticleSearch;
use app\models\Category;
use app\models\ImageUpload;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
     * Lists all Article models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
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


    // из формы все значения поступают в этот action
    // все поля из таблицы Article в БД становятся свойствами объекта класса Article
    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($this->request->isPost) {
            // $model->load - автоматически подставлять значения из формы в соответствующие свойства
            // чтобы вручную не прописывать для каждого свойства, например: $model-title = $_POST['Article']['title']
            // $model-save() - сохранение в базу (перед сохранением мы попадаем в метод rules класса Article для валидации полученных данных)
            if ($model->load($this->request->post()) && $model->save()) {
                // если успешно загрузились и сохранились все данные, то перенаправляем на страницу с созданной статьёй
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * @throws NotFoundHttpException
     */
    public function actionSetImage($id)
    {
        $model = new ImageUpload;

        if ($this->request->isPost) {
            // в $article записывается статья из БД, как объект Article методом findModel по id статьи
            $article = $this->findModel($id);

            // UploadedFile - встроенный в yii класс, представляет информацию для загруженного файла
            // getInstance - статический метод который возвращает файл
            $file = UploadedFile::getInstance($model, 'image');

            // $model->uploadFile($file) возвратит название картинки
            // saveImage сохранит в БД название картинки в поле image
            // и передадим
            if (!is_null($file) && $article->saveImage($model->uploadFile($file, $article->image))) {
                return $this->redirect(['view', 'id' => $article->id]);
            }
        }

        return $this->render('image', ['model' => $model]);
    }

    public function actionSetCategory($id)
    {
        $article = $this->findModel($id); // цепляем нашу статью
        $selectedCategory = $article->category->id; // готовим значение для формы
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title'); // выбираем текущий id (тут готовим список)

        if($this->request->isPost) {
            $category = $this->request->post('category');         // ловится выбранное значение в dropdown по названию category
            if ($article->saveCategory($category)) {                    // передаём методу saveCategory который возвращает true если связь установлена
                return $this->redirect(['view', 'id' => $article->id]); // если связь установлена редиректим на вью статьи
            }
        }

        return $this->render('category', [ // все данные передаём в вид
            'article' => $article,
            'selectedCategory' => $selectedCategory,
            'categories' => $categories
        ]);
    }
}
