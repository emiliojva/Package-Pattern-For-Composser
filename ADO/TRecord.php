<?php

/*
 * classe TRecord
 *  Esta classe prov os mtodos necessrios para persistir e
 *  recuperar objetos da base de dados (Active Record)
 */

namespace Inovuerj\ADO;

use Inovuerj\Helper\Util;

abstract class TRecord
{
    protected $data;  // array contendo os dados do objeto

    protected $validations = [];

    /* mtodo __construct()
     *  instancia um Active Record
     *  se passado o $id, j carrega o objeto
     *  @param [$id] = ID do objeto
     */
    public function __construct($id = NULL)
    {
        if ($id) // se o ID for informado
        {
            // carrega o objeto correspondente
            $object = $this->load($id);
            if ($object) {
                $this->fromArray($object->toArray());
            }
        }

//        $this->showEmptyColumnsValues();
    }

    /**
     *
     * mtodo __clone()
     *  executado quando o objeto for clonado.
     *  Limpa o ID para que seja gerado um novo ID para o clone.
     *
     * @param $id
     * @return TRecord
     * @throws \Exception
     */
    public function load($id)
    {

        // instancia instruo de SELECT
        $sql = new TSqlSelect();
        $sql->setEntity($this->getEntity());
        $sql->addColumn('*');


        // cria critrio de seleo baseado no ID
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id', '=', $id));
        // define o critrio de seleo de dados
        $sql->setCriteria($criteria);
        // inicia transao
        if ($conn = TTransaction::get()) {

            // cria mensagem de log e executa a consulta
            TTransaction::log($sql->getInstruction());
            $result = $conn->Query($sql->getInstruction());

            // se retornou algum dado
            if ($result) {
//                echo $sql->getInstruction();

                // retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));

            }

            return $object;
        } else {
            // se no tiver transao, retorna uma exceo
            throw new \Exception('No h transao ativa !!');
        }
    }

    /*
     * mtodo __get()
     * Executado sempre que uma propriedade for requerida
     */

    private function getEntity()
    {
        // obtm o nome da classe
        $classe = constant(get_class($this) . '::TABLENAME');
        return $classe;
        // retorna o nome da classe - "Record"
//            return substr($classe , 0 , -6);
    }

    /*
     * mtodo __set()
     * Executado sempre que uma propriedade for atribuda.
     */

    public function fromArray($data)
    {
        $this->data = $data;



    }

    /*
     * mtodo getEntity()
     *  retorna o nome da entidade (tabela)
     */

    public function toArray()
    {


        return $this->data;
    }

    /*
     * mtodo fromArray
     * preenche os dados do objeto com um array
     */

    public function __clone()
    {
        unset($this->id);
    }

    /*
     * mtodo toArray
     * retorna os dados do objeto como array
     */

    public function __get($prop)
    {
        // verifica se existe mtodo get_<propriedade>
        $metodo = 'get' . ucwords($prop);
        if (method_exists($this, $metodo)) {
            return call_user_func(array($this, $metodo));
        } else {
            /* Se o método não existir tentar localizar uma propriedade público */
            if (isset($this->data[$prop])) {
                return $this->data[$prop];
            }
        }
    }

    /*
     * mtodo store()
     *  Armazena o objeto na base de dados e retorna
     *  o nmero de linhas afetadas pela instruo SQL (zero ou um)
     */

    public function __set($prop, $value)
    {
        // verifica se existe mtodo set_<propriedade>
        $metodo = 'set' . ucwords($prop);
        if (method_exists($this, $metodo)) {
            call_user_func(array($this, $metodo), $value);
        } else {
            if ($value === NULL) {
                unset($this->data[$prop]);
            } else {
                $this->data[$prop] = $value;
            }
        }
    }


    /**
     * Filtra caracteres e tipos
     * @param array $arrayInputs
     * @return array
     */
    public function filtrarCampos()
    {
        $array_filter = array();

        foreach ($this->validations as $propriedade => $tipo) {

            if($this->data[$propriedade]===NULL){
                throw new \Exception('O valor nao pode ser Nulo');
            }

            // valor do input
            $value = $this->data[$propriedade];

            $valueTypeOf = gettype($value);

            // anti-hacker
            $value = trim($value); # Limpar espacos
            $value = stripslashes($value); # remove barras invertidas
            $value = htmlspecialchars($value); # converte caracteres especiais para realidade HTML -> <a href='test'>Test</a> -> &lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;

            // valor do tipo, usando como metodo existente da classe Sanitize
            $method = $tipo;

            $validator = call_user_func(array('Inovuerj\ADO\TValidator', $method), $value);

            // se nao passar no Validator, retorno erro
            if (!$validator && $method != 'texto') {
                $msg = "O campo {$propriedade} nao foi validado";
                throw new \Exception($msg);
            } else {
                // injetando valor no $this->$propriedade
                $this->{$propriedade} = call_user_func(array('Inovuerj\ADO\TSanitizer', $method), $value);
            }

//            Util::mostrar($propriedade .' - Tipo: '.$tipo, __LINE__);
//            Util::mostrar($this->{$propriedade},__LINE__);

        }


        # filtrado
        return $this;

    }


    /*
     * mtodo load()
     *  Recupera (retorna) um objeto da base de dados
     *  atravs de seu ID e instancia ele na memria
     *  @param $id = ID do objeto
     */

    public function store()
    {
        $this->filtrarCampos();

        // verifica se tem ID ou se existe na base de dados
        if (empty($this->data['id']) or (!$this->load($this->id))) {
            // incrementa o ID
            $this->id = $this->getLast() + 1;
            // cria uma instruo de insert
            $sql = new TSqlInsert;
            $sql->setEntity($this->getEntity());
            // percorre os dados do objeto
            foreach ($this->data as $key => $value) {
                // passa os dados do objeto para o SQL
                $sql->setRowData($key, $this->$key);
            }
        } else {
            // instancia instruo de update
            $sql = new TSqlUpdate;
            $sql->setEntity($this->getEntity());
            // cria um critrio de seleo baseado no ID
            $criteria = new TCriteria;
            $criteria->add(new TFilter('id', '=', $this->id));
            $sql->setCriteria($criteria);
            // percorre os dados do objeto
            foreach ($this->data as $key => $value) {
                if ($key !== 'id') // o ID no precisa ir no UPDATE
                {
                    // passa os dados do objeto para o SQL
                    $sql->setRowData($key, $this->$key);
                }
            }
        }
        // inicia transao
        if ($conn = TTransaction::get()) {

            // faz o log e executa o SQL
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());

            // retorna o resultado
            return $result;
        } else {
            // se no tiver transao, retorna uma exceo
            throw new \Exception('No h transao ativa !!');
        }
    }

    /*
     * mtodo delete()
     *  Exclui um objeto da base de dados atravs de seu ID.
     *  @param $id = ID do objeto
     */

    public function getLast()
    {
        // inicia transao
        if ($conn = TTransaction::get()) {
            // instancia instruo de SELECT
            $sql = new TSqlSelect;
            $sql->addColumn('max(ID) as ID');
            $sql->setEntity($this->getEntity());


            // cria log e executa instruo SQL
            TTransaction::log($sql->getInstruction());
            $result = $conn->Query($sql->getInstruction());
            // retorna os dados do banco
            $row = $result->fetch();

            return $row[0];
        } else {
            // se no tiver transao, retorna uma exceo
            throw new Exception('No h transao ativa !!');
        }
    }

    /*
     * mtodo getLast()
     * Retorna o ltimo ID
     */

    public function delete($id = NULL, TCriteria $conditions = null)
    {
        // o ID  o parmetro ou a propriedade ID
        $id = $id ? $id : $this->id;
        // instancia uma instruo de DELETE
        $sql = new TSqlDelete;
        $sql->setEntity($this->getEntity());

        // cria critrio de seleo de dados
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        // define o critrio de seleo baseado no ID
        $sql->setCriteria($criteria);

        if ($conditions)
            $sql->setCriteria($conditions);

        // inicia transao
        if ($conn = TTransaction::get()) {
            // faz o log e executa o SQL
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());

            // retorna o resultado
            return $result;
        } else {
            // se no tiver transao, retorna uma exceo
            throw new \Exception('No h transao ativa !!');
        }
    }

    public function lastInsertId()
    {
        $conn = TTransaction::get();
        return $conn->lastInsertId();
    }

    /**
     *
     * Lista todos os dados da tabela
     * returna um array de objetos
     *
     * @param TCriteria|null $criterio
     * @param bool|FALSE $retornar_array
     * @param array|string $order_by
     *
     * @return TRecord|array
     */
    public function getList(TCriteria $criterio = null, $retornar_array = FALSE, $order_by = null)
    {
        $conn = TTransaction::get();


        if ($conn) {

            $repo = new TRepository(get_class($this));

            if (is_null($criterio)) {
                $criterio = new TCriteria();
                $criterio->add(new TFilter(1, '=', 1));
            }

            $collection = $repo->load($criterio);

            if (count($collection)) {

                # se for para retornar como uma collection

                $array_list = array();
                # Recuperando descrição das chaves estrangeiras ex: _id
                foreach ($collection as &$object) {
                    /** Verifica se possui método para atributos terminados com '_id' e chama o method **/

                    foreach ($object->toArray() as $attribute => $value) {

                        if (preg_match('/(_id)$/', $attribute)) {

                            $colunaBanco = preg_replace('/(_id)$/', '', $attribute);

                            $method = 'get' . ucwords($colunaBanco);

                            if (method_exists($object, $method)) {
                                $object->data[$colunaBanco] = $object->$method();
                            }
                        }
                    }

                    # Populando para o caso de ser retorno em array
                    $array_list[] = $object->toArray();
                }

                if ($retornar_array) {
                    return !is_null($order_by) ? $this->sortArrayByField($order_by, $array_list) : $array_list;
                } else {
                    return !is_null($order_by) ? $this->sortCollectionByField($order_by, $collection) : $collection;
                }
            }
        }
    }

    public function sortArrayByField($fields, array $list)
    {


        # Ordenação do objeto
        usort($list, function ($a, $b) use ($fields) {

            # Caso o parametro for string
            if (is_string($fields)) {
                $al = Utilidades::removeAcentos(strtolower($a[$fields]));
                $bl = Utilidades::removeAcentos(strtolower($b[$fields]));

                # Não altera posição
                if ($al == $bl) {
                    return 0;
                }

                # Posiciona para frente/atrás no array
                return ($al > $bl) ? +1 : -1;

            } # Caso o parametro seja uma lista/array
            else if (is_array($fields)) {

                foreach ($fields as $key => $field) {

                    $field_a = (string)$a[$field];
                    $field_b = (string)$b[$field];

                    $al = Utilidades::removeAcentos(strtolower($field_a));
                    $bl = Utilidades::removeAcentos(strtolower($field_b));

                    if ($al == $bl) {
                        continue;
                    }

                    return ($al > $bl) ? +1 : -1;

                }

            } else {
                throw new \Exception('Parametro não é uma STRING|ARRAY');
            }
        });

        return $list;
    }

    /**
     * Reordena array/collection de acordo com campo passado(Ou referência a outro objeto)
     *
     * Se Houver 'campo_id' na tabela atual, esse método tentará encontrar referência getCampo(), no objeto
     * da chave estrangeira.
     *
     * Ex: se passar um campo 'categoria' em param fields, ele tentará fazer uso do método mágico __get() do objeto.
     * Com isso chamar getCategoria(), e comparar seu toString() retornado
     *
     * @param string|array $fields
     * @param array $collection
     * @return array
     *
     *
     *
     */
    public function sortCollectionByField($fields, array $collection)
    {


        # Ordenação do objeto
        usort($collection, function ($a, $b) use ($fields) {

            # Caso o parametro for string
            if (is_string($fields)) {
                $al = Utilidades::removeAcentos(strtolower($a->$fields));
                $bl = Utilidades::removeAcentos(strtolower($b->$fields));

                # Não altera posição
                if ($al == $bl) {
                    return 0;
                }

                # Posiciona para frente/atrás no array
                return ($al > $bl) ? +1 : -1;

            } # Caso o parametro seja uma lista/array
            else if (is_array($fields)) {

                foreach ($fields as $key => $field) {

                    $field_a = (string)$a->$field;
                    $field_b = (string)$b->$field;

                    $al = Utilidades::removeAcentos(strtolower($field_a));
                    $bl = Utilidades::removeAcentos(strtolower($field_b));

                    if ($al == $bl) {
                        continue;
                    }

                    return ($al > $bl) ? +1 : -1;

                }

            } else {
                throw new \Exception('Parametro não é uma STRING|ARRAY');
            }
        });

        return $collection;
    }


    public function toJson($charset = null)
    {

        $results = array();
        foreach ($this->toArray() as $prop => $value) {

            if (mb_detect_encoding($value) == 'UTF-8') {

//                Utilidades::mostrar($prop . '-'. $value . '  '.mb_detect_encoding($value));
                $results[$prop] = utf8_encode($value);

            } else {
                $results[$prop] = $value;
            }


        }

        return $results;

    }


    /**
     * choice all attributes to persisting databases single active record - row
     *
     * @return bool|TRecord
     */
    public function getOne()
    {
        if (!empty($this->data['id'])) {
            return $this;
        }

        if (count($this->data)) {

            $results = array();

            $criterio = new TCriteria();

            foreach ($this->data as $prop => $value) {

                $criterio->add(new TFilter($prop, '=', $value));

            }

            $results = $this->getList($criterio);

            return is_object($results[0]) ? $results[0] : false;

        }

        throw new \Exception('O metodo getOne, precisa que um campo chave seja preenchido');


    }

    public function getColumns()
    {

        $conn = TTransaction::get();

        $q = $conn->prepare("DESCRIBE {$this->getEntity()}");
        $q->execute();
        return $q->fetchAll(\PDO::FETCH_COLUMN);
        
    }


    /**
     * Retorna Objeto da Entity, com valores das propriedades vazios, inclusive
     * @return $this
     */
    public function showEmptyColumnsValues(){
        // preenchendo propriedades que nao retornaram do banco, sendo NULL
        foreach ($this->getColumns() as $column) {
            if (empty($this->data[$column])) {
                $this->data[$column] = null;
            }
        }

        return $this;
    }

}
