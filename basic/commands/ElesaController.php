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
                 $new_type->name = trim($pq->text());
             }else{
                 $new_type  = Types::findOne($query['id']);
                 $this->deleteImage($new_type->url_img);
             }

             $new_type->url = urldecode(trim($pq->attr('href')));

             $image_file = $this->generateRandomString(8).'.jpg';
             $url_img = $pq->find('img')->attr('src');

             $new_type->url_img = $image_file;
             $new_type->save();

             $this->getImageRemot(self::BASE_URL.$url_img,$image_file);
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

            $document = $this->getPage(self::BASE_URL.$type['url']);
            $production_list = $document->find('.product-index-tile');

            foreach ($production_list as $elem){
                $pq = pq($elem);
                $name = trim((String)$pq->find('h2')->text());
                $id_type = $type['id']; //ID продукции

                $url = urldecode(trim((String)$pq->find('a')->attr('href')));

                $query = Productions::findOne(['name' => $name,'url' => $url]);

                if (count($query) == 0){
                    $query  = new Productions();
                    $query->name = trim((String)$pq->find('h2')->text());
                    $query->url = urldecode(trim((String)$pq->find('a')->attr('href')));
                }else{
                    $this->deleteImage($query->img_url);
                }
                $query->description = trim((String)$pq->find('.series-overview-subtitle')->text());
                $query->materail = trim((String)$pq->find('.series-overview-subtitle_sec')->text());

                $image_file = $this->generateRandomString(10).'.png';
                $url_img = trim((String)$pq->find('img')->attr('src'));

                $query->img_url = $image_file;
                $query->types_id = $id_type;
                $query->save();

                $this->getImageRemot(self::BASE_URL.$url_img,$image_file);
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
        $this->getProductionSchema($queryProduction);
        $querySchema = Schema_productions::find()->count();
        $this->writeMessage('Schema productions ' . $querySchema);
    }


    /**
     * Получить наименование фильтров и артикулы
     */
    public function actionFilter(){
        $productList = Productions::find()->all();
        foreach ($productList as $product){
            $document = $this->getPage(self::BASE_URL.$product->url);
            $productionData = $document->find('.product-datas')->find('.product-data-wrapper');
            foreach ($productionData as $prod){
                $_pq = pq($prod);

                $thead = $_pq->find('.product-dimensions-table')
                    ->find('.row')
                    ->find('.columns')
                    ->find('.table-wrapper')
                    ->find('.custom')
                    ->find('.overflow-container')
                    ->find('table')->find('thead')
                    ->find('.titlerow')->find('th');
                foreach ($thead as $th){
                    $pq = pq($th);

                    $filter = Filter::findOne([
                        'name' => (String)trim($pq->text()),
                        'production_id' => (Integer)$product->id
                    ]);

                    if (count($filter)==0){
                        $filter = new Filter();
                        $filter->production_id = $product->id;
                        $filter->name = (String)trim($pq->text());
                        $filter->save();
                    }

                }
                $schema_id = $_pq->attr('data-execution-id');
                $tbody = $_pq->find('.product-dimensions-table')
                    ->find('.row > .columns > .table-wrapper > .custom > .overflow-container > table > tbody')
                    ->find('tr > th > a');
                foreach ($tbody as $a){
                    $a = pq($a);
                    $filter_aricle = Filter_article::findOne([
                        'production_id' => (Integer)$product->id,
                        'article_code' => trim((String)$a->text()),
                        'schema_id' => $schema_id
                    ]);
                    if (count($filter_aricle) == 0){
                        $filter_aricle = new Filter_article();
                        $filter_aricle->schema_id = $schema_id;
                        $filter_aricle->production_id = (Integer)$product->id;
                        $filter_aricle->article_code = trim((String)$a->text());
                        $filter_aricle->save();
                    }
                }
            }
        }

        $query = Filter::find()->count();
        $this->writeMessage('Filter productions: ' . $query);

        $query = Filter_article::find()->count();
        $this->writeMessage('Filter article:' . $query);
    }

    /**
     * Получить схемы у которые есть артикулы
     * @param $queryProduction
     */
    private function getSchema($queryProduction){

        foreach ($queryProduction as $production){

            $document = $this->getPage(self::BASE_URL.$production['url']);

            $productInfo = Productions::findOne($production['id']);
            $productInfo->full_description = trim($document->find('.content')->html());
            $productInfo->save();

            $schema_list = $document->find('.small-block-grid-1>li>a');

            foreach ($schema_list as $schema){

                $pq = pq($schema);
                $schema_id = trim($pq->attr('data-execution-id')); //Берем schema_id

                if ($schema_id !== ''){
                    $query_prod = Schema_productions::findOne(['schema_id' => $schema_id]);

                    if (count($query_prod) == 0){
                        $query_prod = new Schema_productions();
                        $query_prod->schema_id = $schema_id;
                    }else{
                        $this->deleteImage($query_prod->img_production_url);
                    }

                    $image_file = $this->generateRandomString(20).'.png';
                    $url_img = $pq->find('img')->attr('src');

                    $query_prod->name_schema = trim($pq->find('p')->html());
                    $query_prod->production_id = $production['id'];
                    $query_prod->img_production_url = $image_file;

                    $productionData = $document->find('.product-datas')
                        ->find('.product-data-wrapper');

                    foreach ($productionData as $prod){
                        $_pq = pq($prod);
                        if ($_pq->attr('data-execution-id') == $schema_id){

                            $_image_file = $this->generateRandomString(20).'.png';
                            $_url_img = $_pq->find('.product-drawing')
                                ->find('.row')
                                ->find('.columns')
                                ->find('img')
                                ->attr('src');

                            $this->deleteImage($query_prod->img_schema);
                            $query_prod->img_schema = $_image_file;

                            $this->getImageRemot(self::BASE_URL.$_url_img,$_image_file);
                        }
                    }

                    $query_prod->save();
                    $this->getImageRemot(self::BASE_URL.$url_img,$image_file);
                }
            }
        }
    }

    private function getProductionSchema($queryProduction){
        $schemaList = Schema_productions::find([
            'schema_id' => ''
        ]);

        foreach ($schemaList as $schema){

            $schema = Schema_productions::findOne($schema->id);

            $production = Productions::findOne($schema->production_id);

            $document = $this->getPage(self::BASE_URL.$production->url);
            $this->deleteImage($schema->img_schema);
            $image_file = $this->generateRandomString(12).'.jpg';
            $url_img = $document->find('.product-drawing')->find('img')->attr('src');
            $schema->img_schema = $image_file;
            $schema->save();
            $this->getImageRemot(self::BASE_URL.$url_img,$image_file);
        }

        foreach ($queryProduction as $production){
            $productInfo = Schema_productions::find()
                ->where(['production_id'=>$production['id']])
                ->asArray()->all();
            if (count($productInfo) == 0){
                try {
                    $document = $this->getPage(self::BASE_URL.$production['url']);
                    $schema_db = new Schema_productions();
                    $schema_db->name_schema = $production['name'];
                    $schema_db->production_id = $production['id'];
                    $schema_db->schema_id = '';
                    $schema_db->img_production_url = '';

                    $image_file = $this->generateRandomString(12).'.jpg';
                    $url_img = $document->find('.product-drawing')->find('img')->attr('src');
                    $schema_db->img_schema = $image_file;
                    $schema_db->save();
                    $this->getImageRemot(self::BASE_URL.$url_img,$image_file);
                }catch (\yii\base\Exception $e){
                    $this->writeMessage($e->getMessage());
                }
            }
        }
    }


    private function getImageRemot($url,$toFile){
        try{
            $fileImage = fopen(Yii::getAlias('@app').'/image/'.$toFile,'w');
            $client = new Client();
            $res = $client->get($url,['save_to' => $fileImage]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    private function deleteImage($toFile){
        try{
            @unlink(Yii::getAlias('@app').'/image/'.$toFile);
        }catch (\yii\base\Exception $e){
            $this->writeMessage($e->getMessage());
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
