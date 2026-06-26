<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class TelegramHelper {
    protected $CI;
    protected $telegram;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->model("TokenModel");

        $telegram_token = $this->CI->TokenModel->get_token('TELEGRAM_TOKEN');
        $this->telegram = new Api($telegram_token);
    }

    public function sendMessageText($chatId, $message){
        return $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }
}
