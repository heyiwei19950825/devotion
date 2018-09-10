<?php
/* @var $exception \yii\web\HttpException|\Exception */
/* @var $handler \yii\web\ErrorHandler */
if ($exception instanceof \yii\web\HttpException) {
    $code = $exception->statusCode;
} else {
    $code = $exception->getCode();
}
$name = $handler->getExceptionName($exception);
if ($name === null) {
    $name = '服务器错误';
}
if ($code) {
    $name .= " (#$code)";
}

if ($exception->event_id) {
    $message = '事件 ID：' . $exception->event_id;
} else {
    $message = '未知错误：' . get_class($exception);
}

if (method_exists($this, 'beginPage')) {
    $this->beginPage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?= $handler->htmlEncode($name) ?></title>

    <style>
        body {
            font: normal 9pt "Verdana";
            color: #000;
            background: #fff;
        }

        h1 {
            font: normal 18pt "Verdana";
            color: #f00;
            margin-bottom: .5em;
        }

        h2 {
            font: normal 14pt "Verdana";
            color: #800000;
            margin-bottom: .5em;
        }

        h3 {
            font: bold 11pt "Verdana";
        }

        p {
            font: normal 9pt "Verdana";
            color: #000;
        }

        .version {
            color: gray;
            font-size: 8pt;
            border-top: 1px solid #aaa;
            padding-top: 1em;
            margin-bottom: 1em;
        }
    </style>
</head>

<body>
    <h1><?= $handler->htmlEncode($name) ?></h1>
    <h2><?= nl2br($handler->htmlEncode($message)) ?></h2>
    <p>
        请将「事件 ID」发送给我们，以便于我们进行问题追踪。
    </p>
    <div class="version">
        <?= date('Y-m-d H:i:s', time()) ?>
    </div>
    <?php
    if (method_exists($this, 'endBody')) {
        $this->endBody(); // to allow injecting code into body (mostly by Yii Debug Toolbar)
    }
    ?>
</body>
</html>
<?php
if (method_exists($this, 'endPage')) {
    $this->endPage();
}
