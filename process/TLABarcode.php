<style>
    table.barcode{
        zoom: 2;
        margin: auto;
    }
    table.barcode td{
        box-sizing: unset;
        padding: unset !important;
    }
    table.barcode td div{
        box-sizing: unset;
    }
    div.barItem{
        border-left: 1px solid black;
        height: 20px
    }
</style>
<?php 
    global $chart128asc, $chart128Width;
    $char128asc=' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    $chart128Width = array(
        '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213', // 0-9 
        '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132', // 10-19 
        '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211', // 20-29 
        '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313', // 30-39 
        '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331', // 40-49 
        '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111', // 50-59 
        '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214', // 60-69 
        '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111', // 70-79 
        '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141', // 80-89 
        '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141', // 90-99
        '114131','311141','411131','211412','211214','211232','23311120' ); // 100-106
    function createBar128($text){
        global $chart128Width, $char128asc;
        $width = $chart128Width[$sum = 104];
        $onChar=1;
        for($i=0;$i<strlen($text);$i++){// GO THRU TEXT GET LETTERS
            if (!(($position = strpos($char128asc,$text[$i])) === false )){ // SKIP NOT FOUND CHARS
                $width.= $chart128Width[$position];
                $sum += $onChar++ * $position;
            } 
        } 
        $width .= $chart128Width[ $sum % 103 ].$chart128Width[106]; //Check Code, then END
        $html="<table class=\"barcode\" cellpadding=0 cellspacing=0><tr>"; 
        for($x=0;$x<strlen($width);$x+=2) // code 128 widths: black border, then white space
        $html .= "<td><div class=\"barItem\" style=\"border-left-width:{$width[$x]}px;width:{$width[$x+1]}px\"></div></td>"; 
        return "$html<tr><td colspan=".strlen($width)." align=center style=\"letter-spacing: 5\"><font family=arial size=2>$text</td></tr></table>"; 
    }
?>