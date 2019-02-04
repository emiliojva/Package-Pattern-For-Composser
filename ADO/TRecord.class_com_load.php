<?php

    /**
     * Created by PhpStorm.
     * User: Vieiras
     * Date: 03/05/2015
     * Time: 17:21
     */


    /**
     * Classe TRecord
     *  Esta classe provê os métodos necessários para persistir e
     *  recuperar objetos da base de dados (Active Record)
     *
     */
    abstract class TRecord_AUX
    {

        /** @var $data - array  contendo os dados do objeto  * */
        protected $data;

        /**
         * Método __contruct()
         *  instancia um Active Record. Se passado o $id, já carrega o objeto
         *
         * @param [$id] = ID do objecto
         *
         */
        public function __construct($id = NULL)
        {
            if ($id) {
                $object = $this->load($id);
                if ($object) {
                    $this->fromArray($object->toArray());
                }
            }
        }

        /**
         * Método __clone()
         *  executado quando o objeto for clonado
         *  limpa o ID para que seja gerado um novo ID para o clone
         *
         */
        public function __clone()
        {
            unset($this->id);
        }

        /**
         * método __set()
         * Interceptador - Executado sempre que uma propriedade for atribuída
         */
        public function __set($prop , $value)
        {
            $metodo = 'set' . ucwords($prop);
            if (method_exists($this , $metodo)) {
                call_user_func(array($this , $metodo) , $value);
            } else {
                if ($value == NULL) {
                    unset($this->data[ $prop ]);
                } else {
                    $this->data[ $prop ] = $value;
                }
            }
        }


        /**
         *  Método __get()
         *  Executado sempre que uma propriedade for requisitada
         *
         */

        public function __get($prop)
        {


            $metodo = 'get' . ucwords($prop);
            if (method_exists($this , $metodo)) {
                return call_user_func(array($this , $metodo));
            } else {
                /* Se o método não existir tentar localizar uma propriedade público */
                if (isset($this->data[ $prop ])) {
                    return $this->data[ $prop ];
                }
            }
        }

        /**
         *  Método getEntity()
         *  retorna o nome da entidade (tabela)
         */
        private function getEntity()
        {
            # Pegar o nome da class do objeto passado
            $class = get_class($this);

            # O nome da tabela criado em uma constante da class filha
            return constant("{$class}::TABLENAME");
        }

        /**
         * método fromArray
         * preenche os dados do objeto com um array
         */
        public function fromArray($data)
        {
            $this->data = $data;
        }

        /**
         * método toArray()
         * retorna os dados do objeto array
         */
        public function toArray()
        {
            return $this->data;
        }

        /**
         * método store()
         *  armazena o objeto na base de dados e retorna
         *  o número de linhas afetadas pela instrução SQL (zero ou UM)
         */
        public function store()
        {
            # Resgatar o nome da tabela
            $TABLE = $this->getEntity();

            # verifica se tem ID ou se existe na base de dados
            if (empty($this->data['id']) or (!$this->load($this->id))) {

                /** Instancia instrução de INSERT **/

                # Verificar ultimo id da tabela e incrementar + 1
                if (empty($this->data['id'])) {
                    $this->id = $this->getLast() + 1;
                }

                $array_columns = array();
                $array_values = array();
                foreach ($this->data as $column => $value) {
                    $array_columns[] = $column;
                    $array_values[] = $value;
                }

                # Formatação das colunas e valores
                $column_format = '(' . implode(',' , $array_columns) . ')';
                $values_format = '(' . implode(',' , $array_values) . ')';


                # Montagem final da Query
                $sql = "INSERT INTO {$TABLE} {$column_format} VALUES {$values_format}";

            } else {

                /** Instancia instrução de UPDATE **/


                $array_columns_values = array();
                foreach ($this->data as $column => $value) {
                    # Sem id no updade
                    if ($column !== 'id') {
                        $array_columns_values[] = "{$column}='{$value}'";
                    }

                }
                $sql = "UPDATE {$TABLE} SET " . implode(',' , $array_columns_values) . " WHERE id = {$this->id}";
            }

            if ($conn = TTransaction::get()) {
//                TTransaction::log()
                $result = $conn->exec($sql);

                return $result;
            } else {
                throw new Exception('Não há transação ativa');
            }

        }

        /**
         * recupera (retorna) um objeto de base de dados
         *
         * @param $id = ID do Objeto
         *
         * @throws Exception
         */
        public function load($id)
        {
            /** Obtém transação **/
            if ($conn = TTransaction::get()) {
                /** SQL **/
                # Table
                $TABLE = $this->getEntity();
                # Retorna um PDO Statament
                $stmt = $conn->prepare("SELECT * FROM {$TABLE} WHERE id = :id");
                # O mesmo que : $sth->bindValue(':colour', $colour, PDO::PARAM_STR);
                $params = array(
                    ':id' => $id
                );
                # Executa com params bind
                $stmt->execute($params);

                if ($stmt) {
                    return $stmt->fetchObject(get_class($this));
                }

            } else {
                throw new Exception('Não há transação ativa');
            }

        }


        /**
         * método delete()
         * exclui um objeto da base de dados através de seu ID
         *
         * @param null $id
         *
         * @throws Exception
         */
        public function delete($id = NULL)
        {
            $TABLE = $this->getEntity();

            # Id é Parâmetro ou a propriedade $id
            $id = $id ? $id : $this->id;

            #
            $sql = "DELETE FROM {$TABLE} WHERE id = {$id}";

            # Obtém transação ativa
            if ($conn = TTransaction::get()) {


                $result = $conn->exec($sql);

                return $result;
            } else {
                throw new Exception('Não há transação ativa!!!');
            }
        }

        /**
         * método getLast
         * retorna o maior valor id da tábela
         * @throws Exception
         */
        private function getLast()
        {

            if ($conn = TTransaction::get()) {
                $TABLE = $this->getEntity();
                $sql = "SELECT MAX(id) as maiorID FROM {$TABLE}";
                $stmt = $conn->query($sql);
                $row = $stmt->fetch();

                return $row[0];
            } else {
                throw new Exception('Não há transação ativa');
            }
        }


    }