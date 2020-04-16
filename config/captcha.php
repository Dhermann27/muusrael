<?php if (!class_exists('CaptchaConfiguration')) {
    return;
}

// BotDetect PHP Captcha configuration options

return [
    'muustcha' => [
        'UserInputID' => 'CaptchaCode',
        'ImageWidth' => 400,
        'ImageHeight' => 100,
        'SoundEnabled' => false,
        'CodeLength' => 4
    ],

];
