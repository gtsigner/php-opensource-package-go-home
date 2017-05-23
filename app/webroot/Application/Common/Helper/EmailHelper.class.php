<?php
/**
 * Created by PhpStorm.
 * User: QQ: 209900956
 * oschina:http://git.oschina.net/zhaojunlike
 * github: http://github.com/zhaojunlike
 * Author：软件开发，网站建设/
 * Date: 2016/8/5
 * Time: 11:45
 */

namespace Common\Helper;


class EmailHelper
{

    private static $instance;
    private $config = array(
        'EMAIL_SERVER_HOST' => '',
        'EMAIL_SERVER_PORT' => '',
        'EMAIL_SERVER_USERNAME' => '',
        'EMAIL_SERVER_PWD' => '',
        'EMAIL_SERVER_USER' => '',
    );

    private function __construct($config = array())
    {
        if (count($config) <= 0) {
            //获取系统配置
            $this->config['EMAIL_SERVER_HOST'] = C('EMAIL_SERVER_HOST');
            $this->config['EMAIL_SERVER_PORT'] = C('EMAIL_SERVER_PORT');
            $this->config['EMAIL_SERVER_USERNAME'] = C('EMAIL_SERVER_USERNAME');
            $this->config['EMAIL_SERVER_USER'] = C('EMAIL_SERVER_USER');
            $this->config['EMAIL_SERVER_PWD'] = C('EMAIL_SERVER_PWD');
        } else {
            $this->config = $config;
        }
    }

    public static function getInstance($config = array())
    {
        if (self::$instance == null) {
            self::$instance = new EmailHelper($config);
        } else {
            if (count($config) > 0) {
                self::$instance->config = $config;
            }
        }
        return self::$instance;
    }

    /**
     * @param $to 收件人邮箱
     * @param string $subject 标题
     * @param string $content 内容，可以只用createContent获取
     * @return bool
     */
    public function sendEmail($to, $subject = '', $content = '')
    {
        require_once THINK_PATH . 'Library\Vendor\phpmailer\PHPMailerAutoload.php';
        $mail = new \PHPMailer();
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //调试输出格式
        //$mail->Debugoutput = 'html';
        //smtp服务器
        $mail->Host = $this->config['EMAIL_SERVER_HOST'];
        //端口 - likely to be 25, 465 or 587
        $mail->Port = $this->config['EMAIL_SERVER_PORT'];
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //用户名
        $mail->Username = $this->config['EMAIL_SERVER_USERNAME'];
        //密码
        $mail->Password = $this->config['EMAIL_SERVER_PWD'];
        //Set who the message is to be sent from
        $mail->setFrom($this->config['EMAIL_SERVER_USER']);
        //回复地址
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        //接收邮件方
        $mail->addAddress($to);
        //标题
        $mail->Subject = $subject;
        //HTML内容转换
        $mail->isHTML(true);
        $mail->Body = $content;
        $mail->AltBody = "text/html";

        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        //添加附件
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @param $tpl_id
     * @param array $data
     * @return string
     */
    public function createContent($tpl_id, $data = array())
    {
        $tpl_1 = '';
        $tpl_2 = '';
        $tpl_3 = '';
        $tpl_4 = '';
        $tpl_5 = '';
        $ret = '';
        foreach ($data as $k => $v) {
            $ret .= "<h4><span style='color: #000;' class='title'>{$k}:</span><span style='color: #ff0000'>{$v}</span></h4>";
        }
        return $ret;
    }
}