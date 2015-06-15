<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 14/06/15
 * Time: 00:15
 */

namespace Microshop\Utils;


class Session {
    public static function start(){
        if( '' === session_id()) {
            return session_start();
        }
    }

    public static function write($key, $value){
        $_SESSION[$key] = $value;
        return $value;
    }

    public static function read($key, $child = false) {
        if(isset($_SESSION[$key])) {
            if(false === $child)
                return $_SESSION[$key];
            if(isset($_SESSION[$key][$child]))
                return $_SESSION[$key][$child];
        }
        return false;
    }
    public static function remove($key, $child = false) {
        if(Session::read($key, $child)) {
            unset($_SESSION[$key][$child]);
            return true;
        } elseif(Session::read($key)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }
    public static function add($key, $data) {
        if(false !== (self::read($key) ) ) {
            $temp = self::read($key);
            if(is_array($temp)) {
                self::write($key, array_merge($temp, $data));
            }
        } else {
            self::write($key, $data);
        }
    }
    public static function dump() {
        echo nl2br(print_r($_SESSION, true));
    }

    public static function destroy() {
        if('' !== session_id()) {
            session_unset();
            session_destroy();
        }
    }
}