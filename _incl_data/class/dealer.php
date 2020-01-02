<?
die('Что-то тут не так...');
/*
 * Класс обработчика запроса от GameDealer.ru
 * by victor niko
 * niko@gamedealer.ru
 * skype: victornikoua
 * 14.09.2012
 *
 *          $old = new Model_PartnerProjects_GamedealerReq;
            $result = $old->init();
            die((($result)));
 */


class  Model_PartnerProjects_GamedealerReq{
    private $allowIp = array(
        '127.0.0.1'
    );
    private $gamedealerkey = '123456';
    var $projectid = 1;
    

   

    function init(){

            $ip = $_SERVER['REMOTE_ADDR'];
            if(!in_array($ip,$this->allowIp))return $this->xml(array('status'=>-10,'desc'=>'ErrIP'));

            //рабочий режим
            //$xml = file_get_contents('php://input');   

            //тестовый режим. Параметры: check_balance / pay / getpacages / check_login
            $xml = $this->testxml('check_balance');  
            
            $result = $this->parse($xml);

            if(!is_object($result))return $this->xml(array('status'=>-50,'desc'=>'ErrorInitParse'));
           
            if(isset($result->method)){
                $methodname = 'gamedealer_'.(string)$result->method; 
                if(!method_exists($this, $methodname))return $this->xml(array('status'=>-20,'desc'=>'ErrMethod'));
                try{
                    
                    $resulttry = $this->$methodname($result);
                    return $this->xml($resulttry);
                }catch(Exception $e){}
            }
            return $this->xml(array('status'=>-10,'desc'=>'MethodError '.$methodname.isset($e)?$e->getMessage():false));
        }



       private function _sign($method,$params=array()){

                         return md5(implode($params).$method.md5($this->gamedealerkey));
           }



        /*Генератор XML-ответа*/
        function xml($arr=false){
            header("Content-type: text/xml; charset=utf-8");

                if(!$arr)$arr = array('status'=>-1,'desc'=>"ErrorXML");
                $xml = '<gdanswer>'."\n";
                    foreach($arr as $k => $v){
                        $xml .= '<'.$k.'>'."";
                        if(is_array($v)){
                           
                                   
                                        foreach($v as $itemkey => $itemvalue){
                                                if(is_array($itemvalue)){
                                                    $xml .= ' <item ';
                                                    foreach($itemvalue as $itemvalue_k => $itemvalue_v)$xml .= ' '.$itemvalue_k.'="'.$itemvalue_v.'" ';
                                                    $xml .= '></item>'."\n";
                                                }else{
                                                    $xml .= '<'.$itemkey.'>'.$itemvalue.'</'.$itemkey.'>'."\n";
                                                }
                                        }
                          }else {
                                        $xml .= $v;
                          }
                      $xml .= '</'.$k.'>'."\n";
                        
                    }
                $xml .= '</gdanswer>';
                return $xml;
        }

        //проверка счета-акканта
        function gamedealer_check_login($params){

        
                $bank = isset($params->nick)?(int)$params->nick:false;
                $projectid = isset($params->projectid)?(int)$params->projectid:false;
                $sign = isset($params->sign)?(string)$params->sign:false;

                $hash = $this->_sign('check_login',array($bank));
                if($sign!=$hash)return array('status'=>-10,'desc'=>'SignError');

                $check = $this->checkLoginByBank($bank);
                if(isset($check['login'])){
                        return array('status'=>1,'desc'=>'Счет указан верно','addinfo'=>$check['login']);
                }

                   
                return array('status'=>-100,'desc'=>'Ошибка проверки счета');
        }


        //запрос на оплату
        function gamedealer_pay($params){

           

            
                $bank = isset($params->nick)?(int)$params->nick:false;
                
                $projectid = isset($params->projectid)?(int)$params->projectid:false;
                $sign = isset($params->sign)?(string)$params->sign:false;
                $paymentid = isset($params->payid)?(int)$params->payid:false;  // уникальный номер платежа
                $amount = isset($params->amount)?number_format((float)$params->amount,'2','.',''):false; //сумма в игровой валюте
                $pacageid = isset($params->pacageid)?(int)$params->pacageid:'';     //идентификатор покупки опции в ГД. если нету - платеж на счет юзера
                $partneritemid = isset($params->partneritemid)?(int)$params->partneritemid:''; //идентификатор продукта в вашей системе
                $additemid = isset($params->additemid)?(int)$params->additemid:'';  //дополнительный идентификатор пакета
                $paymethodid = isset($params->paymethodid)?(string)$params->paymethodid:false; //метод оплаты по системе геймдилер // может быть пустым)


        
                $hash = $this->_sign('pay',array($bank,$projectid.$pacageid.$amount,$paymentid));
                if($sign!=$hash)return array('status'=>-10,'desc'=>'SignError');

                if($amount<0.01)return array('status'=>-20,'desc'=>'сумма слишком маленькая');

                $check = $this->checkLoginByBank($bank);
                if(!isset($check['login']))return array('status'=>-10,'desc'=>'Ошибка логина. счет неверный'); //логин перса добываем

                return $this->oldbk_dopayment($paymentid,$bank,$projectid,$pacageid,$partneritemid,$amount,$check['login'],$additemid);

        }

        function gamedealer_check_balance($params){
                $projectid = isset($params->projectid)?(int)$params->projectid:false;
                $sign = isset($params->sign)?(string)$params->sign:false;
                $method = 'check_balance';

                if($sign != $this->_sign($method,array('projectid'=>$projectid)))return array('status'=>-10,'desc'=>"errSign");
                $balance = 500;
                return array('status'=>1,'desc'=>"Balance: ".$balance,'balance'=>$balance); //баланс в игре
        }

        function gamedealer_getpacages($params){
                $sign = isset($params->sign)?(string)$params->sign:false;
                $projectid = isset($params->projectid)?(int)$params->projectid:false;
                $method = 'getpacages';

               
                if($sign != $this->_sign($method,array('projectid'=>$projectid)))return array('status'=>-10,'desc'=>"errSign");

                return array(
                    'status'=>1,
                    'desc'=>'Список снизу :)',
                    'pacages'=>array(
                        array(
                        'title'=>'Название сильвера',
                        'price'=>20,
                        'partneritemid'=>1 //ид в системе игры
                        ),
                        
                      array(
                        'title'=>'Название сильвера 2',
                        'price'=>20,
                        'partneritemid'=>1 //ид в системе игры
                      ),
                    )
                );
        }

        

        function parse($xml){
            try{
                return simplexml_load_string($xml);
            }catch(Exception $e){
                return false;
            }
        }








        //тестовый интерфейс
         function testxml($method='check_login'){
            switch($method){
                case 'check_login':
                    $nick = 243;
                    
                    
                    return '<?xml version="1.0" encoding="utf-8"?>
                        <gamedealer>
                        <method>check_login</method>
                        <nick>'.$nick.'</nick>
                        <projectid>1</projectid>
                        <sign>'.md5($nick.$method.md5($this->gamedealerkey)).'</sign>
                    </gamedealer>';
                break;
            

            case 'pay':
                $amount=  0.01;
                $nick = 243;
                $projectid = 1;
                $payid = 112222;

                //$bank,$projectid.$pacageid.$amount,$paymentid
                #echo "$nick.$projectid.$amount.$payid.$method";
                $sign = md5($nick.$projectid.$amount.$payid.$method.md5($this->gamedealerkey));

                return '<?xml version="1.0" encoding="utf-8"?>
                <gamedealer>
                    <method>pay</method>
						  <nick>'.$nick.'</nick>
						  <projectid>'.$projectid.'</projectid>
						  <amount>'.$amount.'</amount>
						  <payid>'.$payid.'</payid>
						  <sign>'.$sign.'</sign>
                </gamedealer>';

            break;

            case 'getpacages':
            //список пакетов
                
                    return '<?xml version="1.0" encoding="utf-8"?>
                         <gamedealer>
                            <projectid>1</projectid>
                             <method>'.$method.'</method>
                          <sign>'.md5('1'.$method.md5($this->gamedealerkey)).'</sign>
                         </gamedealer>';
           break;

       case 'check_balance':
            //баланс

                    return '<?xml version="1.0" encoding="utf-8"?>
                         <gamedealer>
                            <projectid>1</projectid>
                             <method>'.$method.'</method>
                          <sign>'.md5('1'.$method.md5($this->gamedealerkey)).'</sign>
                         </gamedealer>';
           break;
         }
    }




    //прием оплаты
        private function oldbk_dopayment($paymentid,$bank,$projectid,$pacageid,$partneritemid,$amount,$login,$additemid){
            //$additemid - дополнительный дентификатор пакета. например - ID клана
            //если оплата на клан - projectid = 20099 // pacageid = 25 //partneritemid  = 1 (в вашей системе)// additemid = 5 //идентификатор клана
                //если пакеты 
                if($pacageid){
                    //если покупка билетов - то количество = amount/price ну  и там округлять, остаток на счет.
                        return array('status'=>1,'desc'=>'Сильвер аккаунт успешно оплачен','paymentid'=>1); //уникальный номер платежа в системе проекта.

                }else{
                    //просто оплата на баланс по счету
                        return array('status'=>1,'desc'=>'Счет попонен','paymentid'=>1); //уникальный номер платежа в системе проекта.
                        //или
                        return array('status'=>3,'desc'=>'Аккаунт уже оплачен с таким payid','paymentid'=>1);//уникальный номер платежа в системе проекта.
                }

                return array('status'=>-10,'desc'=>'Ошибка поиска карты/сильвера и т.п. или сумма не та');

                
        }


        //проверка счета
        function checkLoginByBank($bank){
                return array('status'=>1,'desc'=>'Bank exissts','login'=>'FuckenHero');
        }


        
}
?>