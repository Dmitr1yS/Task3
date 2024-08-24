<?php

namespace NamePlugin;

class NameApi {
    private $api_url;

    public function __construct($api_url) {
        $this->api_url = $api_url;
    }

    public function list_vacancies($post, $vid = 0) {
        global $wpdb;

        if (!is_object($post)) {
            return false;
        }

        $ret = [];
        $page = 0;

        do {
            $params = http_build_query([
                'status' => 'all',
                'id_user' => $this->self_get_option('superjob_user_id'),
                'with_new_response' => 0,
                'order_field' => 'date',
                'order_direction' => 'desc',
                'page' => $page,
                'count' => 100
            ]);

            $res = $this->api_send($this->api_url . '/hr/vacancies/?' . $params);
            $res_o = json_decode($res);

            if ($res !== false && is_object($res_o) && isset($res_o->objects)) {
                $ret = array_merge($ret, $res_o->objects);

                if ($vid > 0) { // Если задан ID вакансии, ищем её
                    foreach ($res_o->objects as $value) {
                        if ($value->id == $vid) {
                            return $value; // Найдена конкретная VAC
                        }
                    }
                }

                $page++;
            } else {
                return false; 
            }

        } while (isset($res_o->more) && $res_o->more);

        return $ret; // Возвращаем все вакансии, если конкретная не найдена
    }

    public function api_send($url) {
        return ''; 
    }

    public function self_get_option($option_name) {
        return ''; 
    }
}