# DittoDexPW
DittoDex, sua Pokedex em forma de website.

## Descrição do Projeto:
DittoDex, uma pokedex em forma de website. 

<br>A DittoDex conta com mais de 1000 pokémons, onde os utilizadores podem adicionar pokémons aos favoritos. 
<br>Cada utilizador terá uma lista com no máximo 50 pokémons, podendo selecionar até 6 pokémons para formar sua equipa. 
<br>Os pokémons em sua equipa não podem ser utilizados simultaneamente por outros utilizadores.


### Funções principais: 
<p>Login e Registro
<br>Permite que o utilizador realize login ou registre uma nova conta. </p>
<p>Alterar informações do utilizador
<br>Opção para alterar o nome, email e senha do utilizador conforme sua preferência.</p>
<p>Adicionar ou remover um Pokémon aos favoritos
<br>Permite ao utilizador adicionar ou remover Pokémon da lista de favoritos.</p>
<p>Criar uma equipa
<br>Permite ao utilizador criar uma equipa de até 6 Pokémon, respeitando a regra de que Pokémon já escolhidos por outros utilizadores não podem ser selecionados.</p>
<p>Pesquisa de Pokémon
<br>Permite que o utilizador pesquise por qualquer Pokémon disponível no sistema.</p>


## Base de dados:
A base de dados contém as seguintes tabelas principais:
  <br>Tabela user:
  <br>Armazena informações dos utilizadores, como nome, email, ID, senha e chaves primárias.
  
  <p>Tabela favoritos: 
    <br>Armazena os Pokémon favoritos de cada utilizador, com os seguintes campos: 
    <br>ID; 
    <br>user_id;
    <br>pokemon_id;
    <br>pokemon_name;
    <br>chaves primárias e chaves estrangeiras.</p>
    
  <p>Tabela equipa: 
  <br>Armazena os Pokémon adicionados à equipa do utilizador, com os campos: 
  <br>id_pokemon; 
  <br>nome_pokemon; 
  <br>nome_utilizadores;
  <br>chaves primárias e chaves estrangeiras.</p>

### Modelo Entidade_Relacionamento (ER):
![Modelo Entidade Relacionamento - Pokemon_WEB](https://github.com/user-attachments/assets/f536f74c-8251-492c-9a1b-313e75b5247f)


## Link do site
https://dittodex.rf.gd/


## Autores
<p>André Delares nº230000981</p>
<p>Filipe Heliodoro nº230001102</p>
<p>Yuri Neves nº230000986</p>


## Descrição mais detalhada
## **Login**
![Login](https://github.com/user-attachments/assets/e5f559d8-1fc3-4d6d-8086-675907f27038)
<p><br>Permite que um utilizador existente aceda ao website.</p>
<br>Métodos de verificação conectam-se à base de dados para:
  <br>Confirmar se a conta existe.
  <br>Verificar se todos os campos estão preenchidos.
  <br>Validar o email e a senha.
  <br>Caso algum dado esteja incorreto, é exibida a mensagem: "Credenciais Inválidas".

## **Registro**
![Register](https://github.com/user-attachments/assets/2c353468-8bde-4375-980c-74d0f5cf4f68)
<p><br>Permite que novos utilizadores criem uma conta para aceder ao website.</p>
<br>Métodos de verificação garantem que:
  <br>Todos os campos estejam preenchidos.
  <br>O email seja válido.
  <br>O email não esteja em uso.
  

## **DittoDex**
![Dittodex](https://github.com/user-attachments/assets/2cd879c5-1b5a-4159-9108-46043e39cb92)
<p><br>Página principal do website.</p>
<br>Características principais:
  <br>Apresenta uma tabela de Pokémon retornados pela API, com 15 Pokémon exibidos por página, totalizando 75 páginas.
<p><br>Funcionalidades da tabela:
 <br> Adicionar ou remover Pokémon individualmente dos favoritos.
 <br> Barra de navegação para acessar outras páginas, como a página da equipa e o perfil do utilizador.
  <br>Botão de logout, que encerra a sessão e redireciona para a página de login.
  <br>Campo de pesquisa que permite ao utilizador encontrar Pokémon rapidamente e exibir informações detalhadas.</p>


## **Pesquisa de Pokémon**
![Pesquisa_Pokemon](https://github.com/user-attachments/assets/0ebbff99-27a8-4084-93d6-8d1cfd94a25d)
<br>Página dedicada a exibir informações detalhadas sobre os Pokémon pesquisados, incluindo:
  <br>Ataques aprendidos.
  <br>Estatísticas.
  <br>Imagem do Pokémon.
  <br>ID, tipo, altura, peso e experiência base.
<p><br>A página também contém:
  <br>Botão para adicionar o Pokémon aos favoritos.
  <br>Botão para remover o Pokémon dos favoritos.
 <br>Botão para voltar à página principal (DittoDex).</p>


## **Perfil do Utilizador**
![Perfil](https://github.com/user-attachments/assets/d7e12b19-4be3-4d9f-ba8f-3a499dca15a2)
<p><br>Página dedicada às informações do utilizador.</p>
<br>Características principais:
  <br>Exibe todos os Pokémon adicionados aos favoritos do utilizador.
  <p><br>Permite que o utilizador altere:
    <br>Nome.
    <br>Email.
    <br>Senha.
    <br>Inclui uma barra de navegação para acessar outras páginas.</p>


## **Equipa**
![Equipa](https://github.com/user-attachments/assets/cafda377-61e4-4850-adc2-a2203216a1e8)
<p><br>Página dedicada à criação de uma equipa de até 6 Pokémon.</p>
<br>Características principais:
  <br>Criação de uma equipa de até 6 Pokémon.
  <br>Exibe uma tabela com 50 Pokémon aleatórios (de um total de 999 disponíveis).
<p><br>Ao selecionar um Pokémon, a página recarrega e:
  <br>Adiciona o Pokémon à equipa do utilizador.
  <br>Gera outros 50 Pokémon aleatórios para serem exibidos.
  <br>O utilizador pode adicionar Pokémon repetidos à equipa.</p>
<p><br>Restrição: Não é possível adicionar Pokémon que já estejam em uso por outros utilizadores. Caso isso ocorra, será exibida uma mensagem informando que o Pokémon já foi escolhido.</p>
<br>A página também inclui:
  <br>Botão para reiniciar a equipa.
  <br>Indicadores que mostram:
  <br>Quantos Pokémon ainda podem ser adicionados.
  <br>Quando a equipa está completa.
  <br>Quando não há nenhum Pokémon na equipa.
