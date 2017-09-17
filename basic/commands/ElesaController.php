<?php
/**
 * @author: anton <antondrelin@gmail.com>
 * Date: 17.09.17
 * Time: 17:16
 */
namespace app\commands;

use GuzzleHttp\Client;
use yii\console\Controller;
use app\models\Types;
use app\models\Productions;

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
        echo $query;
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
        echo 'Count productions' . $query . '/t/n';
    }
}
