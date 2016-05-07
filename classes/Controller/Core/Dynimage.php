<?php

/**
 * Class Controller_Core_Dynimage
 */
class Controller_Core_Dynimage extends Controller_Website
{
    public $auto_render = false;


    public function action_get()
    {

        $request = $this->request->param('request');
        $type = $this->request->param('type');

        $file_path = "$request.$type";

        if (is_file($full_path = DATAPATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $file_path)) {
            $mime_type = mime_content_type($full_path);
            $this->response->headers('content-type', $mime_type);
            echo file_get_contents($full_path);
        }

    }
}

