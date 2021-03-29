<?php

namespace Plugin;

class PClening extends Plugin {
    public static function install() {
        $class = '\\'. __CLASS__;

        \Command::getInstance()->addCommands([
            [
                'command'       => '!noleto',
                'function'      => "$class::greetings",
            ],
            [
                'command'       => '!mdp',
                'function'      => "$class::giveMeDaddy",
            ],
            [
                'command'       => '!irado',
                'function'      => "$class::irado",
                'usage'         => '<name?>'
            ],
            [
                'command'       => '!borat',
                'function'      => "$class::boraTreinar",
                'usage'         => '<name?>'
            ]
        ]);
    }

    public static function greetings($user) {
        $hour = intval(date('G'));
        $message = '';

        if( $hour >= 18 ) {
            $message = 'BNLNCC! (Boa noite, Lucas Nolêto Clener Confirmed!)';
        } else if ( $hour >= 12 ) {
            $message = 'BTLNCC! (Boa tarde, Lucas Nolêto Clener Confirmed!)';
        } else {
            $message = 'BDLNCC! (Bom dia, Lucas Nolêto Clener Confirmed!)';
        }

        $user->secureSendMessage($message);
    }

    public static function giveMeDaddy($user) {
        \Plugin\PCore::sendImage($user, 'https://thumbs.gfycat.com/OblongOrderlyKilldeer-max-1mb.gif');
        $user->secureSendMessage('<b>MIM DE, PAPAI!</b>');
    }

    public static function irado($user, $name) {
        \Plugin\PCore::sendImage($user, 'https://s4.gifyu.com/images/PUTO_COM_UM_COVARDE_FIKAADIKA_cropped-1.gif');
        $user->secureSendMessage("VOU DAR NO SEU FUCINHO" . (isset($name) ? ', <b>'. strtoupper($name) .'</b>' : ''));
    }

    public static function boraTreinar($user, $name) {
        \Plugin\PCore::sendImage($user, 'https://s4.gifyu.com/images/PUTO_COM_UM_COVARDE_FIKAADIKA_cropped.gif');
        $user->secureSendMessage("BORA TREINAR" . (isset($name) ? ', <b>'. strtoupper($name) .'</b>' : ''));
    }
}