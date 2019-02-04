# Package com o Design Patterns. 
###### Desenvolvendo Microframeworks - SON
https://github.com/emiliojva/Package-Pattern-For-Composser

# Instalação do Composer

- #### Baixar 'composer.phar' no site getcomposer.org.

- #### Colocar arquivo 'composer.phar' no diretório do projeto.

- #### Criação do package.json :
  ```
  php composer.phar init
  ```

# PSR-4
- #### Associar PSR4 no package.json. Especificando o fornecedor e o diretório base(src ou source).
  ```
  "autoload": {
          "psr-4": {
              "Inovuerj\\": "src/"
          }
      }
  ```
- #### Sendo a raiz do projeto a pasta src/
- #### Importanto Autoloading no arquivo bootstrap do projto (like app.php) :
  ```
  require 'autoloading.php';
  ```
- #### Imprescindivel uso de namespace para que o composer localize a classe.
- #### Usar um formato que inicia com um nome de fornecedor e termina com nome da class. 
- #### Exemplos de chamada: 
  ```
  $exemplo = new Fornecedor\PacoteOuSubdominio\classe;
  $router = new Inovuerj\Router\Router;
  ```