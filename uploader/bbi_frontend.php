<?php

/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 4/12/2017
 * Time: 4:47 PM
 */
class bbi_frontend{
    private $html;
    public function htmlForm(){
        $this->html .= '<form action="" method="post" enctype="multipart/form-data">';
        $this->html .= '<div class="form-group"><input type="file" name="xml_file" id="xml_file"></div>';
        $this->html .= '<div class="form-group"><label class="col-sm-3"></label>';
        $this->html .= '<div class="col-sm-6">';
        $this->html .= '<button type="submit" name="btn_submit" id="btn_submit" class="btn btn-success">UPLOAD FILE</button>';
        $this->html .= '</div>';
        $this->html .= '<div class="col-sm-3"></div>';
        $this->html .= '</div>';
        $this->html .= '</form>';
        return $this->html;
    }
}
$frontend = new bbi_frontend;