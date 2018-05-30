<?php
/**
 * Created by PhpStorm.
 * User: 34max
 * Date: 30.05.2018
 * Time: 21:40
 */
$template= 'Товаров не найдено';
if(!empty($model)  ){
    $items = $model->orderItems;
    if(!empty($items)){
        $template = "<table width=100% class='table table-hover'>";
        $i=1;
        foreach ($items as $item) {
            $template.="<tr>"
                ."<td>".$i. "</td>"
                ."<td>".$item->good->name. "</td>"
                ."<td>".number_format($item->good_price , 2,'.',' ')."</td>"
                ."<td>".$item->good_count."</td>"
                ."<td>".number_format(($item->good_price * $item->good_count), 2,'.',' ')."</td>"
                ."</tr>";
            $i++;
        }
        $template.= "</table>";
    }
}

echo $template;