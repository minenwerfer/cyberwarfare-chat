<?php

namespace Plugin;

class PCore extends Plugin {
    public static function install() {
        $class = '\\'. __CLASS__;

        \Command::getInstance()->addCommands([
            [
                'command'       => '!help',
                'function'      => "$class::help",
                'description'   => 'Output available commands'
            ],
            [
                'command'       => '!clear',
                'function'      => "$class::clearSession",
                'description'   => 'Clears current session for all users',
                'privileged'    => true
            ],
            [
                'command'       => '!destroy',
                'function'      => "$class::destroySession",
                'description'   => 'Destroys current session and logout',
                'privileged'    => true
            ],
            [
                'command'       => '!important',
                'function'      => "$class::sendImportant",
                'usage'         => '<what>',
                'description'   => 'Tells something important',
                'privileged'    => true
            ],
            [
                'command'       => '!log',
                'function'      => "$class::log",
                'usage'         => "<pattern?>",
                'description'   => 'Displays log, optionally filters by pattern',
                'privileged'    => true
            ],
            [
                'command'       => '!clearlog',
                'function'      => "$class::clearLog",
                'description'   => 'Clears log globally',
                'privileged'    => true
            ],
            [
                'command'       => '!ban',
                'function'      => "$class::ban",
                'usage'         => '<iphash>',
                'description'   => 'Bans user globally',
                'privileged'    => true
            ],
            [
                'command'       => '!unban',
                'function'      => "$class::unban",
                'usage'         => '<iphash>',
                'description'   => 'Unbans user globally',
                'privileged'    => true
            ],
            [
                'command'       => '!lsbans',
                'function'      => "$class::listBans",
                'description'   => 'List banned IP hashes',
                'privileged'    => true
            ],
            [
                'command'       => '!promote',
                'function'      => "$class::promote",
                'usage'         => '<hash>',
                'description'   => 'Promotes user globally',
                'privileged'    => true
            ],
            [
                'command'       => '!demote',
                'function'      => "$class::demote",
                'usage'         => '<hash>',
                'description'   => 'Demotes user globally',
                'privileged'    => true
            ],
            [
                'command'       => '!lsadmins',
                'function'      => "$class::listAdmins",
                'description'   => 'List admins'
            ],
            [
                'command'       => '!priv',
                'function'      => "$class::getPrivilege",
                'description'   => 'Outputs whether current user has privileges or not'
            ],
            [
                'command'       => '!hash',
                'function'      => "$class::getHash",
                'description'   => 'Outputs current user hash'
            ],
            [
                'command'       => '!logout',
                'function'      => "$class::logout",
                'description'   => 'Exits current session'
            ],
            [
                'command'       => '!lsplugins',
                'function'      => "$class::getPlugins",
                'description'   => 'Lists installed plugins'
            ],
            [
                'command'       => '!lschats',
                'function'      => "$class::getSessions",
                'description'   => 'Lists sessions'
            ],
            [
                'command'       => '!notice',
                'function'      => "$class::setNotice",
                'usage'         => '<notice>',
                'description'   => 'Set header message',
                'privileged'    => true
            ],
            [
                'command'       => '!image',
                'function'      => "$class::sendImage",
                'usage'         => '<url>',
                'description'   => 'Sends an image'
            ]
        ]);
    }

    public static function help($user) {
        $message = '<pre>';
        foreach( \Command::getInstance()->getCommands() as $cmd ) {
            extract($cmd);

            if( (isset($privileged) && $privileged === True) && !$user->isPrivileged() ) {
                unset($privileged);
                continue;
            }

            $usage = isset($usage) ? $usage : '<>';
            $usage = htmlentities($usage);
            $privileged = isset($privileged) && $privileged === True ? 'admin' : 'all';
            $privileged = str_pad("($privileged)", 8);
            $description = isset($description) ? $description : '-';

            $command = str_pad($command, 13);
            $usage = str_pad($usage, 22);

            $message .= "$privileged$command$usage";
            $message .= "$description<br/>";
            
            unset($command);
            unset($usage);
            unset($description);
            unset($privileged);
        }

        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function clearSession($user) {
        $user->session->clear();
        $user->session->open();
        \Command::getInstance()->sendMessage($user, "Chat refreshed by $user->name");
    }

    public static function destroySession($user) {
        $user->session->clear();
        \Session::getInstance()->destroy();
    }

    public static function sendImportant($user, $message) {
        $user->secureSendMessage("<h2>$message</h2>");
    }

    public static function logout($user) {
        \Command::getInstance()->sendMessage($user, "Bye, $user->name!");
        \Session::getInstance()->destroy();
    }

    public static function log($user, $pattern = NULL) {
        $message = '<pre>';

        foreach( \Logger::getInstance()->get($pattern) as $entry ) {
            $message .= implode(',', $entry);
            $message .= '<br/>';
        }

        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function clearLog($user) {
        \Logger::getInstance()->clear();
        \Command::getInstance()->sendMessage($user, "Log cleared");
    }

    public static function ban($user, $iphash) {
        \Session::getInstance()->ban($iphash);
        \Command::getInstance()->sendMessage($user, "$iphash banned");
    }

    public static function unban($user, $iphash) {
        \Session::getInstance()->unban($iphash);
        \Command::getInstance()->sendMessage($user, "$iphash unbanned");
    }

    public static function promote($user, $hash) {
        \Session::getInstance()->promote($hash);
        \Command::getInstance()->sendMessage($user, "$hash promoted");
    }

    public static function demote($user, $hash) {
        \Session::getInstance()->demote($hash);
        \Command::getInstance()->sendMessage($user, "$hash demoted");
    }

    public static function listBans($user) {
        $message = '<pre>';
        foreach( \Session::getInstance()->banned_list as $iphash ) {
            $message .= "$iphash<br/>";
        }

        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function listAdmins($user) {
        $message = '<pre>';
        foreach( \Session::getInstance()->privileged_list as $hash ) {
            $message .= "$hash<br/>";
        }

        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function getHash($user) {
        \Command::getInstance()->sendMessage($user, "Hash for {$user->getDisplay()} is <b>$user->hash</b>");
    }

    public static function getPrivilege($user) {
        $privilege = $user->isPrivileged() ? 'admin' : 'user';
        \Command::getInstance()->sendMessage($user, "User {$user->getDisplay()} is <b>$privilege</b>");
    }

    public static function getPlugins($user) {
        $message = '<pre>';
        foreach( \PluginManager::getInstance()->getPlugins() as $plugin ) {
            $message .= "$plugin<br>";
        }


        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function getSessions($user) {
        $message = '<pre>';
        foreach( glob(STORE_PATH . '/*') as $file ) {
            if( strstr($file, ".log") ) {
                continue;
            }

            $session = basename($file);
            $message .= "$session<br/>";
        }

        $message .= '</pre>';
        \Command::getInstance()->sendMessage($user, $message);
    }

    public static function setNotice($user, $notice) {
        $success = @file_put_contents(SETTINGS_PATH . '/notice.txt', $notice);

        if( $success ) {
            \Command::getInstance()->sendMessage($user, "Notice set");
        }
    }

    public static function getNotice() {
        return @file_get_contents(SETTINGS_PATH . '/notice.txt');
    }

    public static function sendImage($user, $url) {
        $user->secureSendMessage(
            "<img src=\"$url\" />"
        );
    }
}