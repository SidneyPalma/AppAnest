<?php

namespace Smart\Common\Traits;

/**
 * TresultSet
 * 
 * Implementa a Serialização de uma Entity\Doctrine em um array.
 * Deve ser usado somente no EntityRepository.
 *
 * @category traits
 */
trait TresultSet {
    public $UNEXPECTED_COMMAND = '{"success":false,"records":0,"rows":[],"errors":[],"text":"Pedido inesperado!"}';

    /**
     * Estrutura de retorno padrão
     *
     * @var array
     */
    private static $result = array( 
        'text'=>'Transação bem sucedida!',
        'rows'=>array(),
        'errors'=>array(),
        'success'=>true,
        'message'=>false,
        'records'=>0 );

    /**
     * Estrutura de Retorno self::$result
     * 
     * @return json Contém a estrutura de retorno
     */
    public static function getResultToJson() {
        return self::arrayToJson(self::$result);
    }

    /**
     * Estrutura de Retorno self::$result
     * 
     * @return array Contém a estrutura de retorno
     */
    public static function getResult() {
        return self::$result;
    }
    
    /**
     * Estrutura de Retorno self::$result
     * 
     * @return array Contém a estrutura de retorno
     */
    public static function setResult(array $data) {
        
        foreach ($data as $key => $value) {
            self::$result[$key] = $value;
        }
        
        return self::getResult();
    }

    /**
     * Analisa um array e converte-a em uma a string codificada JSON. Retorna a representação JSON do array
     *
     * @param array $param Um array associativo/indexado
     * @return string String codificada JSON
     */
    public static function arrayToJson(array $param) {
        return json_encode(self::encodeUTF8($param));
    }

    /**
     * Analisa a string codificada JSON e converte-a em uma array associativo.
     *
     * @param string $param String codificada JSON
     * @return array Um array associativo.
     */
    public static function jsonToArray($param) {
        $json = isset($param) ? $param : '{"records":0}';
        return self::decodeUTF8(json_decode($json,true));
    }

    /**
     * Retorna um array associativo
     *
     * @param object $param stdClass
     * @return array Um array associativo.
     */
    public static function objectToArray($param) {
        return json_decode(json_encode($param),true);
    }

    /**
     * Retorna um array associativo
     *
     * @param array $param
     * @return object Um objeto stdClass.
     */
    public static function arrayToOject($param) {
        return json_decode(json_encode($param),false);
    }

    /**
     * Analisa a string codificada JSON e converte-a em uma array associativo.
     * 
     * @param string $param String codificada JSON
     * @return object
     */
    public static function jsonToObject($param) {
        $json = isset($param) ? $param : '{"records":0}';
        return json_decode($json);
    } 
    
    /**
     * Foram registrados erros
     * 
     * @return boolean Possui erros
     */
    public static function hasErrors() {
        return count(self::$result['errors']) !== 0;
    }

    public static function encodeUTF8(array $array) {
        $convertedArray = array();

        foreach($array as $key => $value) {
            if (is_array($value)) {
                $value = self::encodeUTF8($value);
            } else if (!(bool)preg_match('//u', serialize($value))) {
                $value = utf8_encode($value);
            }

            $convertedArray[$key] = $value;
        }

        return $convertedArray;
    }

    public static function decodeUTF8(array $array) {
        $convertedArray = array();

        foreach($array as $key => $value) {
            if (is_array($value)) {
                $value = self::decodeUTF8($value);
            } else if ((bool)preg_match('//u', serialize($value))) {
                $value = utf8_decode($value);
            }

            $convertedArray[$key] = $value;
        }

        return $convertedArray;
    }
    
    /**
     * Mensagem de retorno
     * 
     * @param string $param Mensagem de retorno
     */
    public static function _setText($param) {
        self::$result['text'] = $param;
    }

    /**
     * Registros de retorno
     * 
     * @param array $param Registros de retorno da Estrutura
     */
    public static function _setRows(array $param, $count = null) {
        self::$result['rows'] = $param;
        self::_setRecords(!empty($count) ? (int)$count : count($param));
    }

    /**
     * Erros encontrados
     * 
     * @param array $param Erros encontrados na validação Business
     */
    public static function _setErrors(array $param) {
        self::$result['errors'] = $param;
    }
    
    /**
     * Retorna o status da operação
     * 
     * @param boolean $param Retorno com/sem sucesso da Estrutura
     */
    public static function _setSuccess($param) {
        self::$result['success'] = (boolean)$param;
    }    

    /**
     * Total de Registros encontrados
     * 
     * @param integer $param Total de Registros encontrados na Consulta
     */
    public static function _setRecords($param) {
        self::$result['records'] = (int)$param;
        self::_setSuccess( ((int)$param) ? (int)$param : self::$result['success'] );
        self::_setText( ((int)$param) ? self::$result['text'] : 'Nao ha registros a serem mostrados!' );
    }
    
    /**
     * Método paginador. <br/>
     * 
     * @param array $args Valores para a entidade
     */
    public static function _setPage($start,$limit) {
        $i = 0;
        $results = self::$result['rows'];
        $records = self::$result['records'];
        $start = !is_numeric($start) ? 0 : $start;
        $limit = !is_numeric($limit) ? 0 : $limit;

        if( count($results) !== 0 ) {        
            if((count($results) >= $start) && ($limit > 0)) {
                do {
                    $result[$i] = $results[$start];
                    $i++;
                    $start++;
                } while ( isset($results[$start]) && ($i < $limit) );
                $results = $result;
            }
            self::_setRows($results);
            self::_setRecords($records);
        }
    }

    /**
     * Estrutura de Retorno self::$result paginado
     * 
     * @return array Contém a estrutura de retorno
     */
    public static function getResultPage($start, $limit) {
        self::_setPage($start,$limit);
        return self::getResult();
    }

    /**
     * Retorna o primeiro item de um array
     *
     * @param array $data
     * @return mixed
     */
    public function getFore(array $data) {
        reset($data);
        return current( $data );
    }

    /**
     * Retorna o último item de um array
     *
     * @param array $data
     * @return mixed
     */
    public function getLast(array $data) {
        return end($data);
    }

    public static function unsetKeyId(&$array, $keyId) {
        unset($array[$keyId]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::unsetKeyId($value, $keyId);
            }
        }
    }

    /**
     * Converte um array com a notação parentid, id em um TreeObject
     *
     * @param array $elements array contendo os alementos
     * @param null $parentid  parentid sempre inicia null
     * @return array com a estrutura TreeObject
     * @author Hamish
     * @link http://stackoverflow.com/questions/4328515/convert-php-array-to-json-tree
     */
    public static function buildTree(array $data) {
        $items = array();
        $bunch = array();

        // Build array of item references:
        foreach($data as $key => &$item) {
            $items[$item['id']] = &$item;
        }

        // Set items as children of the relevant parent item.
        foreach($data as $key => &$item) {
            if($item['parentid'] && isset($items[$item['parentid']])) {
                $items[$item['parentid']]['expanded'] = true;
                $items[$item['parentid']]['hideOnClick'] = false;
                $items[$item['parentid']]['children'][] = &$item;
            }
        }

        // Remove items that were added to parents elsewhere:
        foreach($data as $key => &$item) {
            if($item['parentid'] && isset($items[$item['parentid']])) {
                unset($data[$key]);
            }
        }

        foreach($data as $line) {
            $bunch[] = $line;
        }

        return $bunch;
    }

    public static function buildNode(array $data) {
        $build = self::buildTree($data);
        $bunch['text'] = '.';
        $bunch['expanded'] = true;
        $bunch['children'] = $build[0];
        return $bunch;
    }

    public static function buildMenu(array $data) {
        $build = self::buildTree($data);
        self::unsetKeyId($build,'id');
        $bunch = str_replace('children','menu',self::arrayToJson($build));
        return self::jsonToArray($bunch);
    }

    public static function getWebService($url,$data=array(),$get=array()){
        $url = explode('?',$url,2);

        if(count($url)===2){
            $temp_get = array();
            parse_str($url[1],$temp_get);
            $get = array_merge($get,$temp_get);
        }

        $ch = curl_init($url[0]."?".http_build_query($get));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec ($ch);
    }

    public function buscarCep() {
        $data = $_POST;
        $query = $data['query'];
        $url = 'http://m.correios.com.br/movel/buscaCepConfirma.do';

        if(strlen(trim($query)) != 0 ) {

            $html = self::getWebService($url,array(
                'cepEntrada'=>$query,
                'tipoCep'=>'',
                'cepTemp'=>'',
                'metodo'=>'buscarCep'
            ));

            $findout  = array();
            $address  = array();
            $matches  = 'Logradouro:';
            $parse = array('Logradouro','Bairro','Localidade / UF','CEP');

            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $form = $dom->getElementsByTagName('form');

            foreach ($form as $elements) {
                $div = $elements->getElementsByTagName('div');
                for ($i = 0; $i < $div->length; $i++) {
                    $haystack   = $div->item($i)->nodeValue;
                    if(strpos($haystack, $matches) !== false) {
                        $address[] = explode(':',$haystack);
                    }
                }
            }

            foreach ($address as $fields) {
                $field  = array();

                for ($i = 1; $i < count($fields); $i++) {
                    $value = str_replace($parse, '', $fields[$i]);
                    $field[$parse[$i-1]] = rtrim(ltrim($value));
                }

                $field['Localidade / UF'] = explode('/',$field['Localidade / UF']);
                $field['Localidade'] = trim($field['Localidade / UF'][0]);
                $field['UF'] = trim($field['Localidade / UF'][1]);
                $field['CEP'] = substr($field['CEP'],0,8);
                unset($field['Localidade / UF']);

                $findout[] = $field;
            }

            self::_setRows($findout);
        }

        return self::getResultToJson();
    }

}