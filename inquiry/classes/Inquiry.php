<?php
namespace inquiry; 

class Inquiry extends \inquiry\Controller {
    
    public function submit_inquiry() {
      
      if(isset($_POST['button'])) {
        //tokenを確認
        $this->validate_token();
        
        $this->validation();
        
        if ($this->hasError()) {
          return;
        } else {
          list($phone, $title, $body) = $this->customize_values();
          $_SESSION['values'] =  [ 
                                  'title' => $title,
                                  'name' => $_POST['name'],
                                  'email' => $_POST['email'],
                                  'phone' => $phone,
                                  'content' => $_POST['content'],
                                  'body' => $body
                                 ];
        header('Location: ' . SITE_URL . '/html/confirm.php');
        exit;
        }
      }
    }
    
    public function register_inquiry() {
        
        if ($_SESSION['values'] == null) {
            echo 'ブラウザを戻る操作がありました</br>最初からやり直してください';
            echo '<form action="inquiry.php">';
            echo   '<button>ホームへ戻る</button>';
            echo'</form>';
            exit;
          } 

        if (isset($_POST['button_submit'])) {
  
            //DBへ登録
            //DBへ登録する場合はコメントアウトを外す
            // $InquirySQL = new \inquiry\InquirySQL();
            // $InquirySQL->register_inquiry_to_db();
            header('Location: ' . SITE_URL . '/html/mail_sender_by_gmail.php');
            exit;
        }
        
    }
    
    protected function customize_values() {
      
      $phone = str_replace("-", "", $_POST['phone']);
      
      $title = '';
      //日本語に変換
      if($_POST['title'] == 'opinion') {
        $title = 'ご意見';
      } elseif($_POST['title'] == 'feedback') {
        $title = 'ご感想';
      } elseif($_POST['title'] == 'other') {
        $title = 'その他';
      }
      //HTML形式でメール本文を作成しておく
       $body = '
        <h3>お問い合わせ内容：'. $title .'</h3></br>
        <p>名前：'. $_POST['name'] .'</p></br>
        <p>メールアドレス：'. $_POST['email'] .'</p></br>
        <p>電話番号：'. $_POST['phone'] .'</p></br>
        <h3>内容</h3></br>
        <p>'. $_POST['content'] .'</p>
        ';
        
        return[$phone, $title, $body];
      
    }
    
    protected function validate_token() {
      if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      header('Location: ' . SITE_URL . '/html/invalidToken.php');
      exit;
    }
    
    }
    protected function validation() {
      
    //件名のチェック
    if ($_POST['title'] == 'none') {
      $this->setErrors('emptyTitle' , '※問い合わせ内容が選択されていません');
    } else {
      $this->setValues($_POST['title'], 'selected');
    }
    //名前のチェック
    if (empty($_POST['name'])) {
      $this->setErrors('emptyName' , '※名前を入力してください');
    } else {
      $this->setValues('name', $_POST['name']);
    }
    //メールアドレスのチェック
    if (empty($_POST['email'])) {
      $this->setErrors('emptyEmail' , '※Emailを入力してください');
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $this->setErrors('InvalidEmail' , '※無効なEmailです');
    } else {
      $this->setValues('email', $_POST['email']);
    }
    //電話番号のチェック
     if (empty($_POST['phone'])) {
      $this->setErrors('emptyPhone' , '※電話番号を入力してください');
    } elseif (!preg_match("/^0\d{9,10}$/", str_replace("-", "", $_POST['phone']))) {
      $this->setErrors('InvalidPhone' , '※無効な電話番号です');
    } else {
      $this->setValues('phone', $_POST['phone']);
    }
    //問い合わせ内容のチェック
     if (empty($_POST['content'])) {
      $this->setErrors('emptyContent' , '※問い合わせ内容を入力してください');
    } else {
      $this->setValues('content', $_POST['content']);
    }
    
    }
}