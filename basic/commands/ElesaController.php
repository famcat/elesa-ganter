<?php
/**
 * @author: anton <antondrelin@gmail.com>
 * Date: 17.09.17
 * Time: 17:16
 */
namespace app\commands;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\console\Exception;
use yii\helpers\Console;
use yii\console\Application;
use GuzzleHttp\Client;
use yii\console\Controller;
use app\models\Types;
use app\models\Productions;
use app\models\Schema_productions;
use app\models\Filter;
use app\models\Filter_article;
use app\models\Filter_data;
use app\models\Color_field;
use app\models\Color_list;
use app\models\Color_value;
use yii\helpers\BaseFileHelper;
/**
 * Контроллер для elesa
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ElesaController extends Controller
{
    const BASE_URL='https://www.elesa-ganter.ru';

    /**
     * Получает все типы продукции
     */
    public function actionTypes()
    {
         $client = new Client();
         $res = $client->request('GET',self::BASE_URL . '/Продукция');
         $body = $res->getBody();
         $document = \phpQuery::newDocumentHTML($body);
         $types = $document->find('.product-group>section>ul>li>a');
         foreach ($types as $type){
             $pq = pq($type);
             $query = Types::find()
                    ->where(['name' => trim($pq->text())])
                    ->asArray()->one();

            if (count($query) == 0){
                 $new_type = new Types();
             }else{
                 $new_type  = Types::findOne($query['id']);
             }
             $new_type->name = trim($pq->text());
             $new_type->url = urldecode(trim($pq->attr('href')));
             $new_type->url_img = $pq->find('img')->attr('src');
             $new_type->save();
         }
        $query = Types::find()->count();
        $this->writeMessage('Types: '.$query);
    }

    /**
     * Получить основные типы
     */
    public function actionProduction(){
        $queryTypes = Types::find()->asArray()->all();

        //Малое обновления
        foreach ($queryTypes as $type){
            $clinet = new Client();
            $res = $clinet->request('GET',self::BASE_URL.$type['url']);
            $body = $res->getBody();
            $document = \phpQuery::newDocumentHTML($body);
            $production_list = $document->find('.product-index-tile');

            foreach ($production_list as $elem){
                $pq = pq($elem);
                $name = trim((String)$pq->find('h2')->text());
                $id_type = $type['id']; //ID продукции

                $url = trim((String)$pq->find('a')->attr('href'));
                $query = Productions::find()
                    ->where(['name' => $name,'url' => $url])
                    ->asArray()->one();

                if (count($query) == 0){
                    $production  = new Productions();
                }else{
                    $production  = Productions::findOne($query['id']);
                }
                $production->name = trim((String)$pq->find('h2')->text());
                $production->description = trim((String)$pq->find('.series-overview-subtitle')->text());
                $production->url = urldecode(trim((String)$pq->find('a')->attr('href')));
                $production->materail = trim((String)$pq->find('.series-overview-subtitle_sec')->text());
                $production->img_url = trim((String)$pq->find('img')->attr('src'));
                $production->types_id = $id_type;
                $production->save();

            }
        }

        $query = Productions::find()->count();
        $this->writeMessage('Count productions: ' . $query);

    }

    /**
     * Получить схемы
     */
    public function actionSchema(){
        $queryProduction = Productions::find()->asArray()->all();

        $this->getSchema($queryProduction);
        $this->productionSchema($queryProduction);
//        $this->getImageSchema($queryProduction);

        $querySchema = Schema_productions::find()->count();
        $this->writeMessage('Schema productions ' . $querySchema);
    }


    private function getSchema($queryProduction){
        foreach ($queryProduction as $production){
            $clinet = new Client();
            $res = $clinet->request('GET',self::BASE_URL.$production['url']);
            $body = $res->getBody();
            $document = \phpQuery::newDocumentHTML($body);

            $productInfo = Productions::findOne($production['id']);
            $productInfo->full_description = trim($document->find('.content')->html());
            $productInfo->save();

            $schema_list = $document->find('.small-block-grid-1>li>a');
            foreach ($schema_list as $schema){
                $pq = pq($schema);
                $query_prod = Schema_productions::find()
                    ->where(['schema_id' => trim($pq->attr('data-execution-id'))])
                    ->asArray()->one();

                if (count($query_prod) == 0){
                    $schema_db = new Schema_productions();
                }else{
                    $schema_db = Schema_productions::findOne((Integer)$query_prod['id']);
                }
                $schema_db->name_schema = trim($pq->find('p')->html());
                $schema_db->production_id = $production['id'];
                $schema_db->schema_id = $pq->attr('data-execution-id');
                $schema_db->img_production_url = $pq->find('img')->attr('src');
                $schema_db->save();
            }
        }
    }

    private function productionSchema($queryProduction){
        foreach ($queryProduction as $production){
            $productInfo = Schema_productions::find()
                ->where(['production_id'=>$production['id']])
                ->asArray()->all();
            if (count($productInfo) == 0){
                $clinet = new Client();
                $res = $clinet->request('GET',self::BASE_URL.$production['url']);
                $body = $res->getBody();
                $document = \phpQuery::newDocumentHTML($body);
                $schema_db = new Schema_productions();
                $schema_db->name_schema = $production['name'];
                $schema_db->production_id = $production['id'];
                $schema_db->schema_id = '';
                $schema_db->img_production_url = '';
                $schema_db->img_schema = $document->find('.product-drawing')->find('img')->attr('src');
                $schema_db->save();
            }
        }
    }

    //todo-me Добавить схемы
    private function getImageSchema($queryProduction){
//        foreach ($queryProduction as $production){
//            $query_prod = Schema_productions::find()
//                ->where(['production_id' => $production['id']])
//                ->asArray()->one();
//
//            if (($query_prod['schema_id'] != '') && ($query_prod['img_schema'] == '')){
//                $production = Productions::findOne($query_prod['production_id']);
                $production = Productions::findOne(1);
                $document = $this->getPage(self::BASE_URL.$production->url);
                $productionData = $document->find('.product-datas')
                    ->find('.product-data-wrapper');

                    $schema = Schema_productions::find()
                            ->where(['production_id'=>1])->asArray()
                            ->all();
                    foreach ($schema as $sch){
                        foreach ($productionData as $prod){
                            $pq = pq($prod);
                            if ($pq->attr('data-execution-id') == $sch['schema_id']){
                                echo $pq->find('.product-drawing')
                                    ->find('.row')
                                    ->find('.columns')
                                    ->find('img')
                                    ->attr('src');
                            }
                        }
                    }
//                }
//            }
//        }

    }
    /**
     * Получить фильтры
     */
    public function actionFilter(){

    }

    /**
     * Получить новые продукты
     */
    public function actionNewProduction(){

    }

    /**
     * Получить изображения
     */
    public function actionGetImage(){
        BaseFileHelper::removeDirectory(Yii::getAlias('@app').'/image');
        BaseFileHelper::createDirectory(Yii::getAlias('@app').'/image');
        $this->getImageTypes();
        $this->getImageProductions();
    }

    private function getImageTypes(){
        $queryTypes = Types::find()->asArray()->all();
        foreach ($queryTypes as $types){
            $query = Types::findOne($types['id']);
            $image_file = $this->generateRandomString(8).'.jpg';
            $query->url_img = $image_file;
            $query->save();
            $this->getImageRemot(self::BASE_URL.$types['url_img'],$image_file);
        }
    }

    private function getImageProductions(){
        $queryTypes = Productions::find()->asArray()->all();
        foreach ($queryTypes as $types){
            $query = Productions::findOne($types['id']);
            $image_file = $this->generateRandomString(10).'.png';
            $query->img_url = $image_file;
            $query->save();
            $this->getImageRemot(self::BASE_URL.$types['img_url'],$image_file);
        }
    }
    //todo-me Добавить забор картинки из схемы
    private function getImageRemot($url,$toFile){
        try{
            $fileImage = fopen(Yii::getAlias('@app').'/image/'.$toFile,'w');
            $client = new Client();
            $res = $client->get($url,['save_to' => $fileImage]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    private function writeMessage($message){
        Yii::info($message);
        if (Yii::$app instanceof Application){
            Console::output($message);
        } else {
            echo $message . "\n";
        }
    }

    private function generateRandomString($length = 8, $allowUppercase = true)
    {
        $validCharacters = 'abcdefghijklmnopqrstuxyvwz1234567890';
        if ($allowUppercase) {
            $validCharacters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $validCharNumber = strlen($validCharacters);
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }

    private function getPage($url){
        $clinet = new Client();
        $res = $clinet->request('GET',$url);
        $body = $res->getBody();
        $document = \phpQuery::newDocumentHTML($body);
        return $document;
    }
}
