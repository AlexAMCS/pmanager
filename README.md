# pmanager

Última atualização: 09/03/2020

Sistema básico para gerência de projetos.

Feito com backend PHP 7.4.3, frontend em HTML 5, CSS 3 e JS (ECMAScript 2019), usando as bibliotecas Bootstrap (para componentes responsiivos) e jQuery (para o wrapper de XMLHttpRequest() e seletores CSS), usando como SGBD MariaDB 10.4.11 e HTTPd Apache 2.4.41 (pacote 
XAMPP 7.4.3).

Para instalação, usar o pacote XAMPP mais recente (7.4.3), ou qualquer servidor HTTP com suporte a PHP 7.4, SGBD MariaDB ou MySQL mais recente.
Se for usar MySQL, lembrar de usar o sistema de autenticação antigo para maior compatibilidade (mysql_native_password).

Os esquemas de criação do banco de dados, em SQL, estão na pasta "schema".

Para executar o sistema, basta copiar a pasta inteira do projeto para a pasta web do servidor.

O arquivo "contents/config.php" deve ser alterado para ficar de acordo com as características do seu SGBD.
