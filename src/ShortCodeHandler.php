<?php

require_once('WidgetPrinter.php');

class ShortCodeHandler
{
    /** @var WidgetPrinter */
    private $printer;

    public function __construct()
    {
        $this->printer = new WidgetPrinter();
    }

    public function initShortCode($attr)
    {
//        var_dump($attr);
        $layout = 'horizontalSc';
        $attr['layout'] = $layout;
        $layout = ucfirst($layout);

        return '
        <form name="swFeedSc" id="swFeed'.$layout.'">
            <input name="title" type="hidden" value="' .$attr['title'].'"/>
            <input name="keywords" type="hidden" value="' .$attr['keywords'].'"/>
            <input name="displayCount" type="hidden" value="' .$attr['no_of_product'].'"/>
            <input name="layout" type="hidden" value="' .$attr['layout'].'"/>
            <input name="action" type="hidden" value="get_sw_product"/>
        </form>
        <div class="widgetContentSc">
            <div class="ajaxResponse'.$layout.'" id="ajaxResponse'.$layout.'"></div>
            <div class="next'.$layout.'"><button id="next'.$layout.'" class="next" style="display:none"></button></div>
        </div>';
    }
}
