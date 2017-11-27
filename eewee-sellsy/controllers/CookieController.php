<?php
namespace fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('CookieController')){
    class CookieController{

        private $_name;
        private $_delay;

        public function __construct()
        {
            $this->_name    = "eewee_sellsy";
            $this->_delay   = time()+60*60*24*30;
        }

        /**
         * Exec cookie : add or update
         */
        public function exec()
        {
            // if not cookie, add it empty
            if (!isset($_COOKIE[$this->_name])) {
                $this->add();
            }
            $this->update();
        }

        /**
         * Add cookie (empty)
         */
        public function add()
        {
            if (setcookie( $this->_name, "", $this->_delay, "/" )) {
                return true;
            }
            return false;
        }

        /**
         * Get data cookie
         * @return cookie, false
         */
        public function get()
        {
            $cookie = stripslashes($_COOKIE[$this->_name]);

            if (!isset($cookie)) { return false; }

            // check cookie isn't json, delete
            if (!ToolsControllers::isJson($cookie)) {
                $this->delete();
                return false;
            }
            if (isset($cookie) && !empty($cookie)) {
                return stripslashes($cookie);
            }
            return false;
        }

        /**
         * Update data cookie
         * @return true, false
         */
        public function update()
        {
            // INIT
            $url = ToolsControllers::getUrl();
            $dateTime = new \DateTime();

            // check cookie isn't json, delete
            if (isset($_COOKIE[$this->_name]) && !ToolsControllers::isJson($_COOKIE[$this->_name])) {
                $this->delete();
                return false;
            }

            // if not 1st update
            if (isset($_COOKIE[$this->_name]) && !empty($_COOKIE[$this->_name])) {
                $res_decode = json_decode(stripslashes($_COOKIE[$this->_name]), true);
            }

            // add
            $res_decode[$dateTime->getTimestamp()] = $url;
            // encode
            $res_encode = json_encode($res_decode);

            // save
            if (setcookie( $this->_name, $res_encode, $this->_delay, "/" )) {
                return true;
            }
        }

        /**
         * Delete cookie
         * @return true, false
         */
        public function delete()
        {
            unset( $_COOKIE[$this->_name] );
            if (setcookie( $this->_name, "", time() - 3600, "/" )) {
                return true;
            }
            return false;
        }

        /**
         * Create array for tracking by API
         * @return cookie, false
         */
        public function datasForTracking()
        {
            // INIT
            $cookieTracking = array();
            // GET
            $cookieDatas = $this->get();
            $cookieDatas = json_decode($cookieDatas, true);
            // ERROR
            if (false === $cookieDatas || null === $cookieDatas) {
                return false;
            }

            // STOCK
            foreach ($cookieDatas as $kC=>$vC) {
                $cookieTracking[] = array(
                    'type'		=> "url",
                    'url'		=> $vC,
                    'timestamp' => $kC,
                );
            }
            return $cookieTracking;
        }

    }//fin class
}//fin if