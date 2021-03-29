<?php

namespace Plugin;

class PFortuneTeller extends Plugin {
    public static function install() {
        $class = "\\". __CLASS__;

        \Command::getInstance()->addCommands([
            [
                'command'       => '!idle',
                'function'      => "$class::idleness",
                'description'   => 'Are you bored?'
            ],
            [
                'command'       => '!ask',
                'function'      => "$class::ask",
                'usage'         => '<question>',
                'description'   => 'Ask something'
            ]
        ]);
    }

    public static $actions = [
        'experimenta uma nova distribuição Linux',
        'aprende uma nova linguagem de programação',
        'programa um novo plugin para o chat',
        'faz um trajeto longo de bicicleta',
        'faz alguns exercícios',
        'faz yoga facial',
        'faz exercícios pélvicos',
        'passa um café ou um chá',
        'adota um cachorro',
        'joga uma partida de xadrez',
        'joga até completar um jogo de escape',
        'compra um quebra cabeças',
        'escala uma árvore',
        'planta uma bananeira',
        'incita diálogo com um(a) desconhecido(a)',
        'tenta abandonar algum vício',
        'rememora algum gosto antigo',
        'escuta sua música preferida',
        'inicia a leitura de algum livro',
        'escuta Legião Urbana',
        'escuta Raimundos'
    ];

    public static $answers = [
        'Sim',
        'Não',
        'Talvez',
        'Jamais',
        'Já consultou o Nolêto?',
        'Quem sabe na próxima vida',
        'Não, mas seu pai sim'
    ];

    public static function idleness($user) {
        $action = array_rand(self::$actions, 1);
        \Command::getInstance()->sendMessage($user, 'Hmmmm... por que não '. self::$actions[$action] .'?');
    }

    public static function ask($user, $question) {
        $answer = array_rand(self::$answers, 1);
        \Command::getInstance()->sendMessage($user, self::$answers[$answer]);
    }
}