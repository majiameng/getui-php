<?php

namespace tinymeng\getui;

use tinymeng\getui\request\push\android\GTAndroid;
use tinymeng\getui\request\push\android\GTThirdNotification;
use tinymeng\getui\request\push\android\GTUps;
use tinymeng\getui\request\push\GTNotification;
use tinymeng\getui\request\push\GTPushChannel;
use tinymeng\getui\request\push\GTPushMessage;
use tinymeng\getui\request\push\GTPushRequest;
use tinymeng\getui\request\push\ios\GTAlert;
use tinymeng\getui\request\push\ios\GTAps;
use tinymeng\getui\request\push\ios\GTIos;

class GeTui
{
    private $APPID = '';
    private $APPKEY = '';
    private $APPSECRET = '';
    private $MASTERSECRET = '';
    private $PACKAGE = '';
    public function __construct()
    {

    }
    public function initParams(array $config)
    {
        $this->APPID = $config['app_id'] ?? '';
        $this->APPKEY = $config['app_key'] ?? '';
        $this->MASTERSECRET = $config['master_secret'] ?? '';
        $this->PACKAGE = $config['package'] ?? '';
        return $this;
    }
    public function pushSingleByCid($title, $content, $payload, $cid)
    {
        //创建API，APPID等配置参考 环境要求 进行获取
        $api = new GTClient("https://restapi.getui.com", $this->APPKEY, $this->APPID, $this->MASTERSECRET);
        //设置推送参数
        if (is_array($payload)) {
            $pj = json_encode($payload);
        } else {
            $pj = $payload;
        }

        $push = new GTPushRequest();
        $osn = md5(time() . mt_rand(1, 9999999));
        $push->setRequestId((string) $osn);

        $message = new GTPushMessage();
        $intent = "intent:#Intent;launchFlags=0x14000000;action=android.intent.action.oppopush;component={$this->PACKAGE}/io.dcloud.PandoraEntry;S.UP-OL-SU=true;S.title={$title};S.content={$content};S.payload={$pj};end";
        //个推
        $notify = new GTNotification();
        $notify->setTitle($title);
        $notify->setBody($content);
        $notify->setPayload($pj);
        $notify->setBadgeAddNum(1);
        $notify->setClickType("intent");
        $notify->setIntent($intent);
        $notify->setChannelLevel(4);
        $notify->setBigText($content);
        $message->setNotification($notify);

        $channel = new GTPushChannel();

        //Android
        $thirdnotify = new GTThirdNotification();
        $ups = new GTUps();
        $gtAndroid = new GTAndroid();
        $thirdnotify->setTitle($title);
        $thirdnotify->setBody($content);
        $thirdnotify->setPayload($pj);
        $thirdnotify->setClickType("intent");
        $thirdnotify->setIntent($intent);
        $ups->setNotification($thirdnotify);
        // $upsback= $ups->setTransmission(json_encode($touchuan));//厂商透传
        $gtAndroid->setUps($ups);
        $channel->setAndroid($gtAndroid);
        //echo  $intent;
        //点击通知后续动作，目前支持以下后续动作:
        //1、intent：打开应用内特定页面url：打开网页地址。2、payload：自定义消息内容启动应用。3、payload_custom：自定义消息内容不启动应用。4、startapp：打开应用首页。5、none：纯通知，无后续动作
        //$notify->setIntent($intent);
        // $notify->setChannelLevel(3);
        // $touchuan=['title'=>$title,'content'=>$content,'payload'=>$package];

        //$message->setTransmission(json_encode($touchuan));//个推透传

        //ios
        $ios = new GTIos();
        $ios->setType("notify");
        $ios->setAutoBadge("1");
        $ios->setPayload($pj);

        //aps设置
        $aps = new GTAps();
        $aps->setContentAvailable(0);

        $alert = new GTAlert();
        $alert->setTitle($title);
        $alert->setBody($content);

        $aps->setAlert($alert);
        $ios->setAps($aps);
        $channel->setIos($ios);

        $push->setPushMessage($message);
        $push->setPushChannel($channel);
        $push->setCid($cid);
        //处理返回结果
        $result = $api->pushApi()->pushToSingleByCid($push);
        // print_r($result);
        return $result;
    }
}
