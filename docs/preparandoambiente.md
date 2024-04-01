# Criando ambiente para desenvolvimento

Os comandos listados a seguir são para dispositivos linux.

- Instalar o curl
```
sudo apt update && sudo apt install curl
```

- Instalar o docker (comando)
```
curl https://get.docker.com | bash
```

- (OPCIONAL) - Para não ser necessário utilizar o *sudo* antes das instruções do docker execute:

```
sudo groupadd docker && sudo gpasswd -a $USER docker
```

- Utilize o arquivo **docker-compose.yaml** para iniciar os containers. (caso não queira executar os containers em segundo plano remova o *-d* )
```
docker compose up -d
```

- Para vizualizar os containers em execução:

```
docker compose ps
```

- Em um navegador acesse as respectivas portas:

    <p>
    <strong>Wordpress:</strong>
    <a href="http://localhost:8082" style="color: green; display: inline;" target="_blank">localhost:8082</a><br>
    <strong>PhpMyAdmin:</strong>
    <a href="http://localhost:8083" style="color: green; display: inline;" target="_blank">localhost:8083</a>
    </p>

    -> No wordpress faça a configuração incial para acessar o wordpress

    -> No PhpMyAdmin faça login com <span style="color: blue;">**Usuário: Admin** e **Senha: Admin**</span>

- Realize as configurações do wordpress para criar seu site

- No caso de após a configuração do wordpress não ser redirecionado para a página de admin acesse:

    <a href="http://localhost:8082/wp-admin" style="color: green;" target="_blank">localhost:8082/wp-admin</a>

- Em teoria, o plugin LGBTQ+ Connect já é adicionado ao seu wordpress automaticamente, caso não esteja listado na aba plugins realize os seguintes passos, em caso contrário pode pular essa etapa.
    
    * Na tela de plugins do wordpress clique em adicionar plugin, navegue para o repositório do projeto e selecione o arquivo zipado do plugin

- Em qualquer página do seu site escreva o seguinte shortcode: **[lgbtq_connect]**