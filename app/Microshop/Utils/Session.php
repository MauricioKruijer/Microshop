<?php
namespace Microshop\Utils;


/**
 * Class Session
 *
 * Manage sessions set by application
 *
 * @package Microshop\Utils
 */
class Session {
    /**
     * Check if session is already set otherwise start new session
     * @return bool
     */
    public static function start(){
        if( '' === session_id()) {
            return session_start();
        }
    }

    /**
     * Write session item
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function write($key, $value){
        $_SESSION[$key] = $value;
        return $value;
    }

    /**
     * Read session item, support for two-dimensional array
     *
     * @param $key
     * @param bool $child
     * @return bool
     */
    public static function read($key, $child = false) {
        if(isset($_SESSION[$key])) {
            if(false === $child)
                return $_SESSION[$key];
            if(isset($_SESSION[$key][$child]))
                return $_SESSION[$key][$child];
        }
        return false;
    }

    /**
     * Remove session item with support for two-dimensional array
     *
     * @param $key
     * @param bool $child
     * @return bool
     */
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

    /**
     * Add and item to existing array without loosing all other session items. Let data to be merged
     *
     * @param $key
     * @param $data
     */
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

    /**
     * Used for session debug, prints out $_SESSION array
     */
    public static function dump() {
        echo nl2br(print_r($_SESSION, true));
    }

    /**
     * Remove all session data
     */
    public static function destroy() {
        if('' !== session_id()) {
            session_unset();
            session_destroy();
        }
    }
}