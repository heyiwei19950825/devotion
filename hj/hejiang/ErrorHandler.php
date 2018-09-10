<?php

namespace app\hejiang;

class ErrorHandler extends \Raven_ErrorHandler
{
    public function handleException($e, $isError = false, $vars = null)
    {
        if ($e instanceof \yii\web\HttpException &&
            !$isError &&
            $this->call_existing_exception_handler) {
            if ($this->old_exception_handler !== null) {
                call_user_func($this->old_exception_handler, $e);
            } else {
                throw $e;
            }
            return;
        }
        parent::handleException($e, $isError, $vars);
    }
}