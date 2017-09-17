<?php
/**
 * @author: anton <antondrelin@gmail.com>
 * Date: 17.09.17
 * Time: 17:16
 */
namespace app\commands;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
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
                    ->where(['name' => trim($pq->text()), 'url'=>trim($pq->attr('href'))])
                    ->asArray()->all();

             if (count($query) == 0){
                 $new_type = new Types();
                 $new_type->name = trim($pq->text());
                 $new_type->url = trim($pq->attr('href'));
                 $new_type->save();
             }
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
                $production->url = trim((String)$pq->find('a')->attr('href'));
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
        $this->getImageSchema($queryProduction);

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

    private function getImageSchema($queryProduction){

        foreach ($queryProduction as $production){
            $query_prod = Schema_productions::find()
                ->where(['production_id' => $production['id']])
                ->asArray()->one();

            if (($query_prod['schema_id'] != '') && ($query_prod['img_schema'] == '')){

            }
        }

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

    }

    private function writeMessage($message){
        Yii::info($message);
        if (Yii::$app instanceof Application){
            Console::output($message);
        } else {
            echo $message . "\n";
        }
    }
}
