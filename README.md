# Import Categories Engine
[![Build Status][travis-badge]][travis-source]
[![GitHub issues][issues-badge]][issues-link]
[![Source Code][badge-source]][source]

Script para cadastro de categorias em lote no Bob. Importa um arquivo csv e executa requisições http na api de categorias.

#### Usage
```
$ php import.php -f {file} -h {host} -s {store}
```

#### Parâmetros
|Parâmetro|Descrição|
|---|---|
|-f|Path do arquivo csv|
|-h|Host do Bob|
|-s|Nome da store que receberá os dados de categorias|

#### Arquivo CSV
|Colunas|name|parent_id|
| --- | --- | --- |
|Descrição|Nome da categoria|Nome da categoria pai (caso exista). Entenda-se como o nó superior mais próximo|
|Obrigatório|Sim|Não|
|Caracteres|Caixa alta, baixa e acentos|Exatamente como preenchido na coluna name da panilha|


[travis-badge]: <https://travis-ci.org/madsilver/import-categories-engine.svg?branch=master>
[travis-source]: <https://travis-ci.org/madsilver/import-categories-engine>
[issues-badge]: <https://img.shields.io/github/issues/madsilver/import-categories-engine.svg>
[issues-link]:<https://github.com/madsilver/import-categories-engine/issues>
[badge-source]: <https://img.shields.io/badge/source-madsilver%2Fice-blue.svg>
[source]: <https://github.com/madsilver/import-categories-engine>